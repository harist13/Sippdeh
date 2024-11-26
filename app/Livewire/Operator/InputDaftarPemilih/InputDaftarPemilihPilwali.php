<?php

namespace App\Livewire\Operator\InputDaftarPemilih;

use App\Models\Calon;
use App\Models\DaftarPemilih;
use App\Models\DaftarPemilihPilwaliKecamatan;
use App\Models\SuaraCalonDaftarPemilih;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Sentry\SentrySdk;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class InputDaftarPemilihPilwali extends Component
{
    public string $posisi = 'WALIKOTA';
    
    public string $keyword = '';
    public int $perPage = 10;

    public function render()
    {
        $kecamatan = $this->getKecamatan();
        $paslon = $this->getPaslon();
        return view('operator.input-daftar-pemilih.pilwali.livewire', compact('kecamatan', 'paslon'));
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

    private function insertSuaraDaftarPemilih(int $kecamatanId, int $dptb, int $dpk, int $kotakKosong, int $suaraTidakSah): void
    {
        try {
            DaftarPemilih::updateOrCreate(
                [
                    'kecamatan_id' => $kecamatanId,
                    'posisi' => $this->posisi
                ],
                [
                    'dptb' => $dptb,
                    'dpk' => $dpk,
                    'kotak_kosong' => $kotakKosong,
                    'suara_tidak_sah' => $suaraTidakSah,
                    'operator_id' => Auth::id()
                ]
            );
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    private function insertSuaraCalon(int $kecamatanId, int $calonId, int $suara): void
    {
        try {
            SuaraCalonDaftarPemilih::updateOrCreate(
                [
                    'kecamatan_id' => $kecamatanId,
                    'calon_id' => $calonId,
                ],
                [
                    'suara' => $suara,
                    'operator_id' => Auth::id()
                ]
            );
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    private function getPaslon(): Collection
    {
        $builder = Calon::with('suaraCalon')
            ->whereKabupatenId(session('operator_kabupaten_id'))
            ->wherePosisi($this->posisi);

        return $builder->get();
    }

    #[On('submit-kecamatan')]
    public function submit(array $data)
    {
        try {
            DB::beginTransaction();
            
            foreach ($data as $datum) {
                $this->insertSuaraDaftarPemilih($datum['id'], $datum['dptb'], $datum['dpk'], $datum['kotak_kosong'], $datum['suara_tidak_sah']);
                foreach ($datum['suara_calon'] as $suaraCalon) {
                    $this->insertSuaraCalon($datum['id'], $suaraCalon['id'], $suaraCalon['suara']);
                }
            }

            DB::commit();

            session()->flash('pesan_sukses', 'Berhasil menyimpan data.');
            $this->dispatch('data-stored', status: 'sukses');
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);

            session()->flash('pesan_gagal', 'Gagal menyimpan data.');
            $this->dispatch('data-stored', status: 'gagal');
        }
    }
}
