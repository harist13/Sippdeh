<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function Dashboard()
    {
        return view('admin.dashboard');
    }
    public function rekapitulasi()
    {
        return view('admin.rekapitulasi');
    }
    public function rangkuman()
    {
        return view('admin.rangkuman');
    }
    public function calon()
    {
        return view('admin.calon');
    }
    public function provinsi()
    {
        return view('admin.provinsi');
    }
    public function tps()
    {
        return view('admin.tps');
    }
    public function kabupaten()
    {
        return view('admin.kabupaten');
    }
    public function kecamatan()
    {
        return view('admin.kecamatan');
    }
    public function kelurahan()
    {
        return view('admin.kelurahan');
    }

}
