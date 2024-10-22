<?php

namespace App\Console\Commands;

use App\Imports\TPSImport;
use Illuminate\Console\Command;

class ImportTPSCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:tps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Mengimpor dari berkas '0kKIrotQFhkdEYDIfsvlysLcBAWQqafbjr3InObT.xlsx' di disk 'local'.";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->output->title('Memulai impor...');
        $import = new TPSImport();
        $import->withOutput($this->output)->import('0kKIrotQFhkdEYDIfsvlysLcBAWQqafbjr3InObT.xlsx', 'local');
        $this->output->success('Impor sukses.');
    }
}
