<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKabupatenRequest;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use Exception;
use Illuminate\Http\Request;

class KabupatenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $provinsi = Provinsi::all();
        $kabupaten = Kabupaten::orderByDesc('id')->paginate(10);
        return view('admin.kabupaten.index', compact('provinsi', 'kabupaten'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKabupatenRequest $request)
    {
        try {
            $validated = $request->validated();

            $kabupaten = new Kabupaten();
            $kabupaten->nama = $validated['nama'];
            $kabupaten->provinsi_id = $validated['provinsi_id'];
            $kabupaten->save();

            return redirect()->back()->with('status_pembuatan_kabupaten', 'berhasil');
        } catch (Exception $error) {
            return redirect()->back()->with('status_pembuatan_kabupaten', 'gagal');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
