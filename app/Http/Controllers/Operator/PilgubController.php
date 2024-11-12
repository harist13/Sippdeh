<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PilgubController extends Controller
{
    public function index()
    {
        $userWilayah = session('user_wilayah');
        return view('operator.input-suara.pilgub.index', compact('userWilayah'));
    }
}
