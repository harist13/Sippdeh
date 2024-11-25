<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;

class PilbupController extends Controller
{
    //
    public function index()
    {
        return view('operator.input-suara.pilbup.index');
    }

    public function daftarPemilih()
    {
        return view('operator.input-daftar-pemilih.pilbup.index');
    }
}
