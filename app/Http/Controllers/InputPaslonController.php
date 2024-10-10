<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InputPaslonController extends Controller
{
    //
    public function index(){
        return view('operator.input-paslon.index');
    }
    public function namapaslon(){
        return view('operator.input-paslon.namapaslon');
    }
}
