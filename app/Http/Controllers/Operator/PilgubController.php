<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PilgubController extends Controller
{
    public function index()
    {
        return view('operator.input-suara.pilgub.index');
    }

    public function daftarPemilih()
    {
        return view('operator.input-daftar-pemilih.pilgub.index');
    }
}
