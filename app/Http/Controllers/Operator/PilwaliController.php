<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;

class PilwaliController extends Controller
{
    //
    public function index()
    {
        return view('operator.input-suara.pilwali.index');
    }

    public function daftarPemilih()
    {
        return view('operator.input-daftar-pemilih.pilwali.index');
    }
}
