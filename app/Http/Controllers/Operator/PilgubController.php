<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PilgubController extends Controller
{
    public function index()
    {
        $userWilayah = session('user_wilayah');
        return view('operator.pilgub.index', compact('userWilayah'));
    }
}
