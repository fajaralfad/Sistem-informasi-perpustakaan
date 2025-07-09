<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AnggotaExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $anggota;

    public function __construct($anggota)
    {
        $this->anggota = $anggota;
    }

    public function collection()
    {
        return $this->anggota;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Email',
            'Telepon',
            'Alamat',
            'Status Verifikasi',
            'Tanggal Daftar',
            'Total Peminjaman',
        ];
    }

    public function map($anggota): array
    {
        return [
            $anggota->id,
            $anggota->name,
            $anggota->email,
            $anggota->phone ?? '-',
            $anggota->address ?? '-',
            $anggota->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi',
            $anggota->created_at->format('d/m/Y H:i'),
            $anggota->peminjamans->count(),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
            
            // Styling specific cells
            'A:H' => ['alignment' => ['wrapText' => true]],
        ];
    }
}