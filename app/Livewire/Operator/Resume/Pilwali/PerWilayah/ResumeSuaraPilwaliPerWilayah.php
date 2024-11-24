<?php

namespace App\Livewire\Operator\Resume\Pilwali\PerWilayah;

use App\Exports\ResumePilwaliExport;
use App\Models\Calon;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\ResumeSuaraPilwaliKecamatan;
use App\Models\ResumeSuaraPilwaliKelurahan;
use App\Traits\SortResumeColumns;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ResumeSuaraPilwaliPerWilayah extends Component
{
    use SortResumeColumns, WithPagination, WithoutUrlPagination;

    public string $posisi = 'WALIKOTA';

    public string $keyword = '';
    public int $perPage = 10;

    public array $selectedKecamatan = [];
    public array $selectedKelurahan = [];
    
    public array $includedColumns = ['KABUPATEN/KOTA', 'KECAMATAN', 'CALON'];
    public array $partisipasi = ['HIJAU', 'KUNING', 'MERAH'];

    public function mount()
    {
        $this->fillSelectedKecamatan();
    }

    public function render()
    {
        if (!empty($this->selectedKelurahan)) {
            return $this->getKelurahanTable();
        }

        return $this->getKecamatanTable();
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
        $this->includedColumns = ['KABUPATEN/KOTA', 'KECAMATAN', 'CALON'];
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

        return Excel::download($sheet, 'resume-suara-pemilihan-walikota.xlsx');
    }
}
