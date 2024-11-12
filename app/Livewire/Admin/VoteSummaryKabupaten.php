<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ResumeSuaraTPS;
use Illuminate\Support\Facades\DB;

class VoteSummaryKabupaten extends Component
{
    public function getKabupatenData()
    {
        return ResumeSuaraTPS::select(
            'kabupaten.id',
            'kabupaten.nama as kabupaten_nama',
            DB::raw('SUM(resume_suara_tps.dpt) as total_dpt'),
            DB::raw('SUM(resume_suara_tps.suara_sah) as total_suara_sah'),
            DB::raw('SUM(resume_suara_tps.suara_tidak_sah) as total_suara_tidak_sah'),
            DB::raw('SUM(resume_suara_tps.abstain) as total_abstain'),
            DB::raw('SUM(resume_suara_tps.suara_masuk) as total_suara_masuk'),
            DB::raw('ROUND((SUM(resume_suara_tps.suara_masuk) / SUM(resume_suara_tps.dpt)) * 100, 1) as partisipasi')
        )
        ->join('tps', 'resume_suara_tps.id', '=', 'tps.id')
        ->join('kelurahan', 'tps.kelurahan_id', '=', 'kelurahan.id')
        ->join('kecamatan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
        ->join('kabupaten', 'kecamatan.kabupaten_id', '=', 'kabupaten.id')
        ->groupBy('kabupaten.id', 'kabupaten.nama')
        ->orderBy('kabupaten.nama')
        ->get();
    }

    public function render()
    {
        $kabupatenData = $this->getKabupatenData();
        
        return view('livewire.admin.vote-summary-kabupaten', [
            'kabupatenData' => $kabupatenData
        ]);
    }
}
