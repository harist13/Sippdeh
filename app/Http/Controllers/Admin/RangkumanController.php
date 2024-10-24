<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Petugas;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\LoginHistory;
use App\Models\Kabupaten;
use App\Models\Calon;
use Illuminate\Support\Facades\Auth;

class RangkumanController extends Controller
{
    //
    public function rangkuman()
    {
        return view('admin.rangkuman');
    }
}
