<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKecamatanRequest;
use App\Http\Requests\UpdateKecamatanRequest;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Provinsi;
use Exception;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kabupaten = Kabupaten::all();
        $kecamatan = Kecamatan::orderByDesc('id')->paginate(10);
        return view('admin.kecamatan.index', compact('kecamatan', 'kabupaten'));
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
    public function store(StoreKecamatanRequest $request)
    {
        try {
            $validated = $request->validated();

            $kabupaten = new Kecamatan();
            $kabupaten->nama = $validated['nama'];
            $kabupaten->kabupaten_id = $validated['kabupaten_id'];
            $kabupaten->save();

            return redirect()->back()->with('status_pembuatan_kecamatan', 'berhasil');
        } catch (Exception $error) {
            return redirect()->back()->with('status_pembuatan_kecamatan', 'gagal');
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
    public function update(UpdateKecamatanRequest $request, string $id)
    {
        try {
            $validated = $request->validated();

            $kabupaten = Kecamatan::find($id);
            $kabupaten->nama = $validated['nama'];
            $kabupaten->kabupaten_id = $validated['kabupaten_id'];
            $kabupaten->save();

            return redirect()->back()->with('status_pengeditan_kecamatan', 'berhasil');
        } catch (Exception $error) {
            return redirect()->back()->with('status_pengeditan_kecamatan', 'gagal');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $kabupaten = Kecamatan::find($id);
            $kabupaten->delete();

            return redirect()->back()->with('status_penghapusan_kecamatan', 'berhasil');
        } catch (Exception $error) {
            return redirect()->back()->with('status_penghapusan_kecamatan', 'gagal');
        }
    }
}
