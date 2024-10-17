<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Pilkadacontroller extends Controller
{
    //
    public function index()
    {
        return view('operator.pilkada.index');
    }
}
