<?php

namespace App\Livewire\Superadmin;

use App\Models\Calon;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PaslonPilbup extends Component
{
    public string $posisi = 'BUPATI';
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

        return view('livewire.Superadmin.paslon-pilbup', compact('paslon', 'kotakKosong', 'suaraSah'));
    }

    private function getSuaraSahTotal(): int
    {
        $builder = Kabupaten::select([
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
        });
        
        // Filter by kabupaten if specified
        if ($this->kabupatenId) {
            $builder->where('kabupaten.id', $this->kabupatenId);
        } elseif (session('user_wilayah')) {
            $kabupaten = Kabupaten::where('nama', session('user_wilayah'))->first();
            if ($kabupaten) {
                $builder->where('kabupaten.id', $kabupaten->id);
            }
        }

        $builder->groupBy('kabupaten.id');
        
        if ($builder->count() > 0) {
            return $builder->first()->suara_sah;
        }

        return 0;
    }

    private function getKotakKosongTotal(): int
    {
        $builder = Kabupaten::select([
            'kabupaten.id',
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
        } elseif (session('user_wilayah')) {
            $kabupaten = Kabupaten::where('nama', session('user_wilayah'))->first();
            if ($kabupaten) {
                $builder->where('kabupaten.id', $kabupaten->id);
            }
        }

        $builder->groupBy('kabupaten.id');
        
        if ($builder->count() > 0) {
            return $builder->first()->kotak_kosong;
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
            ->where('calon.posisi', $this->posisi);

        // Filter by kabupaten if specified
        if ($this->kabupatenId) {
            $builder->where('calon.kabupaten_id', $this->kabupatenId);
        } elseif (session('user_wilayah')) {
            $kabupaten = Kabupaten::where('nama', session('user_wilayah'))->first();
            if ($kabupaten) {
                $builder->where('calon.kabupaten_id', $kabupaten->id);
            }
        }

        return $builder
            ->groupBy('calon.id')
            ->orderBy('calon.no_urut', 'asc')
            ->get();
    }
}