<?php

namespace App\Livewire\Operator\Resume\Pilgub;

use App\Models\Calon;
use App\Models\RingkasanSuaraTPS;
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
    public array $includedColumns = ['KABUPATEN', 'KECAMATAN', 'KELURAHAN', 'TPS', 'CALON'];
    public array $partisipasi = ['HIJAU', 'KUNING', 'MERAH'];

    public function render()
    {
        $userWilayah = session('user_wilayah');

        $paslon = $this->getCalon();
        $tps = $this->getTPS();

        return view('livewire.operator.resume.pilgub.suara-pilgub', compact('tps', 'paslon'));
    }

    private function getTPS()
    {
        $userWilayah = session('user_wilayah');

        $builder = RingkasanSuaraTPS::whereHas('tps', function(Builder $builder) use ($userWilayah) {
            $builder->whereHas('kelurahan', function (Builder $builder) use ($userWilayah) {
                if (!empty($this->selectedKelurahan)) {
                    $builder->whereIn('id', $this->selectedKelurahan);
                }

                $builder->whereHas('kecamatan', function(Builder $builder) use ($userWilayah) {
                    if (!empty($this->selectedKecamatan)) {
                        $builder->whereIn('id', $this->selectedKecamatan);
                    }

                    $builder->whereHas('kabupaten', function (Builder $builder) use ($userWilayah) {
                        if (!empty($this->selectedKabupaten)) {
                            $builder->whereIn('id', $this->selectedKabupaten);
                        }

                        if (!empty($this->selectedProvinsi)) {
                            $builder->whereHas('provinsi', fn (Builder $builder) => $builder->whereIn('id', $this->selectedProvinsi));
                        }
                        
                        $builder->whereNama($userWilayah);
                    });
                });
            });
        });

        $builder->where(function (Builder $builder) {
            // If 'MERAH' is selected, include records with 'partisipasi' between 0 and 59 or where 'suara' does not exist
            if (in_array('MERAH', $this->partisipasi)) {
                $builder->where(function (Builder $builder) {
                    $builder
                        ->whereHas('suara', function (Builder $builder) {
                            $builder->whereRaw('partisipasi BETWEEN 0 AND 59.9');
                        })
                        ->orWhereDoesntHave('suara');
                });
            }
        
            // Handle 'HIJAU' and 'KUNING' conditions if they are selected
            if (in_array('KUNING', $this->partisipasi)) {
                $builder->orWhereHas('suara', function (Builder $builder) {
                    $builder->whereRaw('partisipasi BETWEEN 60 AND 79.9');
                });
            }
            
            if (in_array('HIJAU', $this->partisipasi)) {
                $builder->orWhereHas('suara', function (Builder $builder) {
                    $builder->whereRaw('partisipasi BETWEEN 80 AND 100');
                });
            }
        });

        if ($this->keyword) {
            $builder->whereHas('tps', function(Builder $builder) {
                $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
                
                $builder->orWhereHas('kelurahan', function (Builder $builder) {
                    $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
                });

                $builder->orWhereHas('kelurahan', function (Builder $builder) {
                    $builder->whereHas('kecamatan', function (Builder $builder) {
                        $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
                    });
                });
            });
        }

        return $builder->paginate($this->perPage);
    }

    private function getCalon()
    {
        $userWilayah = session('user_wilayah');
        $builder = Calon::with('suaraCalon');

        // TODO: Pakai yang ini kalau mau menampilkan semua calon
        // $builder->orWhereHas('provinsi', function (Builder $builder) use ($userWilayah) {
        //     $builder->whereHas('kabupaten', fn (Builder $builder) => $builder->whereNama($userWilayah));
        // });

        // $builder->orWhereHas('kabupaten', fn (Builder $builder) => $builder->whereNama($userWilayah));

        // TODO: Pakai yang ini kalau mau menampilkan calon berdasarkan posisi
        if ($this->posisi == 'GUBERNUR') {
            $builder->whereHas('provinsi', function (Builder $builder) use ($userWilayah) {
                $builder->whereHas('kabupaten', fn (Builder $builder) => $builder->whereNama($userWilayah));
            });
        }

        if ($this->posisi == 'WALIKOTA') {
            $builder->whereHas('kabupaten', fn (Builder $builder) => $builder->whereNama($userWilayah));
        }

        return $builder->get();
    }

    #[On('reset-filter')] 
    public function resetFilter()
    {
        $this->includedColumns = ['KABUPATEN', 'KECAMATAN', 'KELURAHAN', 'TPS', 'CALON'];
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