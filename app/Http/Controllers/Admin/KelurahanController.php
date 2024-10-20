<?php

namespace App\Http\Controllers\Admin;

use App\Exports\KelurahanExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportKelurahanRequest;
use App\Http\Requests\StoreKelurahanRequest;
use App\Http\Requests\UpdateKelurahanRequest;
use App\Imports\KelurahanImport;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class KelurahanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $kabupaten = Kabupaten::all();
        $kecamatan = Kecamatan::all();
        $kelurahanQuery = Kelurahan::query();

        if ($request->has('cari')) {
            $kataKunci = $request->get('cari');

            // kembalikan lagi ke halaman Daftar Kecamatan kalau query 'cari'-nya ternyata kosong.
            if ($kataKunci == '') {
                // jika pengguna juga mencari kabupaten, maka tetap sertakan kabupaten di URL-nya.
                if ($request->has('kabupaten')) {
                    return redirect()->route('kelurahan', ['kabupaten' => $request->get('kabupaten')]);
                }

                return redirect()->route('kelurahan');
            }

            $kelurahanQuery->whereLike('nama', "%$kataKunci%");
        }

        if ($request->has('kabupaten')) {
            $kelurahanQuery->whereHas('kecamatan', function($builder) use ($request) {
                $builder->where('kabupaten_id', $request->get('kabupaten'));
            });
        }

        $kelurahan = $kelurahanQuery->orderByDesc('id')->paginate(10);
        
        return view('admin.kelurahan.index', compact('kabupaten', 'kecamatan', 'kelurahan'));
    }

    public function export(Request $request)
    {
        if ($request->has('kabupaten_id') && is_numeric($request->get('kabupaten_id'))) {
            return Excel::download(new KelurahanExport($request->get('kabupaten_id')), 'kelurahan.xlsx');
        }
        
        return redirect()->back()->with('pesan_gagal', 'Gagal mengekspor kelurahan.');
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
            $kelurahan->nama = $validated['nama_kelurahan_baru'];
            $kelurahan->kecamatan_id = $validated['kecamatan_id'];
            $kelurahan->save();

            return redirect()->back()->with('status_pembuatan_kelurahan', 'berhasil');
        } catch (Exception $exception) {
            return redirect()->back()->with('status_pembuatan_kelurahan', 'gagal');
        }
    }

    public function import(ImportKelurahanRequest $request)
    {
        try {
            if ($request->hasFile('spreadsheet')) {
                $namaSpreadsheet = $request->file('spreadsheet')->store(options: 'local');

                $kelurahanImport = new KelurahanImport();
                $kelurahanImport->import($namaSpreadsheet, disk: 'local');
                
                $catatan = $kelurahanImport->getCatatan();
                $redirectBackResponse = redirect()->back();

                if (count($catatan) > 0) {
                    $redirectBackResponse->with('catatan_impor', $catatan);
                }

                return $redirectBackResponse->with('pesan_sukses', 'Berhasil mengimpor data kecamatan.');
            }

            return redirect()->back()->with('pesan_gagal', 'berkas .csv tidak terunggah.');
        } catch (Exception $exception) {
            dd($exception);
            return redirect()->back()->with('pesan_gagal', 'Gagal mengimpor data kecamatan.');
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
            $kelurahan->nama = $validated['nama_kelurahan'];
            $kelurahan->kecamatan_id = $validated['kecamatan_id'];
            $kelurahan->save();

            return redirect()->back()->with('status_pengeditan_kelurahan', 'berhasil');
        } catch (Exception $exception) {
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
        } catch (Exception $exception) {
            return redirect()->back()->with('status_penghapusan_kelurahan', 'gagal');
        }
    }
}
