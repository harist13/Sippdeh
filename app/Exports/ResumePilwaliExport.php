<?php

namespace App\Exports;

use App\Models\Calon;
use App\Models\ResumeSuaraPilwaliKabupaten;
use App\Models\ResumeSuaraPilwaliKecamatan;
use App\Models\ResumeSuaraPilwaliKelurahan;
use App\Traits\SortResumeColumns;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;

class ResumePilwaliExport implements FromView, WithStyles
{
    use SortResumeColumns;

    public string $posisi = 'WALIKOTA';

    public string $keyword = '';

    public array $selectedKecamatan = [];
    public array $selectedKelurahan = [];
    public array $includedColumns = ['KECAMATAN', 'KELURAHAN', 'CALON'];
    public array $partisipasi = ['HIJAU', 'KUNING', 'MERAH'];
    
    public function __construct(
        $keyword,
        $selectedKecamatan,
        $selectedKelurahan,
        $includedColumns,
        $partisipasi,

        $dptSort,
        $suaraSahSort,
        $suaraTidakSahSort,
        $suaraMasukSort,
        $abstainSort,
        $partisipasiSort,

        $paslonIdSort,
        $paslonSort
    )
    {
        $this->keyword = $keyword;
        $this->selectedKecamatan = $selectedKecamatan;
        $this->selectedKelurahan = $selectedKelurahan;
        $this->includedColumns = $includedColumns;
        $this->partisipasi = $partisipasi;

        $this->dptSort = $dptSort;
        $this->suaraSahSort = $suaraSahSort;
        $this->suaraTidakSahSort = $suaraTidakSahSort;
        $this->suaraMasukSort = $suaraMasukSort;
        $this->abstainSort = $abstainSort;
        $this->partisipasiSort = $partisipasiSort;

        $this->paslonIdSort = $paslonIdSort;
        $this->paslonSort = $paslonSort;
    }

    /**
    * @return \Illuminate\Support\View
    */
    public function view(): View
    {
        $includedColumns = $this->includedColumns;
        $paslon = $this->getPaslon();
        
        if (!empty($this->selectedKelurahan)) {
            $suara = $this->getSuaraPerKelurahan();
            return view('exports.resume.pilwali.kelurahan-table', compact('suara', 'paslon', 'includedColumns'));
        }
        
        $suara = $this->getSuaraPerKecamatan();
        return view('exports.resume.pilwali.kecamatan-table', compact('suara', 'paslon', 'includedColumns'));
    }

    private function getSuaraPerKelurahan()
    {
        $builder = ResumeSuaraPilwaliKelurahan::query()
            ->selectRaw('
                resume_suara_pilwali_kelurahan.id,
                resume_suara_pilwali_kelurahan.nama,
                resume_suara_pilwali_kelurahan.kecamatan_id,
                resume_suara_pilwali_kelurahan.dpt,
                resume_suara_pilwali_kelurahan.kotak_kosong,
                resume_suara_pilwali_kelurahan.suara_sah,
                resume_suara_pilwali_kelurahan.suara_tidak_sah,
                resume_suara_pilwali_kelurahan.suara_masuk,
                resume_suara_pilwali_kelurahan.abstain,
                resume_suara_pilwali_kelurahan.partisipasi
            ')
            ->whereIn('resume_suara_pilwali_kelurahan.id', $this->selectedKelurahan);

        $this->addPartisipasiFilter($builder);
        $this->sortColumns($builder);
        $this->sortResumeSuaraPilwaliKelurahanPaslon($builder);
        $this->sortResumeSuaraKotakKosong($builder);

        if ($this->keyword) {
            $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
        }

        return $builder->get();
    }

    private function getSuaraPerKecamatan()
    {
        $builder = ResumeSuaraPilwaliKecamatan::query()
            ->selectRaw('
                resume_suara_pilwali_kecamatan.id,
                resume_suara_pilwali_kecamatan.nama,
                resume_suara_pilwali_kecamatan.kabupaten_id,
                resume_suara_pilwali_kecamatan.dpt,
                resume_suara_pilwali_kecamatan.kotak_kosong,
                resume_suara_pilwali_kecamatan.suara_sah,
                resume_suara_pilwali_kecamatan.suara_tidak_sah,
                resume_suara_pilwali_kecamatan.suara_masuk,
                resume_suara_pilwali_kecamatan.abstain,
                resume_suara_pilwali_kecamatan.partisipasi
            ')
            ->whereIn('resume_suara_pilwali_kecamatan.id', $this->selectedKecamatan);

        $this->addPartisipasiFilter($builder);
        $this->sortColumns($builder);
        $this->sortResumeSuaraPilwaliKecamatanPaslon($builder);
        $this->sortResumeSuaraKotakKosong($builder);

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
                $builder->orWhereRaw('partisipasi >= 80');
            }
        });
    }

    private function getPaslon(): Collection
    {
        $builder = Calon::select([
            'calon.id',
            'calon.nama',
            'calon.nama_wakil',
            'calon.foto',
            'calon.kabupaten_id',
            DB::raw('COALESCE(SUM(suara_calon.suara), 0) AS suara'),
        ])
            ->leftJoin('suara_calon', 'suara_calon.calon_id', '=', 'calon.id')
            ->where('calon.posisi', $this->posisi)
            ->where('calon.kabupaten_id', session('operator_kabupaten_id'))
            ->groupBy('calon.id');

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
