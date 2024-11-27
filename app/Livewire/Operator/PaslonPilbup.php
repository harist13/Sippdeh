<?php

namespace App\Livewire\Operator;

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
        $suaraSah = $this->getSuaraSahOfOperatorKabupaten();
        $kotakKosong = $this->getKotakKosongOfOperatorKabupaten();

        return view('livewire.operator.paslon-pilbup', compact('paslon', 'kotakKosong', 'suaraSah'));
    }

    private function getSuaraSahOfOperatorKabupaten(): int
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
                WHERE kc.kabupaten_id = ' . session('operator_kabupaten_id') . '
                AND c.posisi = "' . $this->posisi . '"
            ) + (
                SELECT COALESCE(SUM(scdp.suara), 0)
                FROM suara_calon_daftar_pemilih scdp
                JOIN calon c ON scdp.calon_id = c.id
                JOIN kecamatan kc ON scdp.kecamatan_id = kc.id
                WHERE kc.kabupaten_id = ' . session('operator_kabupaten_id') . '
                AND c.posisi = "' . $this->posisi . '"
            ) AS suara_sah')
        ])
        ->where('kabupaten.id', session('operator_kabupaten_id'))
        ->groupBy('kabupaten.id');
        
        if ($kabupaten->count() > 0) {
            $kabupaten = $kabupaten->first();
            return $kabupaten->suara_sah;
        }

        return 0;
    }

    private function getKotakKosongOfOperatorKabupaten(): int
    {
        $kabupaten = Kabupaten::select([
            'kabupaten.id',
            DB::raw('(
                SELECT COALESCE(SUM(st.kotak_kosong), 0)
                FROM suara_tps st
                JOIN tps t ON st.tps_id = t.id
                JOIN kelurahan k ON t.kelurahan_id = k.id
                JOIN kecamatan kc ON k.kecamatan_id = kc.id
                WHERE kc.kabupaten_id = ' . session('operator_kabupaten_id') . '
                AND st.posisi = "' . $this->posisi . '"
            ) + (
                SELECT COALESCE(SUM(dp.kotak_kosong), 0)
                FROM daftar_pemilih dp
                JOIN kecamatan kc ON dp.kecamatan_id = kc.id
                WHERE kc.kabupaten_id = ' . session('operator_kabupaten_id') . '
                AND dp.posisi = "' . $this->posisi . '"
            ) AS kotak_kosong')
        ])
        ->where('kabupaten.id', session('operator_kabupaten_id'))
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
            'calon.kabupaten_id',
            'calon.no_urut',
            DB::raw('(
                SELECT COALESCE(SUM(sc.suara), 0)
                FROM suara_calon sc
                JOIN tps t ON sc.tps_id = t.id
                JOIN kelurahan k ON t.kelurahan_id = k.id
                JOIN kecamatan kc ON k.kecamatan_id = kc.id
                WHERE sc.calon_id = calon.id
                AND kc.kabupaten_id = ' . session('operator_kabupaten_id') . '
            ) + (
                SELECT COALESCE(SUM(scdp.suara), 0)
                FROM suara_calon_daftar_pemilih scdp
                JOIN kecamatan kc ON scdp.kecamatan_id = kc.id
                WHERE scdp.calon_id = calon.id
                AND kc.kabupaten_id = ' . session('operator_kabupaten_id') . '
            ) AS suara')
        ])
        ->where('calon.posisi', $this->posisi)
        ->where('calon.kabupaten_id', session('operator_kabupaten_id'))
        ->groupBy('calon.id')
        ->orderBy('calon.no_urut', 'asc')
        ->get();
    }
}