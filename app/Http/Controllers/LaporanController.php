<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Denda;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Exports\PeminjamanExport;

class LaporanController extends Controller
{
    // Tampilan utama laporan
    public function index()
    {
        return view('admin.laporan.index');
    }

    // Filter laporan peminjaman
    public function peminjaman(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $peminjaman = Peminjaman::with(['buku', 'user.anggota'])
            ->whereBetween('tanggal_pinjam', [$request->tanggal_awal, $request->tanggal_akhir])
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();

        return view('admin.laporan.peminjaman', [
            'peminjaman' => $peminjaman,
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
        ]);
    }

    // Export PDF
    public function exportPeminjamanPDF(Request $request)
    {
        $peminjaman = Peminjaman::with(['buku', 'user.anggota'])
            ->whereBetween('tanggal_pinjam', [$request->tanggal_awal, $request->tanggal_akhir])
            ->get();

        $pdf = PDF::loadView('admin.laporan.export.peminjaman_pdf', [
            'peminjaman' => $peminjaman,
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
        ]);

        return $pdf->download('laporan_peminjaman.pdf');
    }

    // Export Excel
    public function exportPeminjamanExcel(Request $request)
    {
        return Excel::download(
            new PeminjamanExport($request->tanggal_awal, $request->tanggal_akhir),
            'laporan_peminjaman.xlsx'
        );
    }
}