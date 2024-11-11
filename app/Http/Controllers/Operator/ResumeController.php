<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    public function __invoke()
    {
        return view('operator.resume.index');
    }
}
