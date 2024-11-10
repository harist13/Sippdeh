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
use App\Exports\WalikotaExport;
use Maatwebsite\Excel\Facades\Excel;

class WalikotaTable extends Component
{
    use WithPagination;

    // Regular properties
    public $search = '';
    public $itemsPerPage = 10;
    public $kabupaten_ids = []; // Changed to array
    public $kecamatan_ids = []; // Changed to array
    public $kelurahan_ids = []; // Changed to array
    public $partisipasi = [];
    public $showFilterModal = false;
    public $showExportModal = false;
    public $exportKabupatenId = '';

    // Search properties for dropdowns
    public $searchKabupaten = '';
    public $searchKecamatan = '';
    public $searchKelurahan = '';

    // Collections for dropdowns
    public $kabupatens;
    public $kecamatans;
    public $kelurahans;
    public $paslon;
    public $hiddenColumns = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'kabupaten_ids' => ['except' => []],
        'kecamatan_ids' => ['except' => []],
        'kelurahan_ids' => ['except' => []],
        'partisipasi' => ['except' => []],
        'hiddenColumns' => ['except' => []]
    ];

    public function mount()
    {
        $this->kabupatens = Kabupaten::orderBy('nama')->get();
        $this->kecamatans = collect();
        $this->kelurahans = collect();
        $this->paslon = Calon::where('posisi', 'Walikota')->get();
    }

    // Computed properties for filtered lists
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

    // Reset search when closing dropdowns
    public function resetDropdownSearch()
    {
        $this->searchKabupaten = '';
        $this->searchKecamatan = '';
        $this->searchKelurahan = '';
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
        $this->kecamatans = collect();
        $this->kelurahans = collect();
    }

    public function export()
    {
        $filename = 'rangkuman-suara-walikota-' . date('Y-m-d-His') . '.xlsx';
        
        return Excel::download(new WalikotaExport([
            'kabupaten_ids' => $this->kabupaten_ids,
            'kecamatan_ids' => $this->kecamatan_ids,
            'kelurahan_ids' => $this->kelurahan_ids,
            'search' => $this->search,
            'partisipasi' => $this->partisipasi
        ]), $filename);
    }

    public function getWalikotaDataProperty()
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
        ->whereHas('suaraCalon.calon', function($query) {
            $query->where('posisi', 'Walikota');
        })
        ->with(['suara', 'suaraCalon' => function($query) {
            $query->whereHas('calon', function($q) {
                $q->where('posisi', 'Walikota');
            });
        }]);

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
        return view('livewire.admin.walikota-table', [
            'walikotaData' => $this->walikotaData
        ]);
    }
}