<?php

namespace App\Exports;

use App\Models\Kunjungan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

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
            'Durasi (jam:menit)',
            'Tujuan',
            'Kegiatan',
            'Status',
        ];
    }

    public function map($kunjungan): array
    {
        $durationMinutes = null;
        $durationFormatted = '-';
        
        if ($kunjungan->waktu_keluar) {
            // Handle different possible duration formats
            if (is_int($kunjungan->durasi)) {
                $durationMinutes = $kunjungan->durasi;
            } elseif (is_object($kunjungan->durasi) && method_exists($kunjungan->durasi, 'totalMinutes')) {
                // Handle CarbonInterval case
                $durationMinutes = $kunjungan->durasi->totalMinutes;
            } else {
                // Calculate from scratch if needed
                $waktuMasuk = Carbon::parse($kunjungan->waktu_masuk);
                $waktuKeluar = Carbon::parse($kunjungan->waktu_keluar);
                $durationMinutes = $waktuMasuk->diffInMinutes($waktuKeluar);
            }
            
            $durationFormatted = $this->formatDuration($durationMinutes);
        }

        return [
            $kunjungan->id,
            $kunjungan->user->name,
            $kunjungan->user->email,
            $kunjungan->waktu_masuk->format('d/m/Y H:i'),
            $kunjungan->waktu_keluar ? $kunjungan->waktu_keluar->format('d/m/Y H:i') : '-',
            $kunjungan->waktu_keluar ? $durationMinutes : '-',
            $durationFormatted,
            $kunjungan->tujuan_formatted ?? Kunjungan::listTujuan()[$kunjungan->tujuan] ?? $kunjungan->tujuan,
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
            'A:J' => [
                'alignment' => ['wrapText' => true],
                'font' => ['size' => 11]
            ],
            
            // Auto-size columns
            'A' => ['width' => 12],
            'B' => ['width' => 25],
            'C' => ['width' => 25],
            'D' => ['width' => 18],
            'E' => ['width' => 18],
            'F' => ['width' => 15],
            'G' => ['width' => 15],
            'H' => ['width' => 20],
            'I' => ['width' => 30],
            'J' => ['width' => 12],
        ];
    }

    protected function formatDuration($minutes)
    {
        // Ensure we have an integer value
        $minutes = (int)$minutes;
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        return sprintf('%02d:%02d', $hours, $remainingMinutes);
    }
}