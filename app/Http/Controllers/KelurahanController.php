<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKelurahanRequest;
use App\Http\Requests\UpdateKelurahanRequest;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Exception;
use Illuminate\Http\Request;

class KelurahanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kecamatan = Kecamatan::all();
        $kelurahan = Kelurahan::orderByDesc('id')->paginate(10);
        
        return view('admin.kelurahan.index', compact('kecamatan', 'kelurahan'));
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
    public function store(StoreKelurahanRequest $request)
    {
        try {
            $validated = $request->validated();

            $kelurahan = new Kelurahan();
            $kelurahan->nama = $validated['nama'];
            $kelurahan->kecamatan_id = $validated['kecamatan_id'];
            $kelurahan->save();

            return redirect()->back()->with('status_pembuatan_kelurahan', 'berhasil');
        } catch (Exception $error) {
            return redirect()->back()->with('status_pembuatan_kelurahan', 'gagal');
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
    public function update(UpdateKelurahanRequest $request, string $id)
    {
        try {
            $validated = $request->validated();

            $kelurahan = Kelurahan::find($id);
            $kelurahan->nama = $validated['nama'];
            $kelurahan->kecamatan_id = $validated['kecamatan_id'];
            $kelurahan->save();

            return redirect()->back()->with('status_pengeditan_kelurahan', 'berhasil');
        } catch (Exception $error) {
            return redirect()->back()->with('status_pengeditan_kelurahan', 'gagal');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $kelurahan = Kelurahan::find($id);
            $kelurahan->delete();

            return redirect()->back()->with('status_penghapusan_kelurahan', 'berhasil');
        } catch (Exception $error) {
            return redirect()->back()->with('status_penghapusan_kelurahan', 'gagal');
        }
    }
}