<?php

namespace App\Http\Controllers;

use App\Exports\KabupatenExport;
use App\Http\Requests\ImportKabupatenRequest;
use App\Http\Requests\StoreKabupatenRequest;
use App\Http\Requests\UpdateKabupatenRequest;
use App\Imports\KabupatenImport;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class KabupatenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $provinsi = Provinsi::all();
        $kabupatenQuery = Kabupaten::query();

        if ($request->has('cari')) {
            $kataKunci = $request->get('cari');

            // kembalikan lagi ke halaman Daftar Kecamatan kalau query 'cari'-nya ternyata kosong.
            if ($kataKunci == '') {
                return redirect()->route('kabupaten');
            }

            $kabupatenQuery->whereLike('nama', "%$kataKunci%");
        }

        $kabupaten = $kabupatenQuery->orderByDesc('id')->paginate(10);

        return view('admin.kabupaten.index', compact('kabupaten', 'provinsi'));
    }

    public function export(Request $request)
    {
        if ($request->has('provinsi_id') && is_numeric($request->get('provinsi_id'))) {
            return Excel::download(new KabupatenExport($request->get('provinsi_id')), 'kabupaten.xlsx');
        }
        
        return redirect()->back()->with('gagal', 'Telah terjadi kesalahan, gagal mengekspor kabupaten.');
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

    public function import(ImportKabupatenRequest $request)
    {
        try {
            if ($request->hasFile('spreadsheet')) {
                $namaSpreadsheet = $request->file('spreadsheet')->store(options: 'local');

                $import = new KabupatenImport();
                $import->import($namaSpreadsheet, disk: 'local');
                
                $redirectBackResponse = redirect()->back();

                if (count($import->catatan()) > 0) {
                    $redirectBackResponse->with('catatan_impor', $import->catatan());
                }

                return $redirectBackResponse->with('sukses', 'Berhasil mengimpor data provinsi.');
            }

            return redirect()->back()->with('gagal', 'Telah terjadi kesalahan, berkas .csv tidak terunggah.');
        } catch (Exception $exception) {
            return redirect()->back()->with('gagal', 'Telah terjadi kesalahan, gagal mengimpor data provinsi.');
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
