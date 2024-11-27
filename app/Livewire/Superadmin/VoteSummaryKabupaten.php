<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ResumeSuaraTPS;
use App\Models\Kabupaten;
use Illuminate\Support\Facades\DB;
use App\Exports\VoteKabupatenExport;
use Excel;

class VoteSummaryKabupaten extends Component
{
    public $search = '';
    public $showFilterModal = false;
    public $kabupaten_ids = [];
    public $searchKabupaten = '';
    public $partisipasi = [];

    // Reset filter
    public function resetFilter()
    {
        $this->kabupaten_ids = [];
        $this->partisipasi = [];
        $this->searchKabupaten = '';
    }

    // Reset dropdown search
    public function resetDropdownSearch()
    {
        $this->searchKabupaten = '';
    }

    // Get filtered kabupaten list
    public function getFilteredKabupatens()
    {
        return Kabupaten::where('nama', 'like', '%' . $this->searchKabupaten . '%')
            ->orderBy('nama')
            ->get();
    }

    public function getKabupatenData()
    {
        $query = ResumeSuaraTPS::select(
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
        ->groupBy('kabupaten.id', 'kabupaten.nama');

        // Apply search filter
        if (!empty($this->search)) {
            $query->where('kabupaten.nama', 'like', '%' . $this->search . '%');
        }

        // Apply kabupaten filter
        if (!empty($this->kabupaten_ids)) {
            $query->whereIn('kabupaten.id', $this->kabupaten_ids);
        }

        // Apply partisipasi filter
        if (!empty($this->partisipasi)) {
            $partisipasiConditions = [];
            foreach ($this->partisipasi as $level) {
                switch ($level) {
                    case 'hijau':
                        $partisipasiConditions[] = 'partisipasi >= 70';
                        break;
                    case 'kuning':
                        $partisipasiConditions[] = 'partisipasi >= 50 AND partisipasi < 70';
                        break;
                    case 'merah':
                        $partisipasiConditions[] = 'partisipasi < 50';
                        break;
                }
            }
            if (!empty($partisipasiConditions)) {
                $query->havingRaw('(' . implode(' OR ', $partisipasiConditions) . ')');
            }
        }

        return $query->orderBy('kabupaten.nama')->get();
    }

     public function export()
    {
        $data = $this->getKabupatenData();
        return Excel::download(
            new VoteKabupatenExport($data),
            'partisipasi_suara_kabupaten_' . date('Y-m-d_H-i-s') . '.xlsx'
        );
    }

    public function render()
    {
        return view('livewire.admin.vote-summary-kabupaten', [
            'kabupatenData' => $this->getKabupatenData(),
            'filteredKabupatens' => $this->getFilteredKabupatens()
        ]);
    }
}