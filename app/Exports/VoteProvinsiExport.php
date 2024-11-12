<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VoteProvinsiExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
            'PROVINSI',
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
        
        $partisipasi = $data->total_dpt > 0 
            ? round(($data->total_suara_masuk / $data->total_dpt) * 100, 1) 
            : 0;

        return [
            $counter,
            $data->provinsi_nama,
            number_format($data->total_dpt),
            number_format($data->total_suara_sah),
            number_format($data->total_suara_tidak_sah),
            number_format($data->total_abstain),
            number_format($data->total_suara_masuk),
            $partisipasi . '%'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
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
