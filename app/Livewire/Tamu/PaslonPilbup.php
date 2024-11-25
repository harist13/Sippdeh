<?php

namespace App\Livewire\Tamu;

use App\Models\Calon;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PaslonPilbup extends Component
{
    public string $posisi = 'BUPATI';

    public bool $withCard;

    public function mount($withCard = true)
    {
        $this->withCard = $withCard;
    }

    public function render()
    {
        $paslon = $this->getPaslon();
        $suaraSah = $this->getSuaraSahOfTamuKabupaten();
        $kotakKosong = $this->getKotakKosongOfTamuKabupaten();

        return view('livewire.Tamu.paslon-pilbup', compact('paslon', 'kotakKosong', 'suaraSah'));
    }

    private function getSuaraSahOfTamuKabupaten(): int
    {
        $kabupaten = Kabupaten::select([
            'kabupaten.id',
            'kabupaten.nama',
            DB::raw('COALESCE(SUM(suara_calon.suara), 0) AS suara_sah')
        ])
        ->leftJoin('kecamatan', 'kecamatan.kabupaten_id', '=', 'kabupaten.id')
        ->leftJoin('kelurahan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
        ->leftJoin('tps', 'tps.kelurahan_id', '=', 'kelurahan.id')
        ->leftJoin('suara_calon', function ($join) {
            $join->on('suara_calon.tps_id', '=', 'tps.id')
                ->whereIn('suara_calon.calon_id', function ($query) {
                    $query->select('id')
                        ->from('calon')
                        ->where('posisi', $this->posisi);
                });
        })
        ->where('kabupaten.id', session('Tamu_kabupaten_id'))
        ->groupBy('kabupaten.id');
        
        if ($kabupaten->count() > 0) {
            $kabupaten = $kabupaten->first();
            return $kabupaten->suara_sah;
        }

        return 0;
    }

    private function getKotakKosongOfTamuKabupaten(): int
    {
        $kabupaten = Kabupaten::select([
            'kabupaten.id',
            DB::raw('SUM(suara_tps.kotak_kosong) AS kotak_kosong'),
        ])
            ->leftJoin('kecamatan', 'kecamatan.kabupaten_id', '=', 'kabupaten.id')
            ->leftJoin('kelurahan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
            ->leftJoin('tps', 'tps.kelurahan_id', '=', 'kelurahan.id')
            ->leftJoin('suara_tps', 'suara_tps.tps_id', '=', 'tps.id')
            ->where('suara_tps.posisi', $this->posisi)
            ->where('kabupaten.id', session('Tamu_kabupaten_id'))
            ->groupBy('kabupaten.id');
        
        if ($kabupaten->count() > 0) {
            $kabupaten = $kabupaten->first();
            return $kabupaten->kotak_kosong;
        }

        return 0;
    }

    private function getPaslon()
    {
        $builder = Calon::select([
            'calon.id',
            'calon.nama',
            'calon.nama_wakil',
            'calon.foto',
            'calon.kabupaten_id',
            'calon.no_urut',
            DB::raw('COALESCE(SUM(suara_calon.suara), 0) AS suara'),
        ])
            ->leftJoin('suara_calon', 'suara_calon.calon_id', '=', 'calon.id')
            ->where('calon.posisi', $this->posisi)
            ->where('calon.kabupaten_id', session('Tamu_kabupaten_id'))
            ->groupBy('calon.id')
            ->orderBy('calon.no_urut', 'asc');

        return $builder->get();
    }
}