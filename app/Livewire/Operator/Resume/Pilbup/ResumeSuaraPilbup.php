<?php

namespace App\Livewire\Operator\Resume\Pilbup;

use App\Exports\ResumePilbupExport;
use App\Models\Calon;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\ResumeSuaraPilbupKecamatan;
use App\Models\ResumeSuaraPilbupKelurahan;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ResumeSuaraPilbup extends Component
{
    use WithPagination, WithoutUrlPagination;

    public string $posisi = 'BUPATI';

    public string $keyword = '';

    public int $perPage = 10;

    public array $selectedKecamatan = [];
    public array $selectedKelurahan = [];
    public array $includedColumns = ['KABUPATEN', 'KECAMATAN', 'CALON'];
    public array $partisipasi = ['HIJAU', 'KUNING', 'MERAH'];

    public function mount()
    {
        $this->selectedKecamatan = Kecamatan::query()
            ->whereHas('kabupaten', fn (Builder $builder) => $builder->whereNama(session('user_wilayah')))
            ->get()
            ->pluck('id')
            ->all();
        
        $this->includedColumns = ['KABUPATEN', 'KECAMATAN', 'CALON'];
    }

    public function render()
    {
        if (!empty($this->selectedKelurahan)) {
            return $this->getKelurahanTable();
        }

        return $this->getKecamatanTable();
    }

    private function getKelurahanTable()
    {
        $paslon = $this->getCalon();
        $suara = $this->getSuaraPerKelurahan();
        $scope = 'kelurahan';
        
        $suaraSah = $this->getSuaraSahOfOperatorKabupaten();
        $kotakKosong = $this->getKotakKosongOfOperatorKabupaten();
        
        return view('operator.resume.pilbup.livewire', compact('suara', 'paslon', 'kotakKosong', 'suaraSah', 'scope'));
    }

    private function getKecamatanTable()
    {
        $paslon = $this->getCalon();
        $suara = $this->getSuaraPerKecamatan();
        $scope = 'kecamatan';
        
        $suaraSah = $this->getSuaraSahOfOperatorKabupaten();
        $kotakKosong = $this->getKotakKosongOfOperatorKabupaten();
        
        return view('operator.resume.pilbup.livewire', compact('suara', 'paslon', 'kotakKosong', 'suaraSah', 'scope'));
    }

    private function getSuaraPerKelurahan()
    {
        $builder = ResumeSuaraPilbupKelurahan::whereIn('id', $this->selectedKelurahan);

        $this->addPartisipasiFilter($builder);

        if ($this->keyword) {
            $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
        }

        return $builder->paginate($this->perPage);
    }

    private function getSuaraPerKecamatan()
    {
        $builder = ResumeSuaraPilbupKecamatan::whereIn('id', $this->selectedKecamatan);

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
                $builder->orWhereRaw('partisipasi BETWEEN 80 AND 100');
            }
        });
    }
    
    private function getKabupatenIdOfOperator(): int
    {
        $kabupaten = Kabupaten::whereNama(session('user_wilayah'));

        if ($kabupaten->count() > 0) {
            $kabupaten = $kabupaten->first();
            return $kabupaten->id;
        }

        return 0;
    }

    private function getSuaraSahOfOperatorKabupaten(): int
    {
        $kabupaten = Kabupaten::select([
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
        })
        ->where('kabupaten.id', $this->getKabupatenIdOfOperator())
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
            DB::raw('SUM(suara_tps.kotak_kosong) AS kotak_kosong'),
        ])
            ->leftJoin('kecamatan', 'kecamatan.kabupaten_id', '=', 'kabupaten.id')
            ->leftJoin('kelurahan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
            ->leftJoin('tps', 'tps.kelurahan_id', '=', 'kelurahan.id')
            ->leftJoin('suara_tps', 'suara_tps.tps_id', '=', 'tps.id')
            ->where('suara_tps.posisi', $this->posisi)
            ->where('kabupaten.id', $this->getKabupatenIdOfOperator())
            ->groupBy('kabupaten.id');
        
        if ($kabupaten->count() > 0) {
            $kabupaten = $kabupaten->first();
            return $kabupaten->kotak_kosong;
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
            'calon.kabupaten_id',
            DB::raw('COALESCE(SUM(suara_calon.suara), 0) AS suara'),
        ])
            ->leftJoin('suara_calon', 'suara_calon.calon_id', '=', 'calon.id')
            ->where('calon.posisi', $this->posisi)
            ->where('calon.kabupaten_id', $this->getKabupatenIdOfOperator())
            ->groupBy('calon.id');

        return $builder->get();
    }

    #[On('reset-filter')] 
    public function resetFilter()
    {
        $this->selectedKecamatan = [];
        $this->selectedKelurahan = [];
        $this->includedColumns = ['KABUPATEN', 'KECAMATAN', 'CALON'];
        $this->partisipasi = ['HIJAU', 'KUNING', 'MERAH'];
    }

    #[On('apply-filter')]
    public function applyFilter($selectedKecamatan, $selectedKelurahan, $includedColumns, $partisipasi)
    {
        $this->selectedKecamatan = $selectedKecamatan;
        $this->selectedKelurahan = $selectedKelurahan;
        $this->includedColumns = $includedColumns;
        $this->partisipasi = $partisipasi;
    }

    public function export()
    {
        $sheet = new ResumePilbupExport(
            $this->selectedKecamatan,
            $this->selectedKelurahan,
            $this->includedColumns,
            $this->partisipasi
        );

        return Excel::download($sheet, 'resume-suara-pemilihan-bupati.xlsx');
    }
}
