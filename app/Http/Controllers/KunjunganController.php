<?php

namespace App\Http\Controllers;

use App\Exports\KunjunganExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Kunjungan;
use App\Models\User;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    public function __construct()
    {
    
    }

    public function index()
    {
        return view('admin.kunjungan.index');
    }

    public function aktif()
    {
        return view('admin.kunjungan.aktif');
    }

    public function catatMasuk(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tujuan' => 'required|in:' . implode(',', array_keys(Kunjungan::listTujuan())),
            'kegiatan' => 'nullable|string|max:255',
        ]);

        // Check if user has an active visit
        $activeVisit = Kunjungan::where('user_id', $request->user_id)
            ->whereNull('waktu_keluar')
            ->first();

        if ($activeVisit) {
            return redirect()->back()
                ->with('error', 'Pengguna sudah memiliki kunjungan aktif');
        }

        $kunjungan = Kunjungan::create([
            'user_id' => $request->user_id,
            'waktu_masuk' => now(),
            'tujuan' => $request->tujuan,
            'kegiatan' => $request->kegiatan,
        ]);

        return redirect()->route('admin.kunjungan.aktif')
            ->with('success', 'Kunjungan berhasil dicatat');
    }

    public function catatKeluar($id)
    {
        $kunjungan = Kunjungan::findOrFail($id);
        
        if ($kunjungan->waktu_keluar) {
            return redirect()->route('admin.kunjungan.aktif')
                ->with('error', 'Kunjungan sudah dicatat keluar sebelumnya');
        }
        
        $kunjungan->waktu_keluar = now();
        
        // Ensure proper Carbon instances
        $waktuMasuk = Carbon::instance($kunjungan->waktu_masuk);
        $waktuKeluar = Carbon::instance($kunjungan->waktu_keluar);
        
        $kunjungan->durasi = $waktuMasuk->diffInMinutes($waktuKeluar);
        $kunjungan->save();

        return redirect()->route('admin.kunjungan.aktif')
            ->with('success', 'Kunjungan keluar berhasil dicatat');
    }

    public function exportExcel(Request $request)
    {
        $kunjungans = Kunjungan::with('user')
            ->when($request->search, function($query) use ($request) {
                $query->whereHas('user', function($q) use ($request) {
                    $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
                });
            })
            ->when($request->status, function($query) use ($request) {
                if ($request->status == 'aktif') {
                    $query->whereNull('waktu_keluar');
                } else {
                    $query->whereNotNull('waktu_keluar');
                }
            })
            ->when($request->tanggal, function($query) use ($request) {
                $query->whereDate('waktu_masuk', $request->tanggal);
            })
            ->when($request->date_range, function($query) use ($request) {
                $dates = explode(' - ', $request->date_range);
                if (count($dates) == 2) {
                    $start = Carbon::createFromFormat('Y-m-d', $dates[0])->startOfDay();
                    $end = Carbon::createFromFormat('Y-m-d', $dates[1])->endOfDay();
                    $query->whereBetween('waktu_masuk', [$start, $end]);
                }
            })
            ->orderBy('waktu_masuk', 'desc')
            ->get();

        // Calculate durations properly
        $kunjungans->transform(function ($kunjungan) {
            if ($kunjungan->waktu_keluar) {
                $waktuMasuk = Carbon::instance($kunjungan->waktu_masuk);
                $waktuKeluar = Carbon::instance($kunjungan->waktu_keluar);
                $kunjungan->durasi = $waktuMasuk->diffInMinutes($waktuKeluar);
            }
            return $kunjungan;
        });

        $filename = 'laporan-kunjungan-' . Carbon::now()->format('YmdHis') . '.xlsx';

        return Excel::download(new KunjunganExport($kunjungans), $filename);
    }

    public function exportPdf(Request $request)
    {
        $kunjungans = Kunjungan::with('user')
            ->when($request->search, function($query) use ($request) {
                $query->whereHas('user', function($q) use ($request) {
                    $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
                });
            })
            ->when($request->status, function($query) use ($request) {
                if ($request->status == 'aktif') {
                    $query->whereNull('waktu_keluar');
                } else {
                    $query->whereNotNull('waktu_keluar');
                }
            })
            ->when($request->tanggal, function($query) use ($request) {
                $query->whereDate('waktu_masuk', $request->tanggal);
            })
            ->when($request->date_range, function($query) use ($request) {
                $dates = explode(' - ', $request->date_range);
                if (count($dates) == 2) {
                    $start = Carbon::createFromFormat('Y-m-d', $dates[0])->startOfDay();
                    $end = Carbon::createFromFormat('Y-m-d', $dates[1])->endOfDay();
                    $query->whereBetween('waktu_masuk', [$start, $end]);
                }
            })
            ->orderBy('waktu_masuk', 'desc')
            ->get();

        // Calculate durations in minutes for completed visits
        $totalDurationMinutes = $kunjungans->sum(function($kunjungan) {
            if ($kunjungan->waktu_keluar) {
                return Carbon::parse($kunjungan->waktu_masuk)
                    ->diffInMinutes(Carbon::parse($kunjungan->waktu_keluar));
            }
            return 0;
        });

        $stats = [
            'total' => $kunjungans->count(),
            'aktif' => $kunjungans->whereNull('waktu_keluar')->count(),
            'selesai' => $kunjungans->whereNotNull('waktu_keluar')->count(),
            'total_durasi' => $totalDurationMinutes,
            'total_durasi_formatted' => $this->formatDuration($totalDurationMinutes),
        ];

        $pdf = Pdf::loadView('admin.kunjungan.laporan-pdf', [
            'kunjungans' => $kunjungans,
            'stats' => $stats,
            'tanggal' => Carbon::now()->translatedFormat('d F Y'),
            'search' => $request->search ?? 'Semua',
            'filter_status' => $request->status ?? 'Semua',
            'filter_tanggal' => $request->tanggal ?? 'Semua',
            'date_range' => $request->date_range ?? null,
        ]);

        return $pdf->download('laporan-kunjungan-' . Carbon::now()->format('YmdHis') . '.pdf');
    }

    protected function formatDuration($minutes)
    {
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        return sprintf('%02d:%02d', $hours, $remainingMinutes);
    }
}