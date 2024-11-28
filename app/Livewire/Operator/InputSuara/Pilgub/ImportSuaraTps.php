<?php

namespace App\Livewire\Operator\InputSuara\Pilgub;

use Livewire\Component;
use Livewire\WithFileUploads;

class ImportSuaraTps extends Component
{
    use WithFileUploads;

    public $importSpreadsheet;

    public function render()
    {
        return view('operator.input-suara.pilgub.import-suara-tps');
    }

    public function import()
    {
        dump($this->importSpreadsheet);
    }
}
