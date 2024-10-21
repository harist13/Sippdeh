<?php

namespace App\Http\Controllers;

class PilkadaController extends Controller
{
    //
    public function index()
    {
        $userWilayah = session('user_wilayah');
        return view('operator.pilkada.index', compact('userWilayah'));
    }
}
