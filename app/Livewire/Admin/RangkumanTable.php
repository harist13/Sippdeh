<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Calon;
use App\Models\RingkasanSuaraTPS;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Support\Collection;
use App\Exports\RangkumanExport;
use Maatwebsite\Excel\Facades\Excel;

class RangkumanTable extends Component
{
    use WithPagination;

    public $search = '';
    public $itemsPerPage = 10;
    public $kabupaten_id = '';
    public $kecamatan_id = '';
    public $kelurahan_id = '';
    public $partisipasi = [];
    public $showFilterModal = false;
    public $showExportModal = false;
    public $isLoading = false;
    public $exportKabupatenId = '';

    // Collections for dropdowns
    public $kabupatens;
    public $kecamatans;
    public $kelurahans;
    public $paslon;
    public $hiddenColumns = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'kabupaten_id' => ['except' => ''],
        'kecamatan_id' => ['except' => ''],
        'kelurahan_id' => ['except' => ''],
        'partisipasi' => ['except' => []],
        'hiddenColumns' => ['except' => []]
    ];

    public function mount()
    {
        $this->kabupatens = Kabupaten::orderBy('nama')->get();
        $this->kecamatans = collect();
        $this->kelurahans = collect();
        $this->paslon = Calon::where('posisi', 'Gubernur')->get();
    }

    // Reset Pagination when filters change
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedKabupatenId()
    {
        $this->kecamatans = $this->kabupaten_id ? 
            Kecamatan::where('kabupaten_id', $this->kabupaten_id)->orderBy('nama')->get() : 
            collect();
        $this->kecamatan_id = '';
        $this->kelurahan_id = '';
        $this->kelurahans = collect();
        $this->resetPage();
    }

    public function updatedKecamatanId()
    {
        $this->kelurahans = $this->kecamatan_id ? 
            Kelurahan::where('kecamatan_id', $this->kecamatan_id)->orderBy('nama')->get() : 
            collect();
        $this->kelurahan_id = '';
        $this->resetPage();
    }

    public function resetFilter()
    {
        $this->reset(['kabupaten_id', 'kecamatan_id', 'kelurahan_id', 'partisipasi']);
        $this->kecamatans = collect();
        $this->kelurahans = collect();
    }

    public function export()
    {
        $filename = 'rangkuman-suara-' . date('Y-m-d-His') . '.xlsx';
        
        return Excel::download(
            new RangkumanExport([
                'kabupaten_id' => $this->exportKabupatenId,
                'search' => $this->search,
                'partisipasi' => $this->partisipasi
            ]), 
            $filename
        );
    }

    public function getSummaryDataProperty()
    {
        $query = RingkasanSuaraTPS::select(
            'ringkasan_suara_tps.*',
            'tps.nama as tps_nama',
            'tps.kelurahan_id',
            'kelurahan.nama as kelurahan_nama',
            'kelurahan.kecamatan_id',
            'kecamatan.nama as kecamatan_nama',
            'kecamatan.kabupaten_id',
            'kabupaten.nama as kabupaten_nama'
        )
        ->join('tps', 'ringkasan_suara_tps.id', '=', 'tps.id')
        ->join('kelurahan', 'tps.kelurahan_id', '=', 'kelurahan.id')
        ->join('kecamatan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
        ->join('kabupaten', 'kecamatan.kabupaten_id', '=', 'kabupaten.id')
        ->with(['suara', 'suaraCalon']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('kabupaten.nama', 'LIKE', "%{$this->search}%")
                  ->orWhere('kecamatan.nama', 'LIKE', "%{$this->search}%")
                  ->orWhere('kelurahan.nama', 'LIKE', "%{$this->search}%");
            });
        }

        if ($this->kabupaten_id) {
            $query->where('kabupaten.id', $this->kabupaten_id);
        }

        if ($this->kecamatan_id) {
            $query->where('kecamatan.id', $this->kecamatan_id);
        }

        if ($this->kelurahan_id) {
            $query->where('kelurahan.id', $this->kelurahan_id);
        }

        if (!empty($this->partisipasi)) {
            $query->where(function($q) {
                foreach ($this->partisipasi as $value) {
                    switch ($value) {
                        case 'hijau':
                            $q->orWhere('partisipasi', '>=', 70);
                            break;
                        case 'kuning':
                            $q->orWhereBetween('partisipasi', [50, 69.99]);
                            break;
                        case 'merah':
                            $q->orWhere('partisipasi', '<', 50);
                            break;
                    }
                }
            });
        }

        return $query->paginate($this->itemsPerPage);
    }

    public function render()
    {
        return view('livewire.admin.rangkuman-table', [
            'summaryData' => $this->summaryData
        ]);
    }
}