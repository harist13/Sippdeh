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
        $suaraSah = $this->getSuaraSah();
        $kotakKosong = $this->getKotakKosong();

        return view('livewire.Tamu.paslon-pilgub', compact('paslon', 'kotakKosong', 'suaraSah'));
    }

    private function getKabupatenIdOfTamu(): int
    {
        return session('Tamu_kabupaten_id');
    }
    
    private function getSuaraSah(): int
    {
        $kabupaten = Kabupaten::select([
            'kabupaten.id',
            'kabupaten.nama',
            DB::raw('(
                SELECT COALESCE(SUM(sc.suara), 0)
                FROM suara_calon sc
                JOIN tps t ON sc.tps_id = t.id
                JOIN kelurahan k ON t.kelurahan_id = k.id
                JOIN kecamatan kc ON k.kecamatan_id = kc.id
                JOIN calon c ON sc.calon_id = c.id
                WHERE kc.kabupaten_id = kabupaten.id
                AND c.posisi = "' . $this->posisi . '"
            ) + (
                SELECT COALESCE(SUM(scdp.suara), 0)
                FROM suara_calon_daftar_pemilih scdp
                JOIN kecamatan kc ON scdp.kecamatan_id = kc.id
                JOIN calon c ON scdp.calon_id = c.id
                WHERE kc.kabupaten_id = kabupaten.id
                AND c.posisi = "' . $this->posisi . '"
            ) AS suara_sah')
        ])
        ->where('kabupaten.id', $this->getKabupatenIdOfTamu())
        ->groupBy('kabupaten.id');
        
        if ($kabupaten->count() > 0) {
            $kabupaten = $kabupaten->first();
            return $kabupaten->suara_sah;
        }

        return 0;
    }

    private function getKotakKosong(): int
    {
        $kabupaten = Kabupaten::select([
            'kabupaten.id',
            DB::raw('(
                SELECT COALESCE(SUM(st.kotak_kosong), 0)
                FROM suara_tps st
                JOIN tps t ON st.tps_id = t.id
                JOIN kelurahan k ON t.kelurahan_id = k.id
                JOIN kecamatan kc ON k.kecamatan_id = kc.id
                WHERE kc.kabupaten_id = ' . $this->getKabupatenIdOfTamu() . '
                AND st.posisi = "' . $this->posisi . '"
            ) + (
                SELECT COALESCE(SUM(dp.kotak_kosong), 0)
                FROM daftar_pemilih dp
                JOIN kecamatan kc ON dp.kecamatan_id = kc.id
                WHERE kc.kabupaten_id = ' . $this->getKabupatenIdOfTamu() . '
                AND dp.posisi = "' . $this->posisi . '"
            ) AS kotak_kosong')
        ])
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
            DB::raw('(
                SELECT COALESCE(SUM(sc.suara), 0)
                FROM suara_calon sc
                JOIN tps t ON sc.tps_id = t.id
                JOIN kelurahan k ON t.kelurahan_id = k.id
                JOIN kecamatan kc ON k.kecamatan_id = kc.id
                WHERE sc.calon_id = calon.id
                AND kc.kabupaten_id = ' . session('Tamu_kabupaten_id') . '
            ) + (
                SELECT COALESCE(SUM(scdp.suara), 0)
                FROM suara_calon_daftar_pemilih scdp
                JOIN kecamatan kc ON scdp.kecamatan_id = kc.id
                WHERE scdp.calon_id = calon.id
                AND kc.kabupaten_id = ' . session('Tamu_kabupaten_id') . '
            ) AS suara')
        ])
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
