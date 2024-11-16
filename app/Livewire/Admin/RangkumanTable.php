<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Calon;
use App\Models\ResumeSuaraTPS;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Support\Collection;
use App\Exports\RangkumanExport;
use Maatwebsite\Excel\Facades\Excel;

class RangkumanTable extends Component
{
    use WithPagination;

    // Existing properties
    public $search = '';
    public $itemsPerPage = 10;
    public $kabupaten_ids = [];
    public $kecamatan_ids = [];
    public $kelurahan_ids = [];
    public $partisipasi = [];
    public $showFilterModal = false;
    public $showExportModal = false;
    public $isLoading = false;
    public $exportKabupatenId = '';

    // New search properties for dropdowns
    public $searchKabupaten = '';
    public $searchKecamatan = '';
    public $searchKelurahan = '';

    // Collections for dropdowns
    public $kabupatens;
    public $kecamatans;
    public $kelurahans;
    public $paslon;
    
    // Changed default shown columns (removed kecamatan and kelurahan)
    public $shownColumns = ['kabupaten', 'calon', 'abstain'];

    // Add new properties to queryString if needed
    protected $queryString = [
        'search' => ['except' => ''],
        'kabupaten_ids' => ['except' => []],
        'kecamatan_ids' => ['except' => []],
        'kelurahan_ids' => ['except' => []],
        'partisipasi' => ['except' => []],
        'shownColumns' => ['except' => ['kabupaten', 'calon', 'abstain']]
    ];

    public function mount()
    {
        $this->kabupatens = Kabupaten::orderBy('nama')->get();
        $this->kecamatans = collect();
        $this->kelurahans = collect();
        $this->paslon = Calon::where('posisi', 'Gubernur')->get();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedKabupatenIds()
    {
        if (!empty($this->kabupaten_ids)) {
            $this->kecamatans = Kecamatan::whereIn('kabupaten_id', $this->kabupaten_ids)
                ->orderBy('nama')
                ->get();
        } else {
            $this->kecamatans = collect();
        }
        $this->kecamatan_ids = [];
        $this->kelurahan_ids = [];
        $this->kelurahans = collect();
        $this->resetPage();
    }

    public function updatedKecamatanIds()
    {
        if (!empty($this->kecamatan_ids)) {
            $this->kelurahans = Kelurahan::whereIn('kecamatan_id', $this->kecamatan_ids)
                ->orderBy('nama')
                ->get();
        } else {
            $this->kelurahans = collect();
        }
        $this->kelurahan_ids = [];
        $this->resetPage();
    }

    public function updatedKelurahanIds()
    {
        $this->resetPage();
    }

    public function resetFilter()
    {
        $this->reset([
            'kabupaten_ids', 
            'kecamatan_ids', 
            'kelurahan_ids', 
            'partisipasi',
            'searchKabupaten',
            'searchKecamatan',
            'searchKelurahan'
        ]);
        // Reset shownColumns ke nilai default
        $this->shownColumns = ['kabupaten', 'calon', 'abstain'];
        $this->kecamatans = collect();
        $this->kelurahans = collect();
    }

    public function export()
    {
        $filename = 'rangkuman-suara-' . date('Y-m-d-His') . '.xlsx';
        
        return Excel::download(
            new RangkumanExport([
                'kabupaten_ids' => $this->kabupaten_ids,
                'kecamatan_ids' => $this->kecamatan_ids,
                'kelurahan_ids' => $this->kelurahan_ids,
                'search' => $this->search,
                'partisipasi' => $this->partisipasi
            ]), 
            $filename
        );
    }

    public function getFilteredKabupatensProperty()
    {
        return $this->kabupatens->filter(function($kabupaten) {
            return empty($this->searchKabupaten) || 
                   str_contains(strtolower($kabupaten->nama), strtolower($this->searchKabupaten));
        });
    }

    public function getFilteredKecamatansProperty()
    {
        return $this->kecamatans->filter(function($kecamatan) {
            return empty($this->searchKecamatan) || 
                   str_contains(strtolower($kecamatan->nama), strtolower($this->searchKecamatan));
        });
    }

    public function getFilteredKelurahansProperty()
    {
        return $this->kelurahans->filter(function($kelurahan) {
            return empty($this->searchKelurahan) || 
                   str_contains(strtolower($kelurahan->nama), strtolower($this->searchKelurahan));
        });
    }

    public function resetDropdownSearch()
    {
        $this->searchKabupaten = '';
        $this->searchKecamatan = '';
        $this->searchKelurahan = '';
    }

    public function getSummaryDataProperty()
    {
        $query = ResumeSuaraTPS::select(
            'resume_suara_tps.*',
            'tps.nama as tps_nama',
            'tps.kelurahan_id',
            'kelurahan.nama as kelurahan_nama',
            'kelurahan.kecamatan_id',
            'kecamatan.nama as kecamatan_nama',
            'kecamatan.kabupaten_id',
            'kabupaten.nama as kabupaten_nama'
        )
        ->join('tps', 'resume_suara_tps.id', '=', 'tps.id')
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

        if (!empty($this->kabupaten_ids)) {
            $query->whereIn('kabupaten.id', $this->kabupaten_ids);
        }

        if (!empty($this->kecamatan_ids)) {
            $query->whereIn('kecamatan.id', $this->kecamatan_ids);
        }

        if (!empty($this->kelurahan_ids)) {
            $query->whereIn('kelurahan.id', $this->kelurahan_ids);
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