<?php

namespace App\Http\Controllers;

use App\Exports\KecamatanExport;
use App\Http\Requests\StoreKecamatanRequest;
use App\Http\Requests\UpdateKecamatanRequest;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class KecamatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $kabupaten = Kabupaten::all();
        $kecamatanQuery = Kecamatan::query();

        if ($request->has('cari')) {
            $kataKunci = $request->get('cari');

            // kembalikan lagi ke halaman Daftar Kecamatan kalau query 'cari'-nya ternyata kosong.
            if ($kataKunci == '') {
                // jika pengguna juga mencari kabupaten, maka tetap sertakan kabupaten di URL-nya.
                if ($request->has('kabupaten')) {
                    return redirect()->route('kecamatan', ['kabupaten' => $request->get('kabupaten')]);
                }

                return redirect()->route('kecamatan');
            }

            $kecamatanQuery->whereLike('nama', "%$kataKunci%");
        }

        if ($request->has('kabupaten')) {
            $kecamatanQuery->where('kabupaten_id', $request->get('kabupaten'));
        }

        $kecamatan = $kecamatanQuery->orderByDesc('id')->paginate(10);
        
        return view('admin.kecamatan.index', compact('kabupaten', 'kecamatan'));
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
            $kabupaten->nama = $validated['nama_kecamatan_baru'];
            $kabupaten->kabupaten_id = $validated['kabupaten_id_kecamatan_baru'];
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

    public function export(Request $request)
    {
        if ($request->has('kabupaten_id') && is_numeric($request->get('kabupaten_id'))) {
            return Excel::download(new KecamatanExport($request->get('kabupaten_id')), 'kecamatan.xlsx');
        }
        
        return redirect()->back()->with('status_ekspor_kecamatan', 'gagal');
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
            $kabupaten->nama = $validated['nama_kecamatan'];
            $kabupaten->kabupaten_id = $validated['kabupaten_id_kecamatan'];
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
