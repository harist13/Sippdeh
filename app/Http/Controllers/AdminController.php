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
}
