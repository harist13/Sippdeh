<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;

class PilbupController extends Controller
{
    //
    public function index()
    {
        $userWilayah = session('user_wilayah');
        return view('operator.pilbup.index', compact('userWilayah'));
    }
}
