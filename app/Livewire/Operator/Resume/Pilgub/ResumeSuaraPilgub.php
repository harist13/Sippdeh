<?php

namespace App\Livewire\Operator\Resume\Pilgub;

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

    public function mount()
    {
        $this->fillSelectedKabupaten();
        $this->includedColumns = ['KABUPATEN', 'CALON'];
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

        $suaraSah = $this->getSuaraSahOfOperatorProvinsi();
        $kotakKosong = $this->getKotakKosongOfOperatorProvinsi();
        
        return view('operator.resume.pilgub.livewire', compact('suara', 'paslon', 'kotakKosong', 'suaraSah', 'scope'));
    }

    private function getKecamatanTable()
    {
        $paslon = $this->getCalon();
        $suara = $this->getSuaraPerKecamatan();
        $scope = 'kecamatan';
        
        $suaraSah = $this->getSuaraSahOfOperatorProvinsi();
        $kotakKosong = $this->getKotakKosongOfOperatorProvinsi();
        
        return view('operator.resume.pilgub.livewire', compact('suara', 'paslon', 'kotakKosong', 'suaraSah', 'scope'));
    }

    private function getKabupatenTable()
    {
        $paslon = $this->getCalon();
        $suara = $this->getSuaraPerKabupaten();
        $scope = 'kabupaten';
        
        $suaraSah = $this->getSuaraSahOfOperatorProvinsi();
        $kotakKosong = $this->getKotakKosongOfOperatorProvinsi();
        
        return view('operator.resume.pilgub.livewire', compact('suara', 'paslon', 'kotakKosong', 'suaraSah', 'scope'));
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

    private function getCalon()
    {
        $builder = Calon::select([
            'calon.id',
            'calon.nama',
            'calon.nama_wakil',
            'calon.foto',
            'calon.provinsi_id',
            'calon.kabupaten_id',
            DB::raw('SUM(suara_calon.suara) AS suara'),
        ])
            ->leftJoin('suara_calon', 'suara_calon.calon_id', '=', 'calon.id')
            ->where('calon.posisi', $this->posisi)
            ->where('calon.provinsi_id', $this->getProvinsiIdOfOperator())
            ->groupBy('calon.id');

        return $builder->get();
    }

    private function fillSelectedKabupaten()
    {
        $this->selectedKabupaten = Kabupaten::query()
            ->whereHas('provinsi', function (Builder $builder) {
                $builder->whereHas('kabupaten', fn (Builder $builder) => $builder->whereNama(session('user_wilayah')));
            })
            ->pluck('id')
            ->all();
    }

    #[On('reset-filter')] 
    public function resetFilter()
    {
        $this->fillSelectedKabupaten();

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