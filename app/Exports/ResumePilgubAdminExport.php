<?php

namespace App\Exports;

use App\Models\Calon;
use App\Models\ResumeSuaraPilgubKabupaten;
use App\Models\ResumeSuaraPilgubKecamatan;
use App\Models\ResumeSuaraPilgubKelurahan;
use App\Models\ResumeSuaraPilgubTPS;
use App\Traits\SortResumeColumns;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;

class ResumePilgubAdminExport implements FromView, WithStyles
{
    use SortResumeColumns;

    public string $posisi = 'GUBERNUR';

    public string $keyword = '';

    public array $selectedKabupaten = [];
    public array $selectedKecamatan = [];
    public array $selectedKelurahan = [];
    public array $includedColumns = ['KABUPATEN/KOTA', 'KECAMATAN', 'KELURAHAN', 'CALON'];
    public array $partisipasi = ['HIJAU', 'MERAH'];
    
    public function __construct(
        $keyword,
        $selectedKabupaten,
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
        $this->selectedKabupaten = $selectedKabupaten;
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
            return view('exports.resume.pilgub.kelurahan-table', compact('suara', 'paslon', 'includedColumns'));
        }

        if (!empty($this->selectedKecamatan)) {
            $suara = $this->getSuaraPerKecamatan();
            return view('exports.resume.pilgub.kecamatan-table', compact('suara', 'paslon', 'includedColumns'));
        }
        
        $suara = $this->getSuaraPerKabupaten();
        return view('exports.resume.pilgub.kabupaten-table', compact('suara', 'paslon', 'includedColumns'));
    }

    private function getSuaraPerTps()
    {
        $builder = ResumeSuaraPilgubTPS::query()
            ->selectRaw('
                resume_suara_pilgub_tps.id,
                resume_suara_pilgub_tps.nama,
                resume_suara_pilgub_tps.dpt,
                resume_suara_pilgub_tps.kotak_kosong,
                resume_suara_pilgub_tps.suara_sah,
                resume_suara_pilgub_tps.suara_tidak_sah,
                resume_suara_pilgub_tps.suara_masuk,
                resume_suara_pilgub_tps.abstain,
                resume_suara_pilgub_tps.partisipasi
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

                    $builder->whereHas('kabupaten', fn (Builder $builder) => $builder->whereId(session('Admin_kabupaten_id')));
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
        $this->sortResumeSuaraPilgubTpsPaslon($builder);
        $this->sortColumns($builder);
        $this->sortResumeSuaraKotakKosong($builder);

        return $builder->get();
    }
    
    private function getSuaraPerKelurahan()
    {
        $builder = ResumeSuaraPilgubKelurahan::query()
            ->selectRaw('
                resume_suara_pilgub_kelurahan.id,
                resume_suara_pilgub_kelurahan.nama,
                resume_suara_pilgub_kelurahan.kecamatan_id,
                resume_suara_pilgub_kelurahan.dpt,
                resume_suara_pilgub_kelurahan.kotak_kosong,
                resume_suara_pilgub_kelurahan.suara_sah,
                resume_suara_pilgub_kelurahan.suara_tidak_sah,
                resume_suara_pilgub_kelurahan.suara_masuk,
                resume_suara_pilgub_kelurahan.abstain,
                resume_suara_pilgub_kelurahan.partisipasi
            ');

        $builder->whereIn('resume_suara_pilgub_kelurahan.id', $this->selectedKelurahan);

        if ($this->keyword) {
            $builder->where(function(Builder $builder) {
                $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
                
                $builder->orWhereHas('kecamatan', function (Builder $builder) {
                    $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
                });

                $builder->orWhereHas('kecamatan.kabupaten', function (Builder $builder) {
                    $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
                });
            });
        }

        $this->addPartisipasiFilter($builder);
        $this->sortColumns($builder);
        $this->sortResumeSuaraPilgubKelurahanPaslon($builder);
        $this->sortResumeSuaraKotakKosong($builder);

        if ($this->keyword) {
            $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
        }

        return $builder->get();
    }

    private function getSuaraPerKecamatan()
    {
        $builder = ResumeSuaraPilgubKecamatan::query()
            ->selectRaw('
                resume_suara_pilgub_kecamatan.id,
                resume_suara_pilgub_kecamatan.nama,
                resume_suara_pilgub_kecamatan.kabupaten_id,
                resume_suara_pilgub_kecamatan.dpt,
                resume_suara_pilgub_kecamatan.dptb,
                resume_suara_pilgub_kecamatan.dpk,
                resume_suara_pilgub_kecamatan.kotak_kosong,
                resume_suara_pilgub_kecamatan.suara_sah,
                resume_suara_pilgub_kecamatan.suara_tidak_sah,
                resume_suara_pilgub_kecamatan.suara_masuk,
                resume_suara_pilgub_kecamatan.abstain,
                resume_suara_pilgub_kecamatan.partisipasi
            ');

        $builder->whereIn('resume_suara_pilgub_kecamatan.id', $this->selectedKecamatan);

        if ($this->keyword) {
            $builder->where(function(Builder $builder) {
                $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
                
                $builder->orWhereHas('kabupaten', function (Builder $builder) {
                    $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
                });
            });
        }

        $this->addPartisipasiFilter($builder);
        $this->sortColumns($builder);
        $this->sortResumeSuaraPilgubKecamatanPaslon($builder);
        $this->sortResumeSuaraKotakKosong($builder);

        return $builder->get();
    }

    private function getSuaraPerKabupaten()
    {
        $builder = ResumeSuaraPilgubKabupaten::query()
            ->selectRaw('
                resume_suara_pilgub_kabupaten.id,
                resume_suara_pilgub_kabupaten.nama,
                resume_suara_pilgub_kabupaten.provinsi_id,
                resume_suara_pilgub_kabupaten.dpt,
                resume_suara_pilgub_kabupaten.dptb,
                resume_suara_pilgub_kabupaten.dpk,
                resume_suara_pilgub_kabupaten.kotak_kosong,
                resume_suara_pilgub_kabupaten.suara_sah,
                resume_suara_pilgub_kabupaten.suara_tidak_sah,
                resume_suara_pilgub_kabupaten.suara_masuk,
                resume_suara_pilgub_kabupaten.abstain,
                resume_suara_pilgub_kabupaten.partisipasi
            ');
        
        $builder->whereIn('resume_suara_pilgub_kabupaten.id', $this->selectedKabupaten);

        $this->addPartisipasiFilter($builder);
        $this->sortColumns($builder);
        $this->sortResumeSuaraPilgubKabupatenPaslon($builder);
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
                $builder->orWhereRaw('partisipasi < 77.5');
            }
            
            if (in_array('HIJAU', $this->partisipasi)) {
                $builder->orWhereRaw('partisipasi >= 77.5');
            }
        });
    }

    private function getPaslon()
    {
        return Calon::select([
            'calon.id',
            'calon.nama',
            'calon.nama_wakil',
            'calon.foto', 
            'calon.provinsi_id',
            'calon.kabupaten_id',
            DB::raw('(
                SELECT COALESCE(SUM(sc.suara), 0)
                FROM suara_calon sc
                WHERE sc.calon_id = calon.id
            ) + (
                SELECT COALESCE(SUM(scdp.suara), 0) 
                FROM suara_calon_daftar_pemilih scdp
                WHERE scdp.calon_id = calon.id
            ) AS suara')
        ])
        ->where('calon.posisi', $this->posisi)
        ->where('calon.provinsi_id', session('Admin_provinsi_id'))
        ->groupBy('calon.id')
        ->get();
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
}
