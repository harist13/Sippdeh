<?php

namespace App\Exports;

use App\Models\ResumeSuaraTPS;
use App\Models\Kabupaten;
use App\Models\Calon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BupatiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $paslon;
    protected $filters;
    protected $rowNumber = 0;

    public function __construct(array $filters = [])
    {
        $this->paslon = Calon::where('posisi', 'Bupati')->get();
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = ResumeSuaraTPS::select(
            'resume_suara_tps.*',
            'tps.nama as tps_nama',
            'tps.kelurahan_id',
            'kelurahan.nama as kelurahan_nama',
            'kelurahan.kecamatan_id',
            'kecamatan.nama as kecamatan_nama',
            'kecamatan.kabupaten_id',
            'kabupaten.nama as kabupaten_nama'
        )
        ->join('tps', 'resume_suara_tps.id', '=', 'tps.id')
        ->join('kelurahan', 'tps.kelurahan_id', '=', 'kelurahan.id')
        ->join('kecamatan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
        ->join('kabupaten', 'kecamatan.kabupaten_id', '=', 'kabupaten.id')
        ->whereHas('suaraCalon.calon', function($query) {
            $query->where('posisi', 'Bupati');
        })
        ->with(['suara', 'suaraCalon' => function($query) {
            $query->whereHas('calon', function($q) {
                $q->where('posisi', 'Bupati');
            });
        }]);

        if (!empty($this->filters['kabupaten_id'])) {
            $query->where('kabupaten.id', $this->filters['kabupaten_id']);
        }

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('kabupaten.nama', 'LIKE', "%{$search}%")
                  ->orWhere('kecamatan.nama', 'LIKE', "%{$search}%")
                  ->orWhere('kelurahan.nama', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($this->filters['partisipasi'])) {
            $query->where(function($q) {
                foreach ($this->filters['partisipasi'] as $value) {
                    switch ($value) {
                        case 'hijau':
                            $q->orWhere('partisipasi', '>=', 70);
                            break;
                        case 'kuning':
                            $q->orWhereBetween('partisipasi', [50, 69.99]);
                            break;
                        case 'merah':
                            $q->orWhere('partisipasi', '<', 50);
                            break;
                    }
                }
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        $title = !empty($this->filters['kabupaten_id']) ? 
            'Resume Bupati - ' . Kabupaten::find($this->filters['kabupaten_id'])->nama :
            'Resume Bupati - Semua Kabupaten';

        return [
            [$title],
            [],
            [
                'No',
                'Kabupaten/Kota',
                'Kecamatan',
                'Kelurahan',
                'DPT',
                ...$this->paslon->map(fn($calon) => $calon->nama . '/' . $calon->nama_wakil),
                'Abstain',
                'Tingkat Partisipasi (%)'
            ]
        ];
    }

    public function map($row): array
    {
        $this->rowNumber++;

        $suaraData = [];
        foreach ($this->paslon as $calon) {
            $suaraCalon = $row->suaraCalon->where('calon_id', $calon->id)->first();
            $suaraData[] = $suaraCalon ? $suaraCalon->suara : 0;
        }

        return [
            $this->rowNumber,
            $row->kabupaten_nama ?? '-',
            $row->kecamatan_nama ?? '-',
            $row->kelurahan_nama ?? '-',
            $row->suara->dpt ?? 0,
            ...$suaraData,
            $row->abstain ?? 0,
            number_format($row->partisipasi ?? 0, 1)
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestColumn = $sheet->getHighestColumn();
        
        // Style for title
        $sheet->mergeCells('A1:' . $highestColumn . '1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => 'center',
            ],
        ]);

        // Style for headers
        $sheet->getStyle('A3:' . $highestColumn . '3')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '3560A0'],
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
        ]);

        // Add borders to all cells
        $dataRange = 'A3:' . $highestColumn . $sheet->getHighestRow();
        $sheet->getStyle($dataRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
        ]);

        // Auto-size columns
        foreach (range('A', $highestColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return $sheet;
    }
}