<?php

namespace App\Http\Controllers;

use App\Exports\KecamatanExport;
use App\Http\Requests\ImportKecamatanRequest;
use App\Http\Requests\StoreKecamatanRequest;
use App\Http\Requests\UpdateKecamatanRequest;
use App\Imports\KecamatanImport;
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

            $kecamatan = new Kecamatan();
            $kecamatan->nama = $validated['nama_kecamatan_baru'];
            $kecamatan->kabupaten_id = $validated['kabupaten_id_kecamatan_baru'];
            $kecamatan->save();

            return redirect()->back()->with('status_pembuatan_kecamatan', 'berhasil');
        } catch (Exception $error) {
            return redirect()->back()->with('status_pembuatan_kecamatan', 'gagal');
        }
    }

    public function import(ImportKecamatanRequest $request)
    {
        try {
            if ($request->hasFile('spreadsheet')) {
                $namaSpreadsheet = $request->file('spreadsheet')->store(options: 'local');

                $kecamatanImport = new KecamatanImport();
                $kecamatanImport->import($namaSpreadsheet, disk: 'local');
                
                $redirectBackResponse = redirect()->back();

                if (count($kecamatanImport->getCatatan()) > 0) {
                    $redirectBackResponse->with('catatan_impor', $kecamatanImport->getCatatan());
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

            $kecamatan = Kecamatan::find($id);
            $kecamatan->nama = $validated['nama_kecamatan'];
            $kecamatan->kabupaten_id = $validated['kabupaten_id_kecamatan'];
            $kecamatan->save();

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
            $kecamatan = Kecamatan::find($id);
            $kecamatan->delete();

            return redirect()->back()->with('status_penghapusan_kecamatan', 'berhasil');
        } catch (Exception $error) {
            return redirect()->back()->with('status_penghapusan_kecamatan', 'gagal');
        }
    }
}
