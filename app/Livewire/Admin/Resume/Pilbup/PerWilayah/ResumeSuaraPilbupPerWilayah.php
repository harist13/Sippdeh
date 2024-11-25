<?php

namespace App\Livewire\Admin\Resume\Pilbup\PerWilayah;

use App\Exports\ResumePilbupExport;
use App\Models\Calon;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\ResumeSuaraPilbupKecamatan;
use App\Models\ResumeSuaraPilbupKelurahan;
use App\Traits\SortResumeColumns;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ResumeSuaraPilbupPerWilayah extends Component
{
    use SortResumeColumns, WithPagination, WithoutUrlPagination;

    public string $posisi = 'BUPATI';
    public string $keyword = '';
    public int $perPage = 10;
    public ?int $kabupatenId = null;

    public array $selectedKecamatan = [];
    public array $selectedKelurahan = [];
    public array $includedColumns = ['KABUPATEN/KOTA', 'KECAMATAN', 'CALON'];
    public array $partisipasi = ['HIJAU', 'KUNING', 'MERAH'];

    public function mount($kabupatenId = null)
    {
        $this->kabupatenId = $kabupatenId;
        
        $this->selectedKecamatan = Kecamatan::query()
            ->where('kabupaten_id', $this->kabupatenId)
            ->pluck('id')
            ->all();
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
        
        $suaraSah = $this->getSuaraSahTotal();
        $kotakKosong = $this->getKotakKosongTotal();
        
        return view('admin.resume.pilbup.per-wilayah.livewire', 
            compact('suara', 'paslon', 'kotakKosong', 'suaraSah', 'scope')
        );
    }

    private function getKecamatanTable()
    {
        $paslon = $this->getCalon();
        $suara = $this->getSuaraPerKecamatan();
        $scope = 'kecamatan';
        
        $suaraSah = $this->getSuaraSahTotal();
        $kotakKosong = $this->getKotakKosongTotal();
        
        return view('admin.resume.pilbup.per-wilayah.livewire', 
            compact('suara', 'paslon', 'kotakKosong', 'suaraSah', 'scope')
        );
    }

    private function getSuaraPerKelurahan()
    {
        $builder = ResumeSuaraPilbupKelurahan::query()
            ->selectRaw('
                resume_suara_pilbup_kelurahan.id,
                resume_suara_pilbup_kelurahan.nama,
                resume_suara_pilbup_kelurahan.kecamatan_id,
                resume_suara_pilbup_kelurahan.dpt,
                resume_suara_pilbup_kelurahan.kotak_kosong,
                resume_suara_pilbup_kelurahan.suara_sah,
                resume_suara_pilbup_kelurahan.suara_tidak_sah,
                resume_suara_pilbup_kelurahan.suara_masuk,
                resume_suara_pilbup_kelurahan.abstain,
                resume_suara_pilbup_kelurahan.partisipasi
            ')
            ->whereIn('resume_suara_pilbup_kelurahan.id', $this->selectedKelurahan)
            ->whereHas('kecamatan', function($query) {
                $query->where('kabupaten_id', $this->kabupatenId);
            });

        $this->addPartisipasiFilter($builder);
        $this->sortColumns($builder);
        $this->sortResumeSuaraPilbupKelurahanPaslon($builder);
        $this->sortResumeSuaraKotakKosong($builder);

        if ($this->keyword) {
            $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
        }

        return $builder->paginate($this->perPage);
    }

    private function getSuaraPerKecamatan()
    {
        $builder = ResumeSuaraPilbupKecamatan::query()
            ->selectRaw('
                resume_suara_pilbup_kecamatan.id,
                resume_suara_pilbup_kecamatan.nama,
                resume_suara_pilbup_kecamatan.kabupaten_id,
                resume_suara_pilbup_kecamatan.dpt,
                resume_suara_pilbup_kecamatan.kotak_kosong,
                resume_suara_pilbup_kecamatan.suara_sah,
                resume_suara_pilbup_kecamatan.suara_tidak_sah,
                resume_suara_pilbup_kecamatan.suara_masuk,
                resume_suara_pilbup_kecamatan.abstain,
                resume_suara_pilbup_kecamatan.partisipasi
            ')
            ->where('kabupaten_id', $this->kabupatenId)
            ->whereIn('resume_suara_pilbup_kecamatan.id', $this->selectedKecamatan);

        $this->addPartisipasiFilter($builder);
        $this->sortColumns($builder);
        $this->sortResumeSuaraPilbupKecamatanPaslon($builder);
        $this->sortResumeSuaraKotakKosong($builder);

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
        return DB::table('kabupaten')
            ->where('kabupaten.id', $this->kabupatenId)
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
            ->value(DB::raw('COALESCE(SUM(suara_calon.suara), 0)'));
    }

    private function getKotakKosongTotal(): int
    {
        return DB::table('kabupaten')
            ->where('kabupaten.id', $this->kabupatenId)
            ->leftJoin('kecamatan', 'kecamatan.kabupaten_id', '=', 'kabupaten.id')
            ->leftJoin('kelurahan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
            ->leftJoin('tps', 'tps.kelurahan_id', '=', 'kelurahan.id')
            ->leftJoin('suara_tps', 'suara_tps.tps_id', '=', 'tps.id')
            ->where('suara_tps.posisi', $this->posisi)
            ->value(DB::raw('COALESCE(SUM(suara_tps.kotak_kosong), 0)'));
    }

    private function getCalon()
    {
        return Calon::select([
            'calon.id',
            'calon.nama',
            'calon.nama_wakil',
            'calon.foto',
            'calon.kabupaten_id',
            DB::raw('COALESCE(SUM(suara_calon.suara), 0) AS suara'),
        ])
            ->leftJoin('suara_calon', 'suara_calon.calon_id', '=', 'calon.id')
            ->where('calon.posisi', $this->posisi)
            ->where('calon.kabupaten_id', $this->kabupatenId)
            ->groupBy('calon.id')
            ->get();
    }

    #[On('reset-filter')] 
    public function resetFilter()
    {
        $this->selectedKecamatan = Kecamatan::where('kabupaten_id', $this->kabupatenId)
            ->pluck('id')
            ->all();
        $this->selectedKelurahan = [];
        $this->includedColumns = ['KABUPATEN/KOTA', 'KECAMATAN', 'CALON'];
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
            $this->partisipasi,
            $this->kabupatenId
        );

        return Excel::download($sheet, 'resume-suara-pemilihan-bupati.xlsx');
    }
}