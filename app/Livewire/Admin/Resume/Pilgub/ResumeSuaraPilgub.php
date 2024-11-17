<?php

namespace App\Livewire\Admin\Resume\Pilgub;

use App\Exports\ResumePilgubExport;
use App\Models\Calon;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use App\Models\ResumeSuaraPilgubKabupaten;
use App\Models\ResumeSuaraPilgubKecamatan;
use App\Models\ResumeSuaraPilgubKelurahan;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ResumeSuaraPilgub extends Component
{
    use WithPagination, WithoutUrlPagination;

    public string $posisi = 'GUBERNUR';
    public string $keyword = '';
    public int $perPage = 10;

    public array $selectedKabupaten = [];
    public array $selectedKecamatan = [];
    public array $selectedKelurahan = [];
    public array $includedColumns = ['KABUPATEN', 'KECAMATAN', 'KELURAHAN', 'CALON'];
    public array $partisipasi = ['HIJAU', 'KUNING', 'MERAH'];

    public function mount($wilayah = null)
    {
        if ($wilayah) {
            $kabupaten = Kabupaten::where('nama', 'LIKE', '%' . str_replace('-', ' ', $wilayah) . '%')->first();
            if ($kabupaten) {
                $this->selectedKabupaten = [$kabupaten->id];
            }
        } else {
            $this->selectedKabupaten = Kabupaten::pluck('id')->toArray();
        }
    }

    public function render()
    {
        if (!empty($this->selectedKelurahan)) {
            return $this->getKelurahanTable();
        }

        if (!empty($this->selectedKecamatan)) {
            return $this->getKecamatanTable();
        }
        
        return $this->getKabupatenTable();
    }

    private function getKelurahanTable()
    {
        $paslon = $this->getCalon();
        $suara = $this->getSuaraPerKelurahan();
        $scope = 'kelurahan';

        $suaraSah = $this->getSuaraSahTotal();
        $kotakKosong = $this->getKotakKosongTotal();
        
        return view('admin.resume.pilgub.livewire', compact('suara', 'paslon', 'kotakKosong', 'suaraSah', 'scope'));
    }

    private function getKecamatanTable()
    {
        $paslon = $this->getCalon();
        $suara = $this->getSuaraPerKecamatan();
        $scope = 'kecamatan';
        
        $suaraSah = $this->getSuaraSahTotal();
        $kotakKosong = $this->getKotakKosongTotal();
        
        return view('admin.resume.pilgub.livewire', compact('suara', 'paslon', 'kotakKosong', 'suaraSah', 'scope'));
    }

    private function getKabupatenTable()
    {
        $paslon = $this->getCalon();
        $suara = $this->getSuaraPerKabupaten();
        $scope = 'kabupaten';
        
        $suaraSah = $this->getSuaraSahTotal();
        $kotakKosong = $this->getKotakKosongTotal();
        
        return view('admin.resume.pilgub.livewire', compact('suara', 'paslon', 'kotakKosong', 'suaraSah', 'scope'));
    }

    private function getSuaraPerKelurahan()
    {
        $builder = ResumeSuaraPilgubKelurahan::whereIn('id', $this->selectedKelurahan);

        $this->addPartisipasiFilter($builder);

        if ($this->keyword) {
            $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
        }

        return $builder->paginate($this->perPage);
    }

    private function getSuaraPerKecamatan()
    {
        $builder = ResumeSuaraPilgubKecamatan::whereIn('id', $this->selectedKecamatan);

        $this->addPartisipasiFilter($builder);

        if ($this->keyword) {
            $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
        }

        return $builder->paginate($this->perPage);
    }

    private function getSuaraPerKabupaten()
    {
        $builder = ResumeSuaraPilgubKabupaten::whereIn('id', $this->selectedKabupaten);

        $this->addPartisipasiFilter($builder);

        if ($this->keyword) {
            $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
        }

        return $builder->paginate($this->perPage);
    }

    private function addPartisipasiFilter(Builder $builder)
    {
        $builder->where(function (Builder $builder) {
            if (in_array('MERAH', $this->partisipasi)) {
                $builder->orWhereRaw('partisipasi BETWEEN 0 AND 59.9');
            }
        
            if (in_array('KUNING', $this->partisipasi)) {
                $builder->orWhereRaw('partisipasi BETWEEN 60 AND 79.9');
            }
            
            if (in_array('HIJAU', $this->partisipasi)) {
                $builder->orWhereRaw('partisipasi >= 80');
            }
        });
    }

    private function getSuaraSahTotal(): int
    {
        $result = DB::table('provinsi')
            ->select([
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
            ->first();

        return $result->suara_sah ?? 0;
    }

    private function getKotakKosongTotal(): int
    {
        $result = DB::table('provinsi')
            ->select([
                DB::raw('COALESCE(SUM(suara_tps.kotak_kosong), 0) AS kotak_kosong'),
            ])
            ->leftJoin('kabupaten', 'kabupaten.provinsi_id', '=', 'provinsi.id')
            ->leftJoin('kecamatan', 'kecamatan.kabupaten_id', '=', 'kabupaten.id')
            ->leftJoin('kelurahan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
            ->leftJoin('tps', 'tps.kelurahan_id', '=', 'kelurahan.id')
            ->leftJoin('suara_tps', 'suara_tps.tps_id', '=', 'tps.id')
            ->where('suara_tps.posisi', $this->posisi)
            ->first();

        return $result->kotak_kosong ?? 0;
    }

    private function getCalon()
    {
        return Calon::select([
            'calon.id',
            'calon.nama',
            'calon.nama_wakil',
            'calon.foto',
            'calon.provinsi_id',
            'calon.kabupaten_id',
            DB::raw('COALESCE(SUM(suara_calon.suara), 0) AS suara'),
        ])
            ->leftJoin('suara_calon', 'suara_calon.calon_id', '=', 'calon.id')
            ->where('calon.posisi', $this->posisi)
            ->groupBy('calon.id')
            ->get();
    }

    #[On('reset-filter')] 
    public function resetFilter()
    {
        // Reset to all kabupaten
        $this->selectedKabupaten = Kabupaten::pluck('id')->toArray();
        $this->selectedKecamatan = [];
        $this->selectedKelurahan = [];
        $this->includedColumns = ['KABUPATEN', 'CALON'];
        $this->partisipasi = ['HIJAU', 'KUNING', 'MERAH'];
    }

    #[On('apply-filter')]
    public function applyFilter($selectedKabupaten, $selectedKecamatan, $selectedKelurahan, $includedColumns, $partisipasi)
    {
        $this->selectedKabupaten = $selectedKabupaten;
        $this->selectedKecamatan = $selectedKecamatan;
        $this->selectedKelurahan = $selectedKelurahan;
        $this->includedColumns = $includedColumns;
        $this->partisipasi = $partisipasi;
    }

    public function export()
    {
        $sheet = new ResumePilgubExport(
            $this->keyword,
            $this->selectedKabupaten,
            $this->selectedKecamatan,
            $this->selectedKelurahan,
            $this->includedColumns,
            $this->partisipasi
        );

        return Excel::download($sheet, 'resume-suara-pemilihan-gubernur.xlsx');
    }
}