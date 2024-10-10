<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKabupatenRequest;
use App\Http\Requests\UpdateKabupatenRequest;
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
            $kabupaten->nama = $validated['nama_kabupaten_baru'];
            $kabupaten->provinsi_id = $validated['provinsi_id_kabupaten_baru'];
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
    public function update(UpdateKabupatenRequest $request, string $id)
    {
        try {
            $validated = $request->validated();

            $kabupaten = Kabupaten::find($id);
            $kabupaten->nama = $validated['nama_kabupaten'];
            $kabupaten->provinsi_id = $validated['provinsi_id_kabupaten'];
            $kabupaten->save();

            return redirect()->back()->with('status_pengeditan_kabupaten', 'berhasil');
        } catch (Exception $error) {
            return redirect()->back()->with('status_pengeditan_kabupaten', 'gagal');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $kabupaten = Kabupaten::find($id);
            $kabupaten->delete();

            return redirect()->back()->with('status_penghapusan_kabupaten', 'berhasil');
        } catch (Exception $error) {
            return redirect()->back()->with('status_penghapusan_kabupaten', 'gagal');
        }
    }
}
