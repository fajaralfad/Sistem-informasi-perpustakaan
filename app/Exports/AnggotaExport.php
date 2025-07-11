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
            $anggota->nip,
            $anggota->nrp,
            $anggota->email,
            $anggota->phone ?? '-',
            $anggota->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi',
            $anggota->created_at->format('d/m/Y'),
            $anggota->peminjaman_aktif_count,
            $anggota->peminjaman_selesai_count
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the header row
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
            
            // Set auto-size for columns
            'A:H' => [
                'alignment' => [
                    'wrapText' => true,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
                ]
            ],
            
            // Set border for all cells
            'A1:H' . ($this->anggota->count() + 1) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ]
        ];
    }
}