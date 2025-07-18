<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class AnggotaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithColumnFormatting
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
            'NIP',
            'NRP',
            'Email',
            'Telepon',
            'Status Verifikasi',
            'Tanggal Daftar',
            'Peminjaman Aktif',
            'Peminjaman Selesai'
        ];
    }

    public function map($anggota): array
    {
        return [
            $anggota->id,
            $anggota->name,
            "'" . $anggota->nip, // Tambahkan prefix apostrophe untuk memaksa sebagai text
            "'" . $anggota->nrp, // Tambahkan prefix apostrophe untuk NRP juga
            $anggota->email,
            $anggota->phone ? "'" . $anggota->phone : '-', // Telepon juga diberi prefix jika ada
            $anggota->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi',
            $anggota->created_at->format('d/m/Y'),
            $anggota->peminjaman_aktif_count,
            $anggota->peminjaman_selesai_count
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT, // NIP sebagai teks
            'D' => NumberFormat::FORMAT_TEXT, // NRP sebagai teks juga untuk konsistensi
            'F' => NumberFormat::FORMAT_TEXT, // Telepon sebagai teks
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header row styling
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => '4F81BD']
                ]
            ],
            // Border untuk semua cell
            'A1:J' . ($this->anggota->count() + 1) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,     // ID
            'B' => 25,    // Nama
            'C' => 20,    // NIP (cukup panjang untuk ASN)
            'D' => 20,    // NRP
            'E' => 30,    // Email
            'F' => 18,    // Telepon
            'G' => 20,    // Status Verifikasi
            'H' => 15,    // Tanggal Daftar
            'I' => 18,    // Peminjaman Aktif
            'J' => 20,    // Peminjaman Selesai
        ];
    }
}