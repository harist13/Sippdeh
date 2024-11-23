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

class RangkumanController extends Controller
{
    public function resume(Request $request, $wilayah = null)
    {
        // Default to showing only Gubernur data when no wilayah is specified
        if (!$wilayah) {
            return view('admin.resume.index', [
                'wilayah' => null,
                'kabupatenId' => null,
                'showPilgub' => true,
                'showPilwali' => false,
                'showPilbup' => false
            ]);
        }

        // Convert wilayah parameter to actual kabupaten_id and determine type
        $kabupaten = Kabupaten::where('nama', 'LIKE', '%' . str_replace('-', ' ', $wilayah) . '%')->first();
        
        if (!$kabupaten) {
            abort(404);
        }

        // Determine if it's a city (kota) or regency (kabupaten)
        $isKota = stripos($kabupaten->nama, 'kota') !== false;

        return view('admin.resume.index', [
            'wilayah' => $wilayah,
            'kabupatenId' => $kabupaten->id,
            'showPilgub' => false,
            'showPilwali' => $isKota,
            'showPilbup' => !$isKota
        ]);
    }

    public function pilgub(Request $request)
    {
        return view('admin.input-suara.pilgub.index');
    }

    public function pilwali(Request $request)
    {
        return view('admin.input-suara.pilwali.index');
    }

    public function pilbub(Request $request)
    {
        return view('admin.input-suara.pilbub.index');
    }
}