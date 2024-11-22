<?php

namespace App\Livewire\Operator;

use App\Models\Calon;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PaslonPilgub extends Component
{
    public string $posisi = 'GUBERNUR';

    public function render()
    {
        $paslon = $this->getPaslon();
        $suaraSah = $this->getSuaraSahOfOperatorProvinsi();
        $kotakKosong = $this->getKotakKosongOfOperatorProvinsi();

        return view('livewire.operator.paslon-pilgub', compact('paslon', 'kotakKosong', 'suaraSah'));
    }

    private function getProvinsiIdOfOperator(): int
    {
        $kabupaten = Kabupaten::whereNama(session('user_wilayah'));

        if ($kabupaten->count() > 0) {
            $kabupaten = $kabupaten->first();
            return $kabupaten->provinsi_id;
        }

        return 0;
    }

    private function getSuaraSahOfOperatorProvinsi(): int
    {
        $provinsi = Provinsi::select([
            'provinsi.id',
            'provinsi.nama',
            DB::raw('COALESCE(SUM(suara_calon.suara), 0) AS suara_sah')
        ])
        ->leftJoin('kabupaten', 'kabupaten.provinsi_id', '=', 'provinsi.id')
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
        ->where('provinsi.id', $this->getProvinsiIdOfOperator())
        ->groupBy('provinsi.id');
        
        if ($provinsi->count() > 0) {
            $provinsi = $provinsi->first();
            return $provinsi->suara_sah;
        }

        return 0;
    }

    private function getKotakKosongOfOperatorProvinsi(): int
    {
        $provinsi = Provinsi::select([
            'provinsi.id',
            DB::raw('SUM(suara_tps.kotak_kosong) AS kotak_kosong'),
        ])
            ->leftJoin('kabupaten', 'kabupaten.provinsi_id', '=', 'provinsi.id')
            ->leftJoin('kecamatan', 'kecamatan.kabupaten_id', '=', 'kabupaten.id')
            ->leftJoin('kelurahan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
            ->leftJoin('tps', 'tps.kelurahan_id', '=', 'kelurahan.id')
            ->leftJoin('suara_tps', 'suara_tps.tps_id', '=', 'tps.id')
            ->where('suara_tps.posisi', $this->posisi)
            ->where('provinsi.id', $this->getProvinsiIdOfOperator())
            ->groupBy('provinsi.id');
        
        if ($provinsi->count() > 0) {
            $provinsi = $provinsi->first();
            return $provinsi->kotak_kosong;
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
            'calon.provinsi_id',
            'calon.kabupaten_id',
            'calon.no_urut',
            DB::raw('SUM(suara_calon.suara) AS suara'),
        ])
            ->leftJoin('suara_calon', 'suara_calon.calon_id', '=', 'calon.id')
            ->where('calon.posisi', $this->posisi)
            ->where('calon.provinsi_id', $this->getProvinsiIdOfOperator())
            ->groupBy('calon.id')
            ->orderBy('calon.no_urut', 'asc');

        return $builder->get();
    }
}
