<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PeminjamanExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $peminjamans;

    public function __construct($peminjamans)
    {
        $this->peminjamans = $peminjamans;
    }

    public function collection()
    {
        return $this->peminjamans;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Anggota',
            'Email Anggota',
            'Judul Buku',
            'Pengarang',
            'Tanggal Pinjam',
            'Tanggal Kembali',
            'Status',
            'Diperpanjang',
            'Denda',
        ];
    }

    public function map($peminjaman): array
    {
        return [
            $peminjaman->id,
            $peminjaman->user->name,
            $peminjaman->user->email,
            $peminjaman->buku->judul,
            $peminjaman->buku->pengarang->nama ?? '-',
            $peminjaman->tanggal_pinjam->format('d/m/Y H:i'),
            $peminjaman->tanggal_kembali->format('d/m/Y H:i'),
            $this->getStatusText($peminjaman->status),
            $peminjaman->diperpanjang ? 'Ya' : 'Tidak',
            $peminjaman->denda ? 'Rp ' . number_format($peminjaman->denda->sum('jumlah'), 0, ',', '.') : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
            
            // Styling specific cells
            'A:J' => ['alignment' => ['wrapText' => true]],
        ];
    }

    private function getStatusText($status)
    {
        $statuses = [
            'dipinjam' => 'Dipinjam',
            'booking' => 'Booking',
            'dikembalikan' => 'Dikembalikan',
            'terlambat' => 'Terlambat',
            'pending' => 'Menunggu Konfirmasi',
        ];

        return $statuses[$status] ?? $status;
    }
}