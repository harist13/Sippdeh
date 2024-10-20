<?php

namespace App\Http\Controllers\Admin;

use App\Exports\KabupatenExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportKabupatenRequest;
use App\Http\Requests\StoreKabupatenRequest;
use App\Http\Requests\UpdateKabupatenRequest;
use App\Imports\KabupatenImport;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

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
        try {
            if ($request->has('provinsi_id') && is_numeric($request->get('provinsi_id'))) {
                return Excel::download(new KabupatenExport($request->get('provinsi_id')), 'kabupaten.xlsx');
            }
            
            return redirect()->back()->with('pesan_gagal', 'Gagal mengekspor kabupaten.');
        } catch (Exception $exception) {
            return redirect()->back()->with('pesan_gagal', 'Gagal mengekspor kabupaten.');
        }
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

            return redirect()->back()->with('pesan_sukses', 'Berhasil menambahkan kabupaten.');
        } catch (Exception $exception) {
            return redirect()->back()->with('pesan_gagal', 'Gagal menambahkan kabupaten.');
        }
    }

    public function import(ImportKabupatenRequest $request)
    {
        try {
            if ($request->hasFile('spreadsheet')) {
                $namaSpreadsheet = $request->file('spreadsheet')->store(options: 'local');

                $kabupatenImport = new KabupatenImport();
                $kabupatenImport->import($namaSpreadsheet, disk: 'local');
                
                $catatan = $kabupatenImport->getCatatan();
                $redirectBackResponse = redirect()->back();

                if (count($catatan) > 0) {
                    $redirectBackResponse->with('catatan_impor', $catatan);
                }

                return $redirectBackResponse->with('pesan_sukses', 'Berhasil mengimpor data kabupaten.');
            }

            return redirect()->back()->with('pesan_gagal', 'berkas .csv tidak terunggah.');
        } catch (Exception $exception) {
            // dd($exception);
            return redirect()->back()->with('pesan_gagal', 'Gagal mengimpor data kabupaten.');
        }
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

            return redirect()->back()->with('pesan_sukses', 'Berhasil mengedit kabupaten.');
        } catch (Exception $exception) {
            return redirect()->back()->with('pesan_gagal', 'Gagal mengedit kabupaten.');
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

            return redirect()->back()->with('pesan_sukses', 'Berhasil menghapus kabupaten.');
        } catch (Exception $exception) {
            return redirect()->back()->with('pesan_gagal', 'Gagal menghapus kabupaten.');
        }
    }
}
