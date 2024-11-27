<?php

namespace App\Livewire\Operator\Resume\Pilwali\PerWilayah;

use App\Exports\ResumePilwaliExport;
use App\Models\Calon;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\ResumeSuaraPilwaliKecamatan;
use App\Models\ResumeSuaraPilwaliKelurahan;
use App\Models\ResumeSuaraPilwaliTPS;
use App\Traits\SortResumeColumns;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ResumeSuaraPilwaliPerWilayah extends Component
{
    use SortResumeColumns, WithPagination, WithoutUrlPagination;

    public string $posisi = 'WALIKOTA';

    public string $keyword = '';
    public int $perPage = 10;

    public array $selectedKecamatan = [];
    public array $selectedKelurahan = [];
    
    public array $includedColumns = ['KABUPATEN/KOTA', 'KECAMATAN', 'KELURAHAN', 'CALON', 'TPS'];
    public array $partisipasi = ['HIJAU', 'MERAH'];

    public function mount()
    {
        $this->fillSelectedKecamatan();
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

    private function getTpsTable()
    {
        $paslon = $this->getPaslon();
        $suara = $this->getSuaraPerTps();
        return view('operator.resume.pilwali.per-wilayah.livewire', compact('suara', 'paslon'));
    }

    private function getKelurahanTable()
    {
        $paslon = $this->getPaslon();
        $suara = $this->getSuaraPerKelurahan();
        return view('operator.resume.pilwali.per-wilayah.livewire', compact('suara', 'paslon'));
    }

    private function getKecamatanTable()
    {
        $paslon = $this->getPaslon();
        $suara = $this->getSuaraPerKecamatan();
        return view('operator.resume.pilwali.per-wilayah.livewire', compact('suara', 'paslon'));
    }

    private function getSuaraPerTps()
    {
        $builder = ResumeSuaraPilwaliTPS::query()
            ->selectRaw('
                resume_suara_pilwali_tps.id,
                resume_suara_pilwali_tps.nama,
                resume_suara_pilwali_tps.dpt,
                resume_suara_pilwali_tps.kotak_kosong,
                resume_suara_pilwali_tps.suara_sah,
                resume_suara_pilwali_tps.suara_tidak_sah,
                resume_suara_pilwali_tps.suara_masuk,
                resume_suara_pilwali_tps.abstain,
                resume_suara_pilwali_tps.partisipasi
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

                    $builder->whereHas('kabupaten', function (Builder $builder) {
                        $builder->whereId(session('operator_kabupaten_id'));
                    });
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
        $this->sortResumeSuaraPilwaliTpsPaslon($builder);
        $this->sortColumns($builder);
        $this->sortResumeSuaraKotakKosong($builder);

        return $builder->paginate($this->perPage);
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
            ');
        
        $builder->whereIn('resume_suara_pilwali_kelurahan.id', $this->selectedKelurahan);

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
        $this->sortResumeSuaraPilwaliKelurahanPaslon($builder);
        $this->sortResumeSuaraKotakKosong($builder);

        return $builder->paginate($this->perPage);
    }

    private function getSuaraPerKecamatan()
    {
        $builder = ResumeSuaraPilwaliKecamatan::query()
            ->selectRaw('
                resume_suara_pilwali_kecamatan.id,
                resume_suara_pilwali_kecamatan.nama,
                resume_suara_pilwali_kecamatan.kabupaten_id,
                resume_suara_pilwali_kecamatan.dpt,
                resume_suara_pilwali_kecamatan.dptb,
                resume_suara_pilwali_kecamatan.dpk,
                resume_suara_pilwali_kecamatan.kotak_kosong,
                resume_suara_pilwali_kecamatan.suara_sah,
                resume_suara_pilwali_kecamatan.suara_tidak_sah,
                resume_suara_pilwali_kecamatan.suara_masuk,
                resume_suara_pilwali_kecamatan.abstain,
                resume_suara_pilwali_kecamatan.partisipasi
            ');
        
        $builder->whereIn('resume_suara_pilwali_kecamatan.id', $this->selectedKecamatan);

        if ($this->keyword) {
            $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
        }

        $this->addPartisipasiFilter($builder);
        $this->sortColumns($builder);
        $this->sortResumeSuaraPilwaliKecamatanPaslon($builder);
        $this->sortResumeSuaraKotakKosong($builder);

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

    private function fillSelectedKecamatan(): void
    {
        $this->selectedKecamatan = Kecamatan::query()
            ->whereKabupatenId(session('operator_kabupaten_id'))
            ->get()
            ->pluck('id')
            ->all();
    }

    #[On('reset-filter')] 
    public function resetFilter(): void
    {
        $this->fillSelectedKecamatan();
        
        $this->selectedKelurahan = [];
        $this->includedColumns = ['KABUPATEN/KOTA', 'KECAMATAN', 'KELURAHAN', 'CALON', 'TPS'];
        $this->partisipasi = ['HIJAU', 'MERAH'];
    }

    #[On('apply-filter')]
    public function applyFilter($selectedKecamatan, $selectedKelurahan, $includedColumns, $partisipasi): void
    {
        $this->selectedKecamatan = $selectedKecamatan;
        $this->selectedKelurahan = $selectedKelurahan;
        $this->includedColumns = $includedColumns;
        $this->partisipasi = $partisipasi;
    }

    public function export(): BinaryFileResponse
    {
        $sheet = new ResumePilwaliExport(
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

        return Excel::download($sheet, 'resume-suara-pemilihan-bupkota.xlsx');
    }

    public function exportPdf()
{
    try {
        // Get data based on current view and filters
        if (in_array('TPS', $this->includedColumns)) {
            $data = ResumeSuaraPilwaliTPS::query()
                ->whereHas('tps', function(Builder $builder) {
                    $builder->whereHas('kelurahan', function (Builder $builder) {
                        if (!empty($this->selectedKelurahan)) {
                            $builder->whereIn('id', $this->selectedKelurahan);
                        }

                        $builder->whereHas('kecamatan', function(Builder $builder) {
                            if (!empty($this->selectedKecamatan)) {
                                $builder->whereIn('id', $this->selectedKecamatan);
                            }
                        });
                    });
                });
        } elseif (!empty($this->selectedKelurahan)) {
            $data = ResumeSuaraPilwaliKelurahan::query()
                ->whereIn('id', $this->selectedKelurahan);
        } else {
            $data = ResumeSuaraPilwaliKecamatan::query()
                ->whereIn('id', $this->selectedKecamatan);
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
            $data->where('nama', 'like', '%' . $this->keyword . '%');
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

        $kabupaten = Kabupaten::whereId(session('operator_kabupaten_id'))->first();
        $paslon = $this->getPaslon();

        $viewData = [
            'data' => $finalData,
            'logo' => $kabupaten->logo ?? null,
            'kabupaten' => $kabupaten,
            'paslon' => $paslon,
            'includedColumns' => $this->includedColumns,
            'isPilkadaTunggal' => count($paslon) === 1,
            'isKelurahanView' => !empty($this->selectedKelurahan),
            'isTpsView' => in_array('TPS', $this->includedColumns),
            'keyword' => $this->keyword,
            'partisipasi' => $this->partisipasi
        ];

        $pdf = PDF::loadView('exports.resume-suara-pilwali-wilayah-pdf', $viewData);
        $pdf->setPaper('A4', 'landscape');

        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, 'resume-suara-pemilihan-bupati.pdf', [
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
}