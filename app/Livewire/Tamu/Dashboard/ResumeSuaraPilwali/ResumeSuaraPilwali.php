<?php

namespace App\Livewire\Tamu\Dashboard\ResumeSuaraPilwali;

use App\Models\Calon;
use App\Models\ResumeSuaraPilwaliKecamatan;
use App\Traits\SortResumeColumns;
use Exception;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Sentry\SentrySdk;

class ResumeSuaraPilwali extends Component
{
    use SortResumeColumns, WithPagination, WithoutUrlPagination;

    public string $posisi = 'WALIKOTA';

    public string $keyword = '';
    public int $perPage = 10;

    public function render()
    {
        return $this->getKecamatanTable();
    }

    private function getKecamatanTable()
    {
        $paslon = $this->getPaslon();
        $suara = $this->getSuaraPerKecamatan();
        return view('Tamu.dashboard.resume-suara-pilwali.livewire', compact('suara', 'paslon'));
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
            ');

        $builder->where('resume_suara_pilwali_kecamatan.kabupaten_id', session('Tamu_kabupaten_id'));

        if ($this->keyword) {
            $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
        }

        $this->sortColumns($builder);
        $this->sortResumeSuaraPilwaliKecamatanPaslon($builder);
        $this->sortResumeSuaraKotakKosong($builder);

        return $builder->paginate($this->perPage);
    }

    private function getPaslon()
    {
        try {
            $builder = Calon::with('suaraCalon')
                ->whereKabupatenId(session('Tamu_kabupaten_id'))
                ->wherePosisi($this->posisi);
    
            return $builder->get();
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);

            throw $exception;
        }
    }
}