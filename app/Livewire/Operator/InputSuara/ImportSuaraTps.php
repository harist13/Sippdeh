<?php

namespace App\Livewire\Operator\InputSuara;

use App\Exports\InputSuaraTemplateExport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ImportSuaraTps extends Component
{
    use WithFileUploads;

    public $importSpreadsheet;

    public $posisi;

    public $tpsQuery;

    public function mount(string $posisi, string $tpsQuery)
    {
        $this->posisi = $posisi;
        $this->tpsQuery = $tpsQuery;
    }

    public function render()
    {
        return view('operator.input-suara.import-suara-tps');
    }

    public function import()
    {
        dump($this->importSpreadsheet);
    }

    public function downloadTemplate()
    {
        $sheet = new InputSuaraTemplateExport(
            posisi: $this->posisi,
            tpsQuery: html_entity_decode($this->tpsQuery)
        );

        return Excel::download($sheet, 'template-input-suara-pilgub.xlsx');
    }
}
