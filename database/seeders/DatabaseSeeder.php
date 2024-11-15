<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Console\View\Components\TwoColumnDetail;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    public int $startTime;

    /**
     * Seed the application's database.
     */
    public function run(): void
   {
        $this->startTime = microtime(true);

        $this->call([
            ProvinsiSeeder::class,
            KabupatenSeeder::class,
            KecamatanSeeder::class,
            KelurahanSeeder::class,
        ]);

        $this->running();
        Artisan::call('import:tps');
        $this->done();

        $this->call([
            UserSeeder::class,
            CalonSeeder::class,
        ]);
    }

    public function running()
    {
        with(new TwoColumnDetail($this->command->getOutput()))->render(
            'Kecamatan, Kelurahan, dan TPS dari berkas CSV',
            '<fg=yellow;options=bold>RUNNING</>'
        );
    }

    public function done()
    {
        $runTime = number_format((microtime(true) - $this->startTime) * 1000);
        
        with(new TwoColumnDetail($this->command->getOutput()))->render(
            'Kecamatan, Kelurahan, dan TPS dari berkas CSV',
            "<fg=gray>$runTime ms</> <fg=green;options=bold>DONE</>"
        );

        $this->command->getOutput()->writeln('');
    }
}
