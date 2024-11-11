<?php

namespace App\Livewire\Operator\Resume\Pilgub;

use App\Models\Calon;
use App\Models\Kabupaten;
use App\Models\ResumeSuaraPerKelurahan;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Database\Eloquent\Builder;

class SuaraPilgub extends Component
{
    use WithPagination, WithoutUrlPagination;

    public string $posisi = 'GUBERNUR';

    public string $keyword = '';

    public int $perPage = 10;

    public array $selectedProvinsi = [];
    public array $selectedKabupaten = [];
    public array $selectedKecamatan = [];
    public array $selectedKelurahan = [];
    public array $includedColumns = ['PROVINSI', 'KABUPATEN', 'KECAMATAN', 'KELURAHAN', 'TPS', 'CALON'];
    public array $partisipasi = ['HIJAU', 'KUNING', 'MERAH'];

    public function mount()
    {
        $userWilayah = session('user_wilayah');
        
        $kabupaten = Kabupaten::whereNama($userWilayah)->first();
        $provinsi = $kabupaten->provinsi;

        $this->selectedProvinsi[] = $provinsi->id;
        $this->selectedKabupaten[] = $kabupaten->id;
    }

    public function render()
    {
        $paslon = $this->getCalon();
        $suara = $this->getSuaraPerKelurahan();
        
        return view('livewire.operator.resume.pilgub.suara-pilgub', compact('suara', 'paslon'));
    }

    private function getSuaraPerKelurahan()
    {
        $builder = ResumeSuaraPerKelurahan::whereIn('id', $this->selectedKelurahan);

        $builder->where(function (Builder $builder) {
            if (in_array('MERAH', $this->partisipasi)) {
                $builder->orWhereRaw('partisipasi BETWEEN 0 AND 59.9');
            }
        
            if (in_array('KUNING', $this->partisipasi)) {
                $builder->orWhereRaw('partisipasi BETWEEN 60 AND 79.9');
            }
            
            if (in_array('HIJAU', $this->partisipasi)) {
                $builder->orWhereRaw('partisipasi BETWEEN 80 AND 100');
            }
        });

        if ($this->keyword) {
            $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
        }

        return $builder->paginate($this->perPage);
    }

    private function getCalon()
    {
        $builder = Calon::wherePosisi($this->posisi);
        
        if (!empty($this->selectedProvinsi)) {
            $builder->whereHas('provinsi', function (Builder $query) {
                $query->whereIn('id', $this->selectedProvinsi);
            });
        }

        return $builder->get();
    }

    #[On('reset-filter')] 
    public function resetFilter()
    {
        $this->includedColumns = ['PROVINSI', 'KABUPATEN', 'KECAMATAN', 'KELURAHAN', 'TPS', 'CALON'];
        $this->selectedProvinsi = [];
        $this->selectedKabupaten = [];
        $this->selectedKecamatan = [];
        $this->selectedKelurahan = [];
        $this->partisipasi = ['HIJAU', 'KUNING', 'MERAH'];
    }

    #[On('apply-filter')]
    public function applyFilter($includedColumns, $selectedProvinsi, $selectedKabupaten, $selectedKecamatan, $selectedKelurahan, $partisipasi)
    {
        $this->includedColumns = $includedColumns;
        $this->selectedProvinsi = $selectedProvinsi;
        $this->selectedKabupaten = $selectedKabupaten;
        $this->selectedKecamatan = $selectedKecamatan;
        $this->selectedKelurahan = $selectedKelurahan;
        $this->partisipasi = $partisipasi;
    }
}