<?php

namespace App\Livewire\Operator\Resume\Pilgub\PerWilayah;

use App\Exports\ResumePilgubExport;
use App\Models\Calon;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\ResumeSuaraPilgubKabupaten;
use App\Models\ResumeSuaraPilgubKecamatan;
use App\Models\ResumeSuaraPilgubKelurahan;
use App\Models\ResumeSuaraPilgubTPS;
use App\Traits\SortResumeColumns;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ResumeSuaraPilgubPerWilayah extends Component
{
    use SortResumeColumns, WithPagination, WithoutUrlPagination;

    public string $posisi = 'GUBERNUR';

    public string $keyword = '';
    public int $perPage = 10;

    public array $selectedKecamatan = [];
    public array $selectedKelurahan = [];

    public array $includedColumns = ['KABUPATEN/KOTA', 'KECAMATAN', 'KELURAHAN', 'CALON', 'TPS'];
    public array $partisipasi = ['HIJAU', 'MERAH'];

    public $paslonSorts = [];

    public function mount()
    {
        $this->fillSelectedKecamatan();
        $this->includedColumns = ['KABUPATEN/KOTA', 'KECAMATAN', 'KELURAHAN', 'CALON', 'TPS'];
    }

    public function render()
    {
        if (in_array('TPS', $this->includedColumns)) {
            return $this->getTpsTable();
        }

        if (!empty($this->selectedKelurahan)) {
            return $this->getKelurahanTable();
        }
        
        return $this->getKecamatanTable();
    }

    public function exportPdf()
{
    try {
        // Get data based on current view and filters
        if (in_array('TPS', $this->includedColumns)) {
            $data = ResumeSuaraPilgubTPS::query()
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
                ')
                ->whereHas('tps', function(Builder $builder) {
                    $builder->whereHas('kelurahan', function (Builder $builder) {
                        if (!empty($this->selectedKelurahan)) {
                            $builder->whereIn('id', $this->selectedKelurahan);
                        }

                        $builder->whereHas('kecamatan', function(Builder $builder) {
                            if (!empty($this->selectedKecamatan)) {
                                $builder->whereIn('id', $this->selectedKecamatan);
                            }

                            $builder->whereHas('kabupaten', function (Builder $builder) {
                                if (!empty($this->selectedKabupaten)) {
                                    $builder->whereIn('id', $this->selectedKabupaten);
                                }
                                
                                $builder->whereHas('provinsi', function(Builder $builder) {
                                    $builder->whereId(session('operator_provinsi_id'));
                                });
                            });
                        });
                    });
                });
        } elseif (!empty($this->selectedKelurahan)) {
            $data = ResumeSuaraPilgubKelurahan::query()
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
                ')
                ->whereIn('id', $this->selectedKelurahan);
        } elseif (!empty($this->selectedKecamatan)) {
            $data = ResumeSuaraPilgubKecamatan::query()
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
                ')
                ->whereIn('id', $this->selectedKecamatan);
        } else {
            $data = ResumeSuaraPilgubKabupaten::query()
                ->selectRaw('
                    resume_suara_pilgub_kabupaten.id,
                    resume_suara_pilgub_kabupaten.nama,
                    resume_suara_pilgub_kabupaten.provinsi_id,
                    resume_suara_pilgub_kabupaten.dpt,
                    resume_suara_pilgub_kabupaten.kotak_kosong,
                    resume_suara_pilgub_kabupaten.suara_sah,
                    resume_suara_pilgub_kabupaten.suara_tidak_sah,
                    resume_suara_pilgub_kabupaten.suara_masuk,
                    resume_suara_pilgub_kabupaten.abstain,
                    resume_suara_pilgub_kabupaten.partisipasi
                ')
                ->whereIn('id', $this->selectedKabupaten);
        }

        // Apply partisipasi filter
        $data->where(function (Builder $builder) {
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

        // Apply keyword search if exists
        if ($this->keyword) {
            $data->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
        }

        // Apply sorting
        if ($this->dptSort) {
            $data->orderBy('dpt', $this->dptSort);
        }
        if ($this->suaraSahSort) {
            $data->orderBy('suara_sah', $this->suaraSahSort);
        }
        if ($this->suaraTidakSahSort) {
            $data->orderBy('suara_tidak_sah', $this->suaraTidakSahSort);
        }
        if ($this->suaraMasukSort) {
            $data->orderBy('suara_masuk', $this->suaraMasukSort);
        }
        if ($this->abstainSort) {
            $data->orderBy('abstain', $this->abstainSort);
        }
        if ($this->partisipasiSort) {
            $data->orderBy('partisipasi', $this->partisipasiSort);
        }

        $finalData = $data->get();

        if ($finalData->isEmpty()) {
            $this->dispatch('showAlert', [
                'type' => 'error',
                'message' => 'Tidak ada data untuk di-export'
            ]);
            return;
        }

        $provinsi = session('operator_provinsi_id');
        $paslon = $this->getPaslon();

        $viewData = [
            'data' => $finalData,
            'provinsi' => $provinsi,
            'paslon' => $paslon,
            'includedColumns' => $this->includedColumns,
            'isPilkadaTunggal' => count($paslon) === 1,
            'isKabupatenView' => empty($this->selectedKecamatan),
            'isKecamatanView' => !empty($this->selectedKecamatan) && empty($this->selectedKelurahan),
            'isKelurahanView' => !empty($this->selectedKelurahan),
            'isTpsView' => in_array('TPS', $this->includedColumns),
            'isProvinsiColumnIgnored' => !in_array('PROVINSI', $this->includedColumns),
            'isKabupatenColumnIgnored' => !in_array('KABUPATEN/KOTA', $this->includedColumns),
            'isKecamatanColumnIgnored' => !in_array('KECAMATAN', $this->includedColumns),
            'isKelurahanColumnIgnored' => !in_array('KELURAHAN', $this->includedColumns),
            'isTPSColumnIgnored' => !in_array('TPS', $this->includedColumns),
            'isCalonColumnIgnored' => !in_array('CALON', $this->includedColumns),
            'keyword' => $this->keyword,
            'partisipasi' => $this->partisipasi,
            'sortInfo' => [
                'dptSort' => $this->dptSort,
                'suaraSahSort' => $this->suaraSahSort, 
                'suaraTidakSahSort' => $this->suaraTidakSahSort,
                'suaraMasukSort' => $this->suaraMasukSort,
                'abstainSort' => $this->abstainSort,
                'partisipasiSort' => $this->partisipasiSort,
                'paslonIdSort' => $this->paslonIdSort,
                'paslonSort' => $this->paslonSort,
            ]
        ];

        $pdf = PDF::loadView('exports.resume-suara-pilgub-pdf', $viewData);
        $pdf->setPaper('A4', 'landscape');

        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, 'resume-suara-pilgub.pdf', [
            'Content-Type' => 'application/pdf',
        ]);

    } catch (\Exception $e) {
        Log::error('PDF Export Error: ' . $e->getMessage());
        $this->dispatch('showAlert', [
            'type' => 'error',
            'message' => 'Gagal mengekspor PDF: ' . $e->getMessage()
        ]);
    }
}

    private function getTpsTable()
    {
        $paslon = $this->getPaslon();
        $suara = $this->getSuaraPerTps();
        return view('operator.resume.pilgub.per-wilayah.livewire', compact('suara', 'paslon'));
    }

    private function getKelurahanTable()
    {
        $paslon = $this->getPaslon();
        $suara = $this->getSuaraPerKelurahan();
        return view('operator.resume.pilgub.per-wilayah.livewire', compact('suara', 'paslon'));
    }

    private function getKecamatanTable()
    {
        $paslon = $this->getPaslon();
        $suara = $this->getSuaraPerKecamatan();
        return view('operator.resume.pilgub.per-wilayah.livewire', compact('suara', 'paslon'));
    }

    // NOTE: Ini gak dipakai
    private function getKabupatenTable()
    {
        $paslon = $this->getPaslon();
        $suara = $this->getSuaraPerKabupaten();
        return view('operator.resume.pilgub.per-wilayah.livewire', compact('suara', 'paslon'));
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

                    $builder->whereHas('kabupaten', fn (Builder $builder) => $builder->whereId(session('operator_kabupaten_id')));
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

        return $builder->paginate($this->perPage);
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
            });
        }

        $this->addPartisipasiFilter($builder);
        $this->sortColumns($builder);
        $this->sortResumeSuaraPilgubKelurahanPaslon($builder);
        $this->sortResumeSuaraKotakKosong($builder);

        return $builder->paginate($this->perPage);
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
            $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
        }

        $this->addPartisipasiFilter($builder);
        $this->sortColumns($builder);
        $this->sortResumeSuaraPilgubKecamatanPaslon($builder);
        $this->sortResumeSuaraKotakKosong($builder);

        return $builder->paginate($this->perPage);
    }

    // NOTE: Ini gak dipakai
    private function getSuaraPerKabupaten()
    {
        $builder = ResumeSuaraPilgubKabupaten::query()
            ->selectRaw('
                resume_suara_pilgub_kabupaten.id,
                resume_suara_pilgub_kabupaten.nama,
                resume_suara_pilgub_kabupaten.provinsi_id,
                resume_suara_pilgub_kabupaten.dpt,
                resume_suara_pilgub_kabupaten.kotak_kosong,
                resume_suara_pilgub_kabupaten.suara_sah,
                resume_suara_pilgub_kabupaten.suara_tidak_sah,
                resume_suara_pilgub_kabupaten.suara_masuk,
                resume_suara_pilgub_kabupaten.abstain,
                resume_suara_pilgub_kabupaten.partisipasi
            ')
            ->whereIn('resume_suara_pilgub_kabupaten.id', $this->selectedKabupaten);

        $this->addPartisipasiFilter($builder);
        $this->sortColumns($builder);
        $this->sortResumeSuaraPilgubKabupatenPaslon($builder);
        $this->sortResumeSuaraKotakKosong($builder);

        if ($this->keyword) {
            $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
        }

        return $builder->paginate($this->perPage);
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
        $builder = Calon::select([
            'calon.id',
            'calon.nama',
            'calon.nama_wakil',
            'calon.foto',
            'calon.provinsi_id',
            'calon.kabupaten_id',
            DB::raw('SUM(suara_calon.suara) AS suara'),
        ])
            ->leftJoin('suara_calon', 'suara_calon.calon_id', '=', 'calon.id')
            ->where('calon.posisi', $this->posisi)
            ->where('calon.provinsi_id', session('operator_provinsi_id'))
            ->groupBy('calon.id');

        return $builder->get();
    }
    
    private function fillSelectedKecamatan()
    {
        $this->selectedKecamatan = Kecamatan::query()
            ->whereKabupatenId(session('operator_kabupaten_id'))
            ->pluck('id')
            ->all();
    }
    
    #[On('reset-filter')] 
    public function resetFilter()
    {
        $this->fillSelectedKecamatan();

        $this->selectedKecamatan = [];
        $this->selectedKelurahan = [];
        $this->includedColumns = ['KABUPATEN/KOTA', 'KECAMATAN', 'KELURAHAN', 'CALON', 'TPS'];
        $this->partisipasi = ['HIJAU', 'MERAH'];
    }

    #[On('apply-filter')]
    public function applyFilter($selectedKecamatan, $selectedKelurahan, $includedColumns, $partisipasi)
    {
        $this->selectedKecamatan = $selectedKecamatan;
        $this->selectedKelurahan = $selectedKelurahan;
        $this->includedColumns = $includedColumns;
        $this->partisipasi = $partisipasi;
    }

    public function export()
    {
        $sheet = new ResumePilgubExport(
            $this->keyword,
            $this->selectedKecamatan,
            $this->selectedKelurahan,
            $this->includedColumns,
            $this->partisipasi,

            $this->dptSort,
            $this->suaraSahSort,
            $this->suaraTidakSahSort,
            $this->suaraMasukSort,
            $this->abstainSort,
            $this->partisipasiSort,

            $this->paslonIdSort,
            $this->paslonSort,
        );

        return Excel::download($sheet, 'resume-suara-pemilihan-gubernur.xlsx');
    }
}