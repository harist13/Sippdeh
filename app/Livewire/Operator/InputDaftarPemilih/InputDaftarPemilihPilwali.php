<?php

namespace App\Livewire\Operator\InputDaftarPemilih;

use App\Models\DaftarPemilih;
use App\Models\DaftarPemilihPilwaliKecamatan;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Sentry\SentrySdk;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;

class InputDaftarPemilihPilwali extends Component
{
    public string $posisi = 'WALIKOTA';
    
    public string $keyword = '';
    public int $perPage = 10;

    public function render()
    {
        $kecamatan = $this->getKecamatan();
        return view('operator.input-daftar-pemilih.pilwali.livewire', compact('kecamatan'));
    }

    private function getKecamatan(): LengthAwarePaginator
    {
        try {
            return DaftarPemilihPilwaliKecamatan::query()
                ->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%'])
                ->paginate($this->perPage);
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);
        }
    }

    #[On('submit-kecamatan')]
    public function submit(array $data)
    {
        try {
            foreach ($data as $datum) {
                DaftarPemilih::updateOrCreate(
                    [
                        'kecamatan_id' => $datum['id'],
                        'posisi' => $this->posisi
                    ],
                    [
                        'dptb' => $datum['dptb'],
                        'dpk' => $datum['dpk']
                    ]
                );
            }

            session()->flash('pesan_sukses', 'Berhasil menyimpan data.');
            $this->dispatch('data-stored', status: 'sukses');
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);

            session()->flash('pesan_gagal', 'Gagal menyimpan data.');
            $this->dispatch('data-stored', status: 'gagal');
        }
    }
}
