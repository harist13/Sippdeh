<?php

namespace App\Livewire\Operator\InputSuara\Pilbup;

use App\Models\DaftarPemilih;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Sentry\SentrySdk;
use Exception;

class InputDaftarPemilihPilbup extends Component
{
    public string $posisi = 'BUPATI';

    public int $dptb = 0;
    public int $dpk = 0;

    public function mount()
    {
        $daftarPemilih = $this->getDaftarPemilih();
        
        if ($daftarPemilih) {
            $this->dptb = $daftarPemilih->dptb;
            $this->dpk = $daftarPemilih->dpk;
        }
    }

    public function render()
    {
        return view('operator.input-suara.pilbup.input-daftar-pemilih');
    }

    private function getDaftarPemilih(): ?DaftarPemilih
    {
        try {
            return DaftarPemilih::query()
                ->wherePosisi($this->posisi)
                ->whereKabupatenId(session('operator_kabupaten_id'))
                ->first();
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);
        }
    }

    public function submit()
    {
        try {
            DaftarPemilih::updateOrCreate(
                [
                    'kabupaten_id' => session('operator_kabupaten_id'),
                    'posisi' => $this->posisi
                ],
                [
                    'dptb' => $this->dptb,
                    'dpk' => $this->dpk
                ]
            );

            session()->flash('pesan_sukses', 'Berhasil menyimpan data.');
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);

            session()->flash('pesan_gagal', 'Gagal menyimpan data.');
        }
    }
}
