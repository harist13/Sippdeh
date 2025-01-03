<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ResumeSuaraTPS;
use App\Models\Provinsi;
use Illuminate\Support\Facades\DB;
use App\Exports\VoteProvinsiExport;
use Excel;

class VoteSummaryProvinsi extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $filterPartisipasi = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function getProvinsiData()
    {
        $query = ResumeSuaraTPS::query()
            ->join('tps', 'resume_suara_tps.id', '=', 'tps.id')
            ->join('kelurahan', 'tps.kelurahan_id', '=', 'kelurahan.id')
            ->join('kecamatan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
            ->join('kabupaten', 'kecamatan.kabupaten_id', '=', 'kabupaten.id')
            ->join('provinsi', 'kabupaten.provinsi_id', '=', 'provinsi.id')
            ->select(
                'provinsi.id',
                'provinsi.nama as provinsi_nama',
                DB::raw('COUNT(DISTINCT tps.id) as total_tps'),
                DB::raw('SUM(resume_suara_tps.dpt) as total_dpt'),
                DB::raw('SUM(
                    CASE 
                        WHEN suara_calon.suara IS NOT NULL THEN suara_calon.suara 
                        ELSE 0 
                    END
                ) as total_suara_sah'),
                DB::raw('SUM(
                    CASE 
                        WHEN suara_tps.suara_tidak_sah IS NOT NULL THEN suara_tps.suara_tidak_sah 
                        ELSE 0 
                    END
                ) as total_suara_tidak_sah'),
                DB::raw('SUM(
                    resume_suara_tps.dpt - (
                        CASE 
                            WHEN suara_calon.suara IS NOT NULL THEN suara_calon.suara 
                            ELSE 0 
                        END + 
                        CASE 
                            WHEN suara_tps.suara_tidak_sah IS NOT NULL THEN suara_tps.suara_tidak_sah 
                            ELSE 0 
                        END
                    )
                ) as total_abstain'),
                DB::raw('SUM(
                    CASE 
                        WHEN suara_calon.suara IS NOT NULL THEN suara_calon.suara 
                        ELSE 0 
                    END + 
                    CASE 
                        WHEN suara_tps.suara_tidak_sah IS NOT NULL THEN suara_tps.suara_tidak_sah 
                        ELSE 0 
                    END
                ) as total_suara_masuk')
            )
            ->leftJoin('suara_tps', 'tps.id', '=', 'suara_tps.tps_id')
            ->leftJoin('suara_calon', 'tps.id', '=', 'suara_calon.tps_id')
            ->groupBy('provinsi.id', 'provinsi.nama');

        if (!empty($this->search)) {
            $query->where('provinsi.nama', 'like', '%' . $this->search . '%');
        }

        if ($this->filterPartisipasi) {
            switch ($this->filterPartisipasi) {
                case 'tinggi':
                    $query->havingRaw('(total_suara_masuk / total_dpt) * 100 >= 77.5');
                    break;
                case 'rendah':
                    $query->havingRaw('(total_suara_masuk / total_dpt) * 100 < 77.5');
                    break;
            }
        }

        return $query->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.admin.vote-summary-provinsi', [
            'summaryData' => $this->getProvinsiData()
        ]);
    }

    public function export()
    {
        $data = $this->getProvinsiData();
        return Excel::download(
            new VoteProvinsiExport($data),
            'partisipasi_suara_provinsi_' . date('Y-m-d_H-i-s') . '.xlsx'
        );
    }
}