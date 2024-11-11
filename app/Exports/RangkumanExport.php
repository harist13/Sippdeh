<?php

namespace App\Exports;

use App\Models\ResumeSuaraTPS;
use App\Models\Kabupaten;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Calon;

class RangkumanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $paslon;
    protected $filters;
    protected $rowNumber = 0;

    public function __construct(array $filters = [])
    {
        $this->paslon = Calon::where('posisi', 'Gubernur')->get();
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
        ->with(['suara', 'suaraCalon']);

        // Apply filters
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
        // Get kabupaten name for title if filtered
        $title = !empty($this->filters['kabupaten_id']) ? 
            'Resume Pilgub - ' . Kabupaten::find($this->filters['kabupaten_id'])->nama :
            'Resume Pilgub - Semua Kabupaten';

        return [
            [$title],
            [], // Empty row for spacing
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

        // Get suara data for each paslon
        $suaraData = [];
        foreach ($this->paslon as $calon) {
            $suaraCalon = $row->suaraCalon->where('calon_id', $calon->id)->first();
            $suaraData[] = $suaraCalon ? $suaraCalon->suara : 0;
        }

        // Return data directly from database without calculations
        return [
            $this->rowNumber,                    // No
            $row->kabupaten_nama ?? '-',         // Kabupaten/Kota
            $row->kecamatan_nama ?? '-',         // Kecamatan
            $row->kelurahan_nama ?? '-',         // Kelurahan
            $row->suara->dpt ?? 0,               // DPT
            ...$suaraData,                       // Suara untuk setiap paslon
            $row->abstain ?? 0,  // Abstain dari database
            number_format($row->partisipasi ?? 0, 1)  // Tingkat Partisipasi dari database
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Get the highest column letter
        $highestColumn = $sheet->getHighestColumn();
        
        // Merge cells for title
        $sheet->mergeCells('A1:' . $highestColumn . '1');
        
        // Style for title
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => 'center',
            ],
        ]);

        // Style for data headers (row 3)
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

        // Add borders and center alignment to all data cells
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

        // Set column width for better readability
        foreach (range('A', $highestColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Add some basic styling for data rows
        $contentRange = 'A4:' . $highestColumn . $sheet->getHighestRow();
        $sheet->getStyle($contentRange)->applyFromArray([
            'font' => [
                'size' => 11,
            ],
        ]);

        return $sheet;
    }
}