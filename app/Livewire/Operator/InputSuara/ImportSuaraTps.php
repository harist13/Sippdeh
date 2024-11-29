<?php

namespace App\Livewire\Operator\InputSuara;

use App\Exports\InputSuaraTemplateExport;
use App\Imports\InputSuaraImport;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Sentry\SentrySdk;

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
        if ($this->importSpreadsheet) {
            try {
                $namaSpreadsheet = $this->importSpreadsheet->storeAs(
                    'imports/suara-tps', 
                    'import-' . time() . '.' . $this->importSpreadsheet->getClientOriginalExtension(),
                    'local'
                );

                // 3. Import with error handling
                $tpsImport = new InputSuaraImport($this->posisi);
                Excel::import($tpsImport, $namaSpreadsheet, 'local');

                // 4. Clean up the temporary file
                Storage::disk('local')->delete($namaSpreadsheet);
            } catch (Exception $exception) {
                Log::error($exception);
                SentrySdk::getCurrentHub()->captureException($exception);
                
                dump($exception);
            }
        }
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
