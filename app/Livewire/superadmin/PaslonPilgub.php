<?php

namespace App\Livewire\superadmin;

use App\Models\Calon;
use App\Models\Kabupaten;
use App\Models\Provinsi;
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
        $suaraSah = $this->getSuaraSahTotal();
        $kotakKosong = $this->getKotakKosongTotal();

        return view('livewire.superadmin.paslon-pilgub', compact('paslon', 'kotakKosong', 'suaraSah'));
    }

    private function getSuaraSahTotal(): int
    {
        $builder = Kabupaten::select([
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
        });
        
        // Filter by kabupaten if specified
        if ($this->kabupatenId) {
            $builder->where('kabupaten.id', $this->kabupatenId);
        }

        return $builder->value('suara_sah') ?? 0;
    }

    private function getKotakKosongTotal(): int
    {
        $builder = Kabupaten::select([
            DB::raw('COALESCE(SUM(suara_tps.kotak_kosong), 0) AS kotak_kosong'),
        ])
            ->leftJoin('kecamatan', 'kecamatan.kabupaten_id', '=', 'kabupaten.id')
            ->leftJoin('kelurahan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
            ->leftJoin('tps', 'tps.kelurahan_id', '=', 'kelurahan.id')
            ->leftJoin('suara_tps', 'suara_tps.tps_id', '=', 'tps.id')
            ->where('suara_tps.posisi', $this->posisi);

        // Filter by kabupaten if specified
        if ($this->kabupatenId) {
            $builder->where('kabupaten.id', $this->kabupatenId);
        }

        return $builder->value('kotak_kosong') ?? 0;
    }

    private function getPaslon()
    {
        $builder = Calon::select([
            'calon.id',
            'calon.nama',
            'calon.nama_wakil',
            'calon.foto',
            'calon.provinsi_id',
            'calon.no_urut',
            DB::raw('COALESCE(SUM(suara_calon.suara), 0) AS suara'),
        ])
            ->leftJoin('suara_calon', 'suara_calon.calon_id', '=', 'calon.id')
            ->where('calon.posisi', $this->posisi)
            ->groupBy('calon.id')
            ->orderBy('calon.no_urut', 'asc');

        return $builder->get();
    }
}