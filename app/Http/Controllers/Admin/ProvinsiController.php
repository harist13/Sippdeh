<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProvinsiExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Provinsi\ImportProvinsiRequest;
use App\Http\Requests\Admin\Provinsi\StoreProvinsiRequest;
use App\Http\Requests\Admin\Provinsi\UpdateProvinsiRequest;
use App\Imports\ProvinsiImport;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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

    public function export(Request $request)
    {
        if ($request->has('kabupaten_id') && is_numeric($request->get('kabupaten_id'))) {
            return Excel::download(new ProvinsiExport($request->get('kabupaten_id')), 'provinsi.xlsx');
        }
        
        return redirect()->back()->with('pesan_gagal', 'Gagal mengimpor provinsi.');
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
    
            return redirect()->back()->with('pesan_sukses', 'Berhasil menambah provinsi.');
        } catch (Exception $exception) {
            return redirect()->back()->with('pesan_gagal', 'Gagal manambah provinsi.');
        }
    }

    public function import(ImportProvinsiRequest $request)
    {
        try {
            if ($request->hasFile('spreadsheet')) {
                $namaSpreadsheet = $request->file('spreadsheet')->store(options: 'local');

                $provinsiImport = new ProvinsiImport();
                $provinsiImport->import($namaSpreadsheet, disk: 'local');
                
                $catatan = $provinsiImport->getCatatan();
                $redirectBackResponse = redirect()->back();

                if (count($catatan) > 0) {
                    $redirectBackResponse->with('catatan_impor', $catatan);
                }

                return $redirectBackResponse->with('pesan_sukses', 'Berhasil mengimpor data provinsi.');
            }

            return redirect()->back()->with('pesan_gagal', 'Telah terjadi kesalahan, berkas .csv tidak terunggah.');
        } catch (Exception $exception) {
            dd($exception);
            return redirect()->back()->with('pesan_gagal', 'Gagal mengimpor data provinsi.');
        }
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
    
            return redirect()->back()->with('pesan_sukses', 'Berhasil mengedit provinsi.');
        } catch (Exception $exception) {
            return redirect()->back()->with('pesan_gagal', 'Gagal mengedit provinsi.');
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
    
            return redirect()->back()->with('pesan_sukses', 'Berhasil menghapus provinsi.');
        } catch (Exception $exception) {
            return redirect()->back()->with('pesan_gagal', 'Gagal menghapus provinsi.');
        }
    }
}
