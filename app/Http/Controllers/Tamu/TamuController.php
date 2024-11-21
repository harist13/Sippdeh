<?php

namespace App\Http\Controllers\Tamu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Petugas;
use App\Models\Calon;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use App\Models\ResumeSuaraPilgubKabupaten;
use App\Models\ResumeSuaraPilgubProvinsi;
use App\Models\ResumeSuaraPilgubTPS;
use App\Models\ResumeSuaraTPS;
use App\Models\SuaraCalon;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TamuController extends Controller
{
    public function Dashboard()
    {
        return view('Tamu.dashboard');
    }
}
