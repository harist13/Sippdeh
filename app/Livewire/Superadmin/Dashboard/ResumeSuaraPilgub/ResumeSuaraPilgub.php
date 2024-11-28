<?php

namespace App\Livewire\Superadmin\Dashboard\ResumeSuaraPilgub;

use App\Models\Calon;
use App\Models\ResumeSuaraPilgubKecamatan;
use App\Traits\SortResumeColumns;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ResumeSuaraPilgub extends Component
{
    use SortResumeColumns, WithPagination, WithoutUrlPagination;

    public string $posisi = 'GUBERNUR';

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
        return view('superadmin.dashboard.resume-suara-pilgub.livewire', compact('suara', 'paslon'));
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
            ')
            ->join('kabupaten', 'kabupaten.id', '=', 'resume_suara_pilgub_kecamatan.kabupaten_id')
            ->addSelect('kabupaten.nama as kabupaten_nama');

        if ($this->keyword) {
            $builder->where(function($query) {
                $query->whereRaw('LOWER(resume_suara_pilgub_kecamatan.nama) LIKE ?', ['%' . strtolower($this->keyword) . '%'])
                      ->orWhereRaw('LOWER(kabupaten.nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
            });
        }

        $this->sortColumns($builder);
        $this->sortResumeSuaraPilgubKecamatanPaslon($builder);
        $this->sortResumeSuaraKotakKosong($builder);

        return $builder->paginate($this->perPage);
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
            ->groupBy('calon.id');

        return $builder->get();
    }
}
