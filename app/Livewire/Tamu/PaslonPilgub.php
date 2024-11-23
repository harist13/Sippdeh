<?php

namespace App\Livewire\Tamu;

use App\Models\Calon;
use App\Models\Kabupaten;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PaslonPilgub extends Component
{
    public string $posisi = 'GUBERNUR';

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

        return view('livewire.Tamu.paslon-pilgub', compact('paslon', 'kotakKosong', 'suaraSah'));
    }

    private function getKabupatenIdOfTamu(): int
    {
        return session('Tamu_kabupaten_id');
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
        ->where('kabupaten.id', $this->getKabupatenIdOfTamu())
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
            ->where('kabupaten.id', $this->getKabupatenIdOfTamu())
            ->groupBy('kabupaten.id');
        
        if ($kabupaten->count() > 0) {
            $kabupaten = $kabupaten->first();
            return $kabupaten->kotak_kosong;
        }

        return 0;
    }

    private function getPaslon()
    {
        return Calon::select([
            'calon.id',
            'calon.nama',
            'calon.nama_wakil',
            'calon.foto',
            'calon.provinsi_id',
            'calon.kabupaten_id',
            'calon.no_urut',
            DB::raw('COALESCE(SUM(suara_calon.suara), 0) AS suara'),
        ])
        ->join('tps', function($join) {
            $join->join('kelurahan', 'kelurahan.id', '=', 'tps.kelurahan_id')
                ->join('kecamatan', 'kecamatan.id', '=', 'kelurahan.kecamatan_id')
                ->where('kecamatan.kabupaten_id', session('Tamu_kabupaten_id'));
        })
        ->leftJoin('suara_calon', function($join) {
            $join->on('suara_calon.calon_id', '=', 'calon.id')
                ->on('suara_calon.tps_id', '=', 'tps.id');
        })
        ->where([
            ['calon.posisi', '=', $this->posisi],
            ['calon.provinsi_id', '=', session('Tamu_provinsi_id')]
        ])
        ->groupBy([
            'calon.id',
            'calon.nama',
            'calon.nama_wakil',
            'calon.foto',
            'calon.provinsi_id',
            'calon.kabupaten_id',
            'calon.no_urut'
        ])
        ->get();
    }
}
