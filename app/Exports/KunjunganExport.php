<?php

namespace App\Exports;

use App\Models\Kunjungan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KunjunganExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $kunjungans;

    public function __construct($kunjungans)
    {
        $this->kunjungans = $kunjungans;
    }

    public function collection()
    {
        return $this->kunjungans;
    }

    public function headings(): array
    {
        return [
            'ID Kunjungan',
            'Nama Anggota',
            'Email',
            'Waktu Masuk',
            'Waktu Keluar',
            'Durasi (menit)',
            'Tujuan',
            'Kegiatan',
            'Status',
        ];
    }

    public function map($kunjungan): array
    {
        return [
            $kunjungan->id,
            $kunjungan->user->name,
            $kunjungan->user->email,
            $kunjungan->waktu_masuk->format('d/m/Y H:i'),
            $kunjungan->waktu_keluar ? $kunjungan->waktu_keluar->format('d/m/Y H:i') : '-',
            $kunjungan->durasi,
            $kunjungan->tujuan_formatted,
            $kunjungan->kegiatan ?? '-',
            $kunjungan->waktu_keluar ? 'Selesai' : 'Aktif',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
            
            // Styling specific cells
            'A:I' => ['alignment' => ['wrapText' => true]],
        ];
    }
}