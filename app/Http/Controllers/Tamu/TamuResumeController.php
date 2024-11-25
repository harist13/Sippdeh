<?php

namespace App\Http\Controllers\Tamu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TamuResumeController extends Controller
{
    public function __invoke()
    {
        return view('Tamu.resume.index');
    }
}
