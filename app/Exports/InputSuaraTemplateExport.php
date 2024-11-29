<?php

namespace App\Exports;

use App\Models\Calon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class InputSuaraTemplateExport implements FromView
{
    public $posisi;

    public $tpsQuery;

    public function __construct(string $posisi, string $tpsQuery)
    {
        $this->posisi = $posisi;
        $this->tpsQuery = $tpsQuery;
    }

    private function getPaslon(): Collection
    {
        if ($this->posisi == 'GUBERNUR') {
            $builder = Calon::query()
                ->whereProvinsiId(session('operator_provinsi_id'))
                ->wherePosisi($this->posisi);
    
            return $builder->get();
        }

        if ($this->posisi == 'WALIKOTA') {
            $builder = Calon::query()
                ->whereKabupatenId(session('operator_kabupaten_id'))
                ->wherePosisi($this->posisi);
    
            return $builder->get();
        }

        return collect([]);
    }

    public function view(): View
    {
        return view('exports.template-input-suara', [
            'tps' => DB::select($this->tpsQuery),
            'paslon' => $this->getPaslon()
        ]);
    }
}
