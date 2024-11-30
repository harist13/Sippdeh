<?php

namespace App\Exports\Admin;

use App\Models\Calon;
use App\Models\ResumeSuaraPilbupKecamatan;
use App\Models\ResumeSuaraPilbupKelurahan;
use App\Models\ResumeSuaraPilbupTPS;
use App\Traits\SortResumeColumns;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;

class ResumePilbupWilayahExport implements FromView, WithStyles
{
    use SortResumeColumns;

    public int $kabupatenId;

    public string $posisi = 'BUPATI';

    public string $keyword = '';

    public array $selectedKecamatan = [];
    public array $selectedKelurahan = [];
    public array $includedColumns = ['KABUPATEN/KOTA', 'KECAMATAN', 'KELURAHAN', 'CALON', 'TPS'];
    public array $partisipasi = ['HIJAU', 'MERAH'];
    
    public function __construct(
        $kabupatenId,

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
        $this->kabupatenId = $kabupatenId;

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

        if (in_array('TPS', $includedColumns)) {
            $tps = $this->getSuaraPerTps();
            return view('exports.input-suara.table', compact('tps', 'paslon', 'includedColumns'));
        }
        
        if (!empty($this->selectedKelurahan)) {
            $suara = $this->getSuaraPerKelurahan();
            return view('exports.resume.pilbup.kelurahan-table', compact('suara', 'paslon', 'includedColumns'));
        }
        
        $suara = $this->getSuaraPerKecamatan();
        return view('exports.resume.pilbup.kecamatan-table', compact('suara', 'paslon', 'includedColumns'));
    }

    private function getSuaraPerTps()
    {
        $builder = ResumeSuaraPilbupTPS::query()
            ->selectRaw('
                resume_suara_pilbup_tps.id,
                resume_suara_pilbup_tps.nama,
                resume_suara_pilbup_tps.dpt,
                resume_suara_pilbup_tps.kotak_kosong,
                resume_suara_pilbup_tps.suara_sah,
                resume_suara_pilbup_tps.suara_tidak_sah,
                resume_suara_pilbup_tps.suara_masuk,
                resume_suara_pilbup_tps.abstain,
                resume_suara_pilbup_tps.partisipasi
            ');

        $builder->whereHas('tps', function(Builder $builder) {
            $builder->whereHas('kelurahan', function (Builder $builder) {
                if (!empty($this->selectedKelurahan)) {
                    $builder->whereIn('id', $this->selectedKelurahan);
                }

                $builder->whereHas('kecamatan', function(Builder $builder) {
                    if (!empty($this->selectedKecamatan)) {
                        $builder->whereIn('id', $this->selectedKecamatan);
                    }

                    $builder->whereHas('kabupaten', fn (Builder $builder) => $builder->whereId($this->kabupatenId));
                });
            });
        });

        if ($this->keyword) {
            $builder->whereHas('tps', function(Builder $builder) {
                $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
                
                $builder->orWhereHas('kelurahan', function (Builder $builder) {
                    $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
                });
    
                $builder->orWhereHas('kelurahan', function (Builder $builder) {
                    $builder->whereHas('kecamatan', function (Builder $builder) {
                        $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
                    });
                });
            });
        }

        $this->addPartisipasiFilter($builder);
        $this->sortResumeSuaraPilbupTpsPaslon($builder);
        $this->sortColumns($builder);
        $this->sortResumeSuaraKotakKosong($builder);

        return $builder->get();
    }

    private function getSuaraPerKelurahan()
    {
        $builder = ResumeSuaraPilbupKelurahan::query()
            ->selectRaw('
                resume_suara_pilbup_kelurahan.id,
                resume_suara_pilbup_kelurahan.nama,
                resume_suara_pilbup_kelurahan.kecamatan_id,
                resume_suara_pilbup_kelurahan.dpt,
                resume_suara_pilbup_kelurahan.kotak_kosong,
                resume_suara_pilbup_kelurahan.suara_sah,
                resume_suara_pilbup_kelurahan.suara_tidak_sah,
                resume_suara_pilbup_kelurahan.suara_masuk,
                resume_suara_pilbup_kelurahan.abstain,
                resume_suara_pilbup_kelurahan.partisipasi
            ');

        $builder->whereIn('resume_suara_pilbup_kelurahan.id', $this->selectedKelurahan);

        if ($this->keyword) {
            $builder->where(function(Builder $builder) {
                $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
                
                $builder->orWhereHas('kecamatan', function (Builder $builder) {
                    $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
                });
            });
        }

        $this->addPartisipasiFilter($builder);
        $this->sortColumns($builder);
        $this->sortResumeSuaraPilbupKelurahanPaslon($builder);
        $this->sortResumeSuaraKotakKosong($builder);

        return $builder->get();
    }

    private function getSuaraPerKecamatan()
    {
        $builder = ResumeSuaraPilbupKecamatan::query()
            ->selectRaw('
                resume_suara_pilbup_kecamatan.id,
                resume_suara_pilbup_kecamatan.nama,
                resume_suara_pilbup_kecamatan.kabupaten_id,
                resume_suara_pilbup_kecamatan.dpt,
                resume_suara_pilbup_kecamatan.dptb,
                resume_suara_pilbup_kecamatan.dpk,
                resume_suara_pilbup_kecamatan.kotak_kosong,
                resume_suara_pilbup_kecamatan.suara_sah,
                resume_suara_pilbup_kecamatan.suara_tidak_sah,
                resume_suara_pilbup_kecamatan.suara_masuk,
                resume_suara_pilbup_kecamatan.abstain,
                resume_suara_pilbup_kecamatan.partisipasi
            ');

        $builder->whereIn('resume_suara_pilbup_kecamatan.id', $this->selectedKecamatan);

        if ($this->keyword) {
            $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
        }

        $this->addPartisipasiFilter($builder);
        $this->sortColumns($builder);
        $this->sortResumeSuaraPilbupKecamatanPaslon($builder);
        $this->sortResumeSuaraKotakKosong($builder);

        return $builder->get();
    }

    private function addPartisipasiFilter(Builder $builder)
    {
        $builder->where(function (Builder $builder) {
            if (in_array('MERAH', $this->partisipasi)) {
                $builder->orWhereRaw('partisipasi < 77.5');
            }
            
            if (in_array('HIJAU', $this->partisipasi)) {
                $builder->orWhereRaw('partisipasi >= 77.5');
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
            ->where('calon.kabupaten_id', $this->kabupatenId)
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