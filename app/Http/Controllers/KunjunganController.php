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
        $kunjungan->catatKeluar();

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
            ->orderBy('waktu_masuk', 'desc')
            ->get();

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
            ->orderBy('waktu_masuk', 'desc')
            ->get();

        $stats = [
            'total' => $kunjungans->count(),
            'aktif' => $kunjungans->whereNull('waktu_keluar')->count(),
            'selesai' => $kunjungans->whereNotNull('waktu_keluar')->count(),
        ];

        $pdf = Pdf::loadView('admin.kunjungan.laporan-pdf', [
            'kunjungans' => $kunjungans,
            'stats' => $stats,
            'tanggal' => Carbon::now()->translatedFormat('d F Y'),
            'search' => $request->search ?? 'Semua',
            'filter_status' => $request->status ?? 'Semua',
            'filter_tanggal' => $request->tanggal ?? 'Semua',
        ]);

        return $pdf->download('laporan-kunjungan-' . Carbon::now()->format('YmdHis') . '.pdf');
    }
}