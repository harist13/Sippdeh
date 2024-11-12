<?php

namespace App\Exports;

use App\Models\Calon;
use App\Models\ResumeSuaraPilgubKabupaten;
use App\Models\ResumeSuaraPilgubKecamatan;
use App\Models\ResumeSuaraPilgubKelurahan;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;

class ResumePilgubExport implements FromView, WithStyles
{
    public string $keyword = '';

    public array $selectedKabupaten = [];
    public array $selectedKecamatan = [];
    public array $selectedKelurahan = [];
    public array $includedColumns = ['KABUPATEN', 'KECAMATAN', 'KELURAHAN', 'CALON'];
    public array $partisipasi = ['HIJAU', 'KUNING', 'MERAH'];
    
    public function __construct($keyword, $selectedKabupaten, $selectedKecamatan, $selectedKelurahan, $includedColumns, $partisipasi)
    {
        $this->keyword = $keyword;
        $this->selectedKabupaten = $selectedKabupaten;
        $this->selectedKecamatan = $selectedKecamatan;
        $this->selectedKelurahan = $selectedKelurahan;
        $this->includedColumns = $includedColumns;
        $this->partisipasi = $partisipasi;
    }

    /**
    * @return \Illuminate\Support\View
    */
    public function view(): View
    {
        $includedColumns = $this->includedColumns;
        $paslon = $this->getCalon();

        
        if (!empty($this->selectedKelurahan)) {
            $suara = $this->getSuaraPerKelurahan();
            return view('exports.resume.pilgub.kelurahan-table', compact('suara', 'paslon', 'includedColumns'));
        }

        if (!empty($this->selectedKecamatan)) {
            $suara = $this->getSuaraPerKecamatan();
            return view('exports.resume.pilgub.kecamatan-table', compact('suara', 'paslon', 'includedColumns'));
        }
        
        $suara = $this->getSuaraPerKabupaten();
        return view('exports.resume.pilgub.kabupaten-table', compact('suara', 'paslon', 'includedColumns'));
    }

    private function getSuaraPerKelurahan()
    {
        $builder = ResumeSuaraPilgubKelurahan::whereIn('id', $this->selectedKelurahan);

        $this->addPartisipasiFilter($builder);

        if ($this->keyword) {
            $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
        }

        return $builder->get();
    }

    private function getSuaraPerKecamatan()
    {
        $builder = ResumeSuaraPilgubKecamatan::whereIn('id', $this->selectedKecamatan);

        $this->addPartisipasiFilter($builder);

        if ($this->keyword) {
            $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
        }

        return $builder->get();
    }

    private function getSuaraPerKabupaten()
    {
        $builder = ResumeSuaraPilgubKabupaten::whereIn('id', $this->selectedKabupaten);

        $this->addPartisipasiFilter($builder);

        if ($this->keyword) {
            $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
        }

        return $builder->get();
    }

    private function addPartisipasiFilter(Builder $builder)
    {
        $builder->where(function (Builder $builder) {
            if (in_array('MERAH', $this->partisipasi)) {
                $builder->orWhereRaw('partisipasi BETWEEN 0 AND 59.9');
            }
        
            if (in_array('KUNING', $this->partisipasi)) {
                $builder->orWhereRaw('partisipasi BETWEEN 60 AND 79.9');
            }
            
            if (in_array('HIJAU', $this->partisipasi)) {
                $builder->orWhereRaw('partisipasi BETWEEN 80 AND 100');
            }
        });
    }

    private function getCalon()
    {
        $builder = Calon::wherePosisi('GUBERNUR');
        $builder->whereHas('provinsi', function (Builder $builder) {
            $builder->whereHas('kabupaten', fn (Builder $builder) => $builder->whereNama(session('user_wilayah')));
        });

        return $builder->get();
    }

    public function styles(Worksheet $sheet)
    {
        $styleArray = [
            'borders' => [
                'bottom' => ['borderStyle' => 'thin', 'color' => ['argb' => '00000000']],
                'top' => ['borderStyle' => 'thin', 'color' => ['argb' => '00000000']],
                'right' => ['borderStyle' => 'thin', 'color' => ['argb' => '00000000']],
                'left' => ['borderStyle' => 'thin', 'color' => ['argb' => '00000000']],
            ],
        ];

        $index = 1;
    }

    // private function setKabupatenBorder(Worksheet $sheet, int $index, array $styleArray): void
    // {
    //     $namaKabupaten = $sheet->getCell('A1');
    //     $namaKabupaten->getStyle()->getFont()->setBold(true);
    // }
}
