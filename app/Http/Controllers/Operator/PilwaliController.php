<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;

class PilwaliController extends Controller
{
    //
    public function index()
    {
        $userWilayah = session('user_wilayah');
        return view('operator.input-suara.pilwali.index', compact('userWilayah'));
    }
}
