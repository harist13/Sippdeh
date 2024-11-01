<?php

namespace App\Exports;

use App\Models\RingkasanSuaraTPS;
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
    protected $request;

    public function __construct($request)
    {
        $this->paslon = Calon::where('posisi', 'Gubernur')->get();
        $this->request = $request;
    }

    public function collection()
    {
        $query = RingkasanSuaraTPS::select(
            'ringkasan_suara_tps.*',
            'tps.nama as tps_nama',
            'tps.kelurahan_id',
            'kelurahan.nama as kelurahan_nama',
            'kelurahan.kecamatan_id',
            'kecamatan.nama as kecamatan_nama',
            'kecamatan.kabupaten_id',
            'kabupaten.nama as kabupaten_nama'
        )
        ->join('tps', 'ringkasan_suara_tps.id', '=', 'tps.id')
        ->join('kelurahan', 'tps.kelurahan_id', '=', 'kelurahan.id')
        ->join('kecamatan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
        ->join('kabupaten', 'kecamatan.kabupaten_id', '=', 'kabupaten.id')
        ->with(['suara', 'suaraCalon']);

        // Apply search filter if exists
        if ($this->request->has('search') && !empty($this->request->search)) {
            $search = $this->request->search;
            $query->where(function($q) use ($search) {
                $q->where('kabupaten.nama', 'like', "%{$search}%")
                  ->orWhere('kecamatan.nama', 'like', "%{$search}%")
                  ->orWhere('kelurahan.nama', 'like', "%{$search}%");
            });
        }

        // Apply kabupaten filter if exists
        if ($this->request->has('kabupaten') && !empty($this->request->kabupaten)) {
            $query->where('kabupaten.nama', $this->request->kabupaten);
        }

        return $query->get();
    }

    public function headings(): array
    {
        $headers = [
            'No',
            'Kabupaten/Kota',
            'Kecamatan',
            'Kelurahan',
            'DPT',
        ];

        // Add paslon names to headers
        foreach ($this->paslon as $calon) {
            $headers[] = $calon->nama . '/' . $calon->nama_wakil;
        }

        // Add remaining headers
        $headers = array_merge($headers, [
            'Abstain',
            'Tingkat Partisipasi (%)'
        ]);

        return $headers;
    }

    public function map($row): array
    {
        $suaraData = [];
        foreach ($this->paslon as $calon) {
            $suaraCalon = $row->suaraCalon->where('calon_id', $calon->id)->first();
            $suaraData[] = $suaraCalon ? $suaraCalon->suara : 0;
        }

        return [
            $row->getThreeDigitsId(),
            $row->kabupaten_nama,
            $row->kecamatan_nama,
            $row->kelurahan_nama,
            $row->suara->dpt ?? 0,
            ...$suaraData,
            $row->jumlah_pengguna_tidak_pilih ?? 0,
            number_format($row->partisipasi ?? 0, 1)
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style for header
        $sheet->getStyle('1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '3560A0'],
            ],
        ]);

        // Get the last row number
        $lastRow = $sheet->getHighestRow();

        // Add borders to all cells
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // Center align all cells
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . $lastRow)->getAlignment()->setHorizontal('center');

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}