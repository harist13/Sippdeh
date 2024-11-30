<?php

namespace App\Livewire\Admin;

use App\Models\Calon;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use App\Models\ResumeSuaraPilgubKabupaten;
use App\Models\ResumeSuaraPilgubProvinsi;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PaslonPilgub extends Component
{
    public string $posisi = 'GUBERNUR';
    public bool $withCard;
    public ?int $kabupatenId = null;

    public function mount($kabupatenId = null, $withCard = true)
    {
        $this->kabupatenId = $kabupatenId;
        $this->withCard = $withCard;
    }

    public function render()
    {
        $paslon = $this->getPaslon();
        $suaraSah = $this->getSuaraSah();
        $kotakKosong = $this->getKotakKosong();

        return view('livewire.admin.paslon-pilgub', compact('paslon', 'kotakKosong', 'suaraSah'));
    }

    private function getSuaraSah(): int
    {
        if ($this->kabupatenId) {
            $resume = ResumeSuaraPilgubKabupaten::query()
                ->whereId($this->kabupatenId)
                ->first();
        } else {
            $resume = ResumeSuaraPilgubProvinsi::query()
                ->whereId(session('Admin_provinsi_id'))
                ->first();
        }

        if ($resume) {
            return $resume->suara_sah;
        }

        return 0;
    }

    private function getKotakKosong(): int
    {
        if ($this->kabupatenId) {
            $resume = ResumeSuaraPilgubKabupaten::query()
                ->whereId($this->kabupatenId)
                ->first();
        } else {
            $resume = ResumeSuaraPilgubProvinsi::query()
                ->whereId(session('Admin_provinsi_id'))
                ->first();
        }

        if ($resume) {
            return $resume->kotak_kosong;
        }

        return 0;
    }

    private function getPaslon()
    {
        if ($this->kabupatenId) {
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
                    AND kc.kabupaten_id = ' . $this->kabupatenId . '
                ) + (
                    SELECT COALESCE(SUM(scdp.suara), 0)
                    FROM suara_calon_daftar_pemilih scdp
                    JOIN kecamatan kc ON scdp.kecamatan_id = kc.id
                    WHERE scdp.calon_id = calon.id
                    AND kc.kabupaten_id = ' . $this->kabupatenId . '
                ) AS suara')
            ])
            ->where([
                ['calon.posisi', '=', $this->posisi],
                ['calon.provinsi_id', '=', session('Admin_provinsi_id')]
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
                JOIN kabupaten kb ON kc.kabupaten_id = kb.id
                WHERE sc.calon_id = calon.id
                AND kb.provinsi_id = ' . session('Admin_provinsi_id') . '
            ) + (
                SELECT COALESCE(SUM(scdp.suara), 0)
                FROM suara_calon_daftar_pemilih scdp
                JOIN kecamatan kc ON scdp.kecamatan_id = kc.id
                JOIN kabupaten kb ON kc.kabupaten_id = kb.id
                WHERE scdp.calon_id = calon.id
                AND kb.provinsi_id = ' . session('Admin_provinsi_id') . '
            ) AS suara')
        ])
        ->where([
            ['calon.posisi', '=', $this->posisi],
            ['calon.provinsi_id', '=', session('Admin_provinsi_id')]
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