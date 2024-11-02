<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Calon;
use App\Models\RingkasanSuaraTPS;
use App\Models\Kabupaten;
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

        // Get summary data from ringkasan_suara_tps with pagination
        $summaryData = RingkasanSuaraTPS::select(
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
        ->with(['suara', 'suaraCalon'])
        ->paginate($itemsPerPage)
        ->withQueryString();

        // Get list of kabupaten for filter
        $kabupatens = Kabupaten::all();

        return view('admin.rangkuman', compact('paslon', 'summaryData', 'kabupatens'));
    }

    public function export(Request $request)
    {
        $filename = 'rangkuman-suara-' . date('Y-m-d-His') . '.xlsx';
        return Excel::download(new RangkumanExport($request), $filename);
    }
}