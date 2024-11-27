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

class SuperResumeController extends Controller
{
    public function resume(Request $request, $wilayah = null)
    {
        // Default to showing only Gubernur data when no wilayah is specified
        if (!$wilayah) {
            return view('superadmin.resume.index', [
                'wilayah' => null,
                'kabupatenId' => null,
                'showPilgub' => true,
                'showPilwali' => false,
                'showPilbup' => false
            ]);
        }

        // Get kabupaten by slug
        $kabupaten = Kabupaten::where('slug', $wilayah)->firstOrFail();

        // Determine if it's a city (kota) or regency (kabupaten)
        $isKota = $kabupaten->isKota();

        return view('superadmin.resume.index', [
            'wilayah' => $wilayah,
            'kabupatenId' => $kabupaten->id,
            'showPilgub' => false,
            'showPilwali' => $isKota,
            'showPilbup' => !$isKota
        ]);
    }

    public function pilgub(Request $request)
    {
        return view('superadmin.input-suara.pilgub.index');
    }

    public function pilwali(Request $request)
    {
        return view('superadmin.input-suara.pilwali.index');
    }

    public function pilbub(Request $request)
    {
        return view('superadmin.input-suara.pilbub.index');
    }
}