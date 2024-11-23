<?php

namespace App\Livewire\Operator\Dashboard\ResumeSuaraPilgub;

use App\Exports\ResumePilgubExport;
use App\Models\Calon;
use App\Models\Kabupaten;
use App\Models\ResumeSuaraPilgubKabupaten;
use App\Models\ResumeSuaraPilgubKecamatan;
use App\Models\ResumeSuaraPilgubKelurahan;
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

class ResumeSuaraPilgub extends Component
{
    use SortResumeColumns, WithPagination, WithoutUrlPagination;

    public string $posisi = 'GUBERNUR';

    public string $keyword = '';

    public int $perPage = 10;

    public array $selectedKabupaten = [];
    public array $selectedKecamatan = [];
    public array $selectedKelurahan = [];
    public array $includedColumns = ['KABUPATEN/KOTA', 'KECAMATAN', 'KELURAHAN', 'CALON'];
    public array $partisipasi = ['HIJAU', 'KUNING', 'MERAH'];

    public $paslonSorts = [];

    public function mount()
    {
        $this->fillSelectedKabupaten();
        $this->includedColumns = ['KABUPATEN/KOTA', 'CALON'];
    }

    public function render()
    {
        if (!empty($this->selectedKelurahan)) {
            return $this->getKelurahanTable();
        }

        if (!empty($this->selectedKecamatan)) {
            return $this->getKecamatanTable();
        }
        
        return $this->getKabupatenTable();
    }

     public function exportPdf()
    {
        try {
            // Ambil data tanpa paginasi
            if (!empty($this->selectedKelurahan)) {
                $data = ResumeSuaraPilgubKelurahan::query()
                    ->whereIn('id', $this->selectedKelurahan)
                    ->where(function($query) {
                        if ($this->keyword) {
                            $query->where('nama', 'like', '%' . $this->keyword . '%');
                        }
                    })->get();
            } elseif (!empty($this->selectedKecamatan)) {
                $data = ResumeSuaraPilgubKecamatan::query()
                    ->whereIn('id', $this->selectedKecamatan)
                    ->where(function($query) {
                        if ($this->keyword) {
                            $query->where('nama', 'like', '%' . $this->keyword . '%');
                        }
                    })->get();
            } else {
                $data = ResumeSuaraPilgubKabupaten::query()
                    ->whereIn('id', $this->selectedKabupaten)
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

            $kabupaten = Kabupaten::whereNama(session('user_wilayah'))->first();
            $paslon = $this->getCalon();

            $pdf = PDF::loadView('exports.resume-suara-pilgub-pdf', [
                'data' => $data,
                'logo' => $kabupaten->logo ?? null,
                'kabupaten' => $kabupaten,
                'paslon' => $paslon,
                'includedColumns' => $this->includedColumns,
            ]);

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
                'message' => 'Gagal mengekspor PDF'
            ]);
        }
    }

    private function getKelurahanTable()
    {
        $paslon = $this->getCalon();
        $suara = $this->getSuaraPerKelurahan();
        $scope = 'kelurahan';
        
        return view('operator.dashboard.resume-suara-pilgub.livewire', compact('suara', 'paslon', 'scope'));
    }

    private function getKecamatanTable()
    {
        $paslon = $this->getCalon();
        $suara = $this->getSuaraPerKecamatan();
        $scope = 'kecamatan';
    
        
        return view('operator.dashboard.resume-suara-pilgub.livewire', compact('suara', 'paslon', 'scope'));
    }

    private function getKabupatenTable()
    {
        $paslon = $this->getCalon();
        $suara = $this->getSuaraPerKabupaten();
        $scope = 'kabupaten';

        return view('operator.dashboard.resume-suara-pilgub.livewire', compact('suara', 'paslon', 'scope'));
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
            ')
            ->whereIn('resume_suara_pilgub_kelurahan.id', $this->selectedKelurahan);

        $this->addPartisipasiFilter($builder);
        $this->sortColumns($builder);
        $this->sortResumeSuaraPilgubKelurahanPaslon($builder);
        $this->sortResumeSuaraKotakKosong($builder);

        if ($this->keyword) {
            $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
        }

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
                resume_suara_pilgub_kecamatan.kotak_kosong,
                resume_suara_pilgub_kecamatan.suara_sah,
                resume_suara_pilgub_kecamatan.suara_tidak_sah,
                resume_suara_pilgub_kecamatan.suara_masuk,
                resume_suara_pilgub_kecamatan.abstain,
                resume_suara_pilgub_kecamatan.partisipasi
            ')
            ->whereIn('resume_suara_pilgub_kecamatan.id', $this->selectedKecamatan);

        $this->addPartisipasiFilter($builder);
        $this->sortColumns($builder);
        $this->sortResumeSuaraPilgubKecamatanPaslon($builder);
        $this->sortResumeSuaraKotakKosong($builder);

        if ($this->keyword) {
            $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
        }

        return $builder->paginate($this->perPage);
    }

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

    private function fillSelectedKabupaten()
    {
        $this->selectedKabupaten = Kabupaten::query()
            ->whereHas('provinsi', function (Builder $builder) {
                $builder->whereHas('kabupaten', fn (Builder $builder) => $builder->whereNama(session('user_wilayah')));
            })
            ->pluck('id')
            ->all();
    }

    private function getCalon()
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
            ->where('calon.provinsi_id', $this->getProvinsiIdOfOperator())
            ->groupBy('calon.id');

        return $builder->get();
    }

    private function getProvinsiIdOfOperator(): int
    {
        $kabupaten = Kabupaten::whereNama(session('user_wilayah'));

        if ($kabupaten->count() > 0) {
            $kabupaten = $kabupaten->first();
            return $kabupaten->provinsi_id;
        }

        return 0;
    }
    
    #[On('reset-filter')] 
    public function resetFilter()
    {
        $this->fillSelectedKabupaten();

        $this->selectedKecamatan = [];
        $this->selectedKelurahan = [];
        $this->includedColumns = ['KABUPATEN/KOTA', 'CALON'];
        $this->partisipasi = ['HIJAU', 'KUNING', 'MERAH'];
    }

    #[On('apply-filter')]
    public function applyFilter($selectedKabupaten, $selectedKecamatan, $selectedKelurahan, $includedColumns, $partisipasi)
    {
        $this->selectedKabupaten = $selectedKabupaten;
        $this->selectedKecamatan = $selectedKecamatan;
        $this->selectedKelurahan = $selectedKelurahan;
        $this->includedColumns = $includedColumns;
        $this->partisipasi = $partisipasi;
    }

    public function export()
    {
        $sheet = new ResumePilgubExport(
            $this->keyword,
            $this->selectedKabupaten,
            $this->selectedKecamatan,
            $this->selectedKelurahan,
            $this->includedColumns,
            $this->partisipasi
        );

        return Excel::download($sheet, 'resume-suara-pemilihan-gubernur.xlsx');
    }
}