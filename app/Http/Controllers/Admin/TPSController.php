<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTPSRequest;
use App\Http\Requests\UpdateTPSRequest;
use App\Models\Kabupaten;
use App\Models\Kelurahan;
use App\Models\TPS;
use Exception;
use Illuminate\Http\Request;

class TPSController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $kabupaten = Kabupaten::all();
        $kelurahan = Kelurahan::all();
        $tpsQuery = TPS::query();

        if ($request->has('cari')) {
            $kataKunci = $request->get('cari');

            // kembalikan lagi ke halaman Daftar Kecamatan kalau query 'cari'-nya ternyata kosong.
            if ($kataKunci == '') {
                // jika pengguna juga mencari kabupaten, maka tetap sertakan kabupaten di URL-nya.
                if ($request->has('kabupaten')) {
                    return redirect()->route('tps', ['kabupaten' => $request->get('kabupaten')]);
                }

                return redirect()->route('tps');
            }

            $tpsQuery->whereLike('nama', "%$kataKunci%");
        }

        if ($request->has('kabupaten')) {
            $tpsQuery->whereHas('kelurahan', function($builder) use ($request) {
                $builder->whereHas('kecamatan', function($builder) use ($request) {
                    $builder->where('kabupaten_id', $request->get('kabupaten'));
                });
            });
        }

        $tps = $tpsQuery->orderByDesc('id')->paginate(10);
        
        return view('admin.tps.index', compact('tps', 'kelurahan', 'kabupaten'));
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
    public function store(StoreTPSRequest $request)
    {
        try {
            $validated = $request->validated();

            $tps = new TPS();
            $tps->nama = $validated['nama_tps_baru'];
            $tps->kelurahan_id = $validated['kelurahan_id_tps_baru'];
            $tps->save();

            return redirect()->back()->with('status_pembuatan_tps', 'berhasil');
        } catch (Exception $exception) {
            return redirect()->back()->with('status_pembuatan_tps', 'gagal');
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
    public function update(UpdateTPSRequest $request, string $id)
    {
        try {
            $validated = $request->validated();

            $tps = TPS::find($id);
            $tps->nama = $validated['nama_tps'];
            $tps->kelurahan_id = $validated['kelurahan_id_tps'];
            $tps->save();

            return redirect()->back()->with('status_pengeditan_tps', 'berhasil');
        } catch (Exception $exception) {
            return redirect()->back()->with('status_pengeditan_tps', 'gagal');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $tps = TPS::find($id);
            $tps->delete();

            return redirect()->back()->with('status_penghapusan_tps', 'berhasil');
        } catch (Exception $exception) {
            return redirect()->back()->with('status_penghapusan_tps', 'gagal');
        }
    }
}
