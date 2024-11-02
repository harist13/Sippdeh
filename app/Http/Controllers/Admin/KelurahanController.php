<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Http\Requests\Admin\Kelurahan\ImportKelurahanRequest;
use App\Http\Requests\Admin\Kelurahan\StoreKelurahanRequest;
use App\Http\Requests\Admin\Kelurahan\UpdateKelurahanRequest;
use App\Imports\KelurahanImport;
use App\Exports\KelurahanExport;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class KelurahanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get items per page from request, default to 10
        $itemsPerPage = $request->input('itemsPerPage', 10);
        
        $kabupaten = Kabupaten::all();
        $kecamatan = Kecamatan::all();
        $kelurahanQuery = Kelurahan::query();

        if ($request->has('cari')) {
            $kataKunci = $request->get('cari');

            // kembalikan lagi ke halaman Daftar Kelurahan kalau query 'cari'-nya ternyata kosong.
            if ($kataKunci == '') {
                // jika pengguna juga mencari kabupaten, maka tetap sertakan kabupaten di URL-nya.
                if ($request->has('kabupaten')) {
                    return redirect()->route('kelurahan', [
                        'kabupaten' => $request->get('kabupaten'),
                        'itemsPerPage' => $itemsPerPage
                    ]);
                }

                return redirect()->route('kelurahan', ['itemsPerPage' => $itemsPerPage]);
            }

            $kelurahanQuery->whereLike('nama', "%$kataKunci%");
        }

        if ($request->has('kabupaten')) {
            $kelurahanQuery->whereHas('kecamatan', function($builder) use ($request) {
                $builder->where('kabupaten_id', $request->get('kabupaten'));
            });
        }

        $kelurahan = $kelurahanQuery->orderByDesc('id')
            ->paginate($itemsPerPage)
            ->withQueryString();
        
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

            return redirect()->back()->with('pesan_sukses', 'Berhasil menambah kelurahan.');
        } catch (Exception $exception) {
            return redirect()->back()->with('pesan_gagal', 'Gagal menambah kelurahan.');
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

                return $redirectBackResponse->with('pesan_sukses', 'Berhasil mengimpor data kelurahan.');
            }

            return redirect()->back()->with('pesan_gagal', 'Berkas .csv atau .xlsx tidak terunggah.');
        } catch (Exception $exception) {
            dd($exception);
            return redirect()->back()->with('pesan_gagal', 'Gagal mengimpor data kelurahan.');
        }
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

            return redirect()->back()->with('pesan_sukses', 'Berhasil mengedit kelurahan.');
        } catch (Exception $exception) {
            return redirect()->back()->with('pesan_gagal', 'Gagal mengedit kelurahan.');
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

            return redirect()->back()->with('pesan_sukses', 'Berhasil menghapus kelurahan.');
        } catch (Exception $exception) {
            return redirect()->back()->with('pesan_gagal', 'Gagal menghapus kelurahan.');
        }
    }
}
