<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Calon;
use App\Models\ResumeSuaraTPS;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\RangkumanExport;
use Maatwebsite\Excel\Facades\Excel;

class RangkumanController extends Controller
{
      public function rangkuman(Request $request)
    {
        // Get items per page from request, default to 10
        $itemsPerPage = $request->input('itemsPerPage', 10);
        
        // Get paslon data for gubernur position
        $paslon = Calon::where('posisi', 'Gubernur')->get();

        // Get location data for filters
        $kabupatens = Kabupaten::orderBy('nama')->get();
        $kecamatans = collect();
        $kelurahans = collect();

        // Base query
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

        // Apply search if provided
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kabupaten.nama', 'LIKE', "%{$search}%")
                  ->orWhere('kecamatan.nama', 'LIKE', "%{$search}%")
                  ->orWhere('kelurahan.nama', 'LIKE', "%{$search}%");
            });
        }

        // Apply location filters
        if ($request->kabupaten_id) {
            $query->where('kabupaten.id', $request->kabupaten_id);
            $kecamatans = Kecamatan::where('kabupaten_id', $request->kabupaten_id)
                         ->orderBy('nama')
                         ->get();
        }

        if ($request->kecamatan_id) {
            $query->where('kecamatan.id', $request->kecamatan_id);
            $kelurahans = Kelurahan::where('kecamatan_id', $request->kecamatan_id)
                         ->orderBy('nama')
                         ->get();
        }

        if ($request->kelurahan_id) {
            $query->where('kelurahan.id', $request->kelurahan_id);
        }

        // Apply partisipasi filter with fixed ranges
        if ($request->partisipasi) {
            $partisipasiValues = explode(',', $request->partisipasi);
            
            $query->where(function($q) use ($partisipasiValues) {
                foreach ($partisipasiValues as $value) {
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

        // If it's an AJAX search request, return JSON
        if ($request->ajax() && $request->filled('search')) {
            $results = $query->get();
            return response()->json($results);
        }

        // Paginate the results
        $summaryData = $query->paginate($itemsPerPage)->withQueryString();

        return view('admin.rangkuman', compact(
            'paslon', 
            'summaryData', 
            'kabupatens', 
            'kecamatans', 
            'kelurahans',
            'request'
        ));
    }

    // Methods for AJAX calls
    public function getKecamatan($kabupatenId)
    {
        $kecamatans = Kecamatan::where('kabupaten_id', $kabupatenId)
                     ->orderBy('nama')
                     ->get();
        return response()->json($kecamatans);
    }

    public function getKelurahan($kecamatanId)
    {
        $kelurahans = Kelurahan::where('kecamatan_id', $kecamatanId)
                     ->orderBy('nama')
                     ->get();
        return response()->json($kelurahans);
    }

    public function export(Request $request)
    {
        $filename = 'rangkuman-suara-' . date('Y-m-d-His') . '.xlsx';
        return Excel::download(new RangkumanExport($request), $filename);
    }

    public function suara() {
        return view ('admin.suara');
    }
}