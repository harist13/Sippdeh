<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VoteKabupatenExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'NO',
            'KAB/KOTA',
            'DPT',
            'SUARA SAH',
            'SUARA TIDAK SAH',
            'ABSTAIN',
            'SUARA MASUK',
            'PARTISIPASI'
        ];
    }

    public function map($data): array
    {
        static $counter = 0;
        $counter++;
        
        return [
            str_pad($counter, 2, '0', STR_PAD_LEFT),
            $data->kabupaten_nama,
            number_format($data->total_dpt),
            number_format($data->total_suara_sah),
            number_format($data->total_suara_tidak_sah),
            number_format($data->total_abstain),
            number_format($data->total_suara_masuk),
            $data->partisipasi . '%'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Header row
            'A1:H1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '3560A0']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
        ];
    }
}