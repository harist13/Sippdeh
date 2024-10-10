<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaslonController extends Controller
{
    //
    public function index(){
        return view('operator.paslon.index');
    }
}