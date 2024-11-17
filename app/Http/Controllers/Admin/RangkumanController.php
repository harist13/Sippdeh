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
    public function resume(Request $request, $wilayah = null)
    {
        // Convert wilayah parameter to actual kabupaten_id
        $kabupatenId = null;
        if ($wilayah) {
            $kabupaten = Kabupaten::where('nama', 'LIKE', '%' . str_replace('-', ' ', $wilayah) . '%')->first();
            $kabupatenId = $kabupaten ? $kabupaten->id : null;
        }
        
        return view('admin.resume.index', compact('wilayah', 'kabupatenId'));
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