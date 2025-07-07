<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PeminjamanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $tanggalAwal;
    protected $tanggalAkhir;

    public function __construct($tanggalAwal, $tanggalAkhir)
    {
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
    }

    public function collection()
    {
        return Peminjaman::with(['buku', 'user.anggota'])
            ->whereBetween('tanggal_pinjam', [$this->tanggalAwal, $this->tanggalAkhir])
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Anggota',
            'Buku',
            'Tanggal Pinjam',
            'Tanggal Kembali',
            'Status',
            'Denda (Rp)'
        ];
    }

    public function map($peminjaman): array
    {
        return [
            $peminjaman->id,
            $peminjaman->user->anggota->nama,
            $peminjaman->buku->judul,
            $peminjaman->tanggal_pinjam,
            $peminjaman->tanggal_kembali,
            $peminjaman->status,
            $peminjaman->denda ? number_format($peminjaman->denda->jumlah, 0, ',', '.') : '0',
        ];
    }
}