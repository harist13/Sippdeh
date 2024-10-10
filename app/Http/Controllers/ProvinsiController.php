<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProvinsiRequest;
use App\Http\Requests\UpdateProvinsiRequest;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use Exception;
use Illuminate\Http\Request;

class ProvinsiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $kabupaten = Kabupaten::all();
        $provinsiQuery = Provinsi::query();

        if ($request->has('cari')) {
            $kataKunci = $request->get('cari');

            // kembalikan lagi ke halaman Daftar Kecamatan kalau query 'cari'-nya ternyata kosong.
            if ($kataKunci == '') {
                // jika pengguna juga mencari kabupaten, maka tetap sertakan kabupaten di URL-nya.
                if ($request->has('kabupaten')) {
                    return redirect()->route('provinsi', ['kabupaten' => $request->get('kabupaten')]);
                }

                return redirect()->route('provinsi');
            }

            $provinsiQuery->whereLike('nama', "%$kataKunci%");
        }

        if ($request->has('kabupaten')) {
            $provinsiQuery->whereHas('kabupaten', function($builder) use ($request) {
                $builder->where('id', $request->get('kabupaten'));
            });
        }

        $provinsi = $provinsiQuery->orderByDesc('id')->paginate(10);
        
        return view('admin.provinsi.index', compact('kabupaten', 'provinsi'));
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
    public function store(StoreProvinsiRequest $request)
    {
        try {
            $validated = $request->validated();

            $provinsi = new Provinsi();
            $provinsi->nama = $validated['nama_provinsi_baru'];
            $provinsi->save();
    
            return redirect()->back()->with('status_pembuatan_provinsi', 'berhasil');
        } catch (Exception $error) {
            return redirect()->back()->with('status_pembuatan_provinsi', 'gagal');
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
    public function update(UpdateProvinsiRequest $request, string $id)
    {
        try {
            $validated = $request->validated();

            $provinsi = Provinsi::find($id);
            $provinsi->nama = $validated['nama_provinsi'];
            $provinsi->save();
    
            return redirect()->back()->with('status_pengeditan_provinsi', 'berhasil');
        } catch (Exception $error) {
            dd($error);
            return redirect()->back()->with('status_pengeditan_provinsi', 'gagal');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $provinsi = Provinsi::find($id);
            $provinsi->delete();
    
            return redirect()->back()->with('status_penghapusan_provinsi', 'berhasil');
        } catch (Exception $error) {
            dd($error);
            return redirect()->back()->with('status_penghapusan_provinsi', 'gagal');
        }
    }
}
