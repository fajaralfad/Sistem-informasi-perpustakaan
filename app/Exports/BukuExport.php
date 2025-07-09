<?php

namespace App\Exports;

use App\Models\Buku;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BukuExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $buku;

    public function __construct($buku)
    {
        $this->buku = $buku;
    }

    public function collection()
    {
        return $this->buku;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Judul',
            'Kategori',
            'Pengarang',
            'ISBN',
            'Tahun Terbit',
            'Stok',
            'Total Copy',
            'Tanggal Dibuat',
        ];
    }

    public function map($buku): array
    {
        return [
            $buku->id,
            $buku->judul,
            $buku->kategori->nama ?? '-',
            $buku->pengarang->nama ?? '-',
            $buku->isbn_list ?? '-',
            $buku->tahun_terbit,
            $buku->total_stok,
            $buku->total_copy,
            $buku->created_at->format('d/m/Y H:i'),
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