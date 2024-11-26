<?php

namespace App\Livewire\Operator\Resume\Pilbup\PerWilayah;

use App\Exports\ResumePilbupExport;
use App\Models\Calon;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\ResumeSuaraPilbupKecamatan;
use App\Models\ResumeSuaraPilbupKelurahan;
use App\Models\ResumeSuaraPilbupTPS;
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

class ResumeSuaraPilbupPerWilayah extends Component
{
    use SortResumeColumns, WithPagination, WithoutUrlPagination;

    public string $posisi = 'BUPATI';

    public string $keyword = '';
    public int $perPage = 10;

    public array $selectedKecamatan = [];
    public array $selectedKelurahan = [];
    
    public array $includedColumns = ['KABUPATEN/KOTA', 'KECAMATAN', 'KELURAHAN', 'CALON', 'TPS'];
    public array $partisipasi = ['HIJAU', 'KUNING', 'MERAH'];

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
        return view('operator.resume.pilbup.per-wilayah.livewire', compact('suara', 'paslon'));
    }

    private function getKelurahanTable()
    {
        $paslon = $this->getPaslon();
        $suara = $this->getSuaraPerKelurahan();
        return view('operator.resume.pilbup.per-wilayah.livewire', compact('suara', 'paslon'));
    }

    private function getKecamatanTable()
    {
        $paslon = $this->getPaslon();
        $suara = $this->getSuaraPerKecamatan();
        return view('operator.resume.pilbup.per-wilayah.livewire', compact('suara', 'paslon'));
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

                            // TODO: Tidak pakai kabupaten dari operator itu sendiri
                            // $builder->whereId(session('operator_kabupaten_id'));
                        });
                    });
                });
            });

        $this->addPartisipasiFilter($builder);
        $this->sortResumeSuaraPilbupTpsPaslon($builder);
        $this->sortColumns($builder);
        $this->sortResumeSuaraKotakKosong($builder);

        if ($this->keyword) {
            $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
        }

        return $builder->paginate($this->perPage);
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
            ')
            ->whereIn('resume_suara_pilbup_kelurahan.id', $this->selectedKelurahan);

        $this->addPartisipasiFilter($builder);
        $this->sortColumns($builder);
        $this->sortResumeSuaraPilbupKelurahanPaslon($builder);
        $this->sortResumeSuaraKotakKosong($builder);

        if ($this->keyword) {
            $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
        }

        return $builder->paginate($this->perPage);
    }

    private function getSuaraPerKecamatan()
    {
        $builder = ResumeSuaraPilbupKecamatan::query()
            ->selectRaw('
                resume_suara_pilbup_kecamatan.id,
                resume_suara_pilbup_kecamatan.nama,
                resume_suara_pilbup_kecamatan.kabupaten_id,
                resume_suara_pilbup_kecamatan.dpt,
                resume_suara_pilbup_kecamatan.kotak_kosong,
                resume_suara_pilbup_kecamatan.suara_sah,
                resume_suara_pilbup_kecamatan.suara_tidak_sah,
                resume_suara_pilbup_kecamatan.suara_masuk,
                resume_suara_pilbup_kecamatan.abstain,
                resume_suara_pilbup_kecamatan.partisipasi
            ')
            ->whereIn('resume_suara_pilbup_kecamatan.id', $this->selectedKecamatan);

        $this->addPartisipasiFilter($builder);
        $this->sortColumns($builder);
        $this->sortResumeSuaraPilbupKecamatanPaslon($builder);
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
        $this->partisipasi = ['HIJAU', 'KUNING', 'MERAH'];
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
        $sheet = new ResumePilbupExport(
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
            // Get data based on current view without pagination
            if (!empty($this->selectedKelurahan)) {
                $data = ResumeSuaraPilbupKelurahan::query()
                    ->whereIn('id', $this->selectedKelurahan)
                    ->where(function($query) {
                        if ($this->keyword) {
                            $query->where('nama', 'like', '%' . $this->keyword . '%');
                        }
                    })->get();
            } else {
                $data = ResumeSuaraPilbupKecamatan::query()
                    ->whereIn('id', $this->selectedKecamatan)
                    ->where(function($query) {
                        if ($this->keyword) {
                            $query->where('nama', 'like', '%' . $this->keyword . '%');
                        }
                    })->get();
            }

            if ($data->isEmpty()) {
                $this->dispatch('showAlert', [
                    'type' => 'error',
                    'message' => 'Tidak ada data untuk di-export'
                ]);
                return;
            }

            $kabupaten = Kabupaten::whereId(session('operator_kabupaten_id'))->first();
            $paslon = $this->getPaslon();

            $pdf = PDF::loadView('exports.resume-suara-pilbup-wilayah-pdf', [
                'data' => $data,
                'logo' => $kabupaten->logo ?? null,
                'kabupaten' => $kabupaten,
                'paslon' => $paslon,
                'includedColumns' => $this->includedColumns,
                'isPilkadaTunggal' => count($paslon) === 1,
                'isKelurahanView' => !empty($this->selectedKelurahan)
            ]);

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
                'message' => 'Gagal mengekspor PDF'
            ]);
        }
    }
}