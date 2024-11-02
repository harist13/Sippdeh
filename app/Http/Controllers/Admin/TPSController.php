<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TPS\ImportTPSRequest;
use App\Http\Requests\Admin\TPS\StoreTPSRequest;
use App\Http\Requests\Admin\TPS\UpdateTPSRequest;
use App\Imports\TPSImport;
use App\Models\Kabupaten;
use App\Models\Kelurahan;
use App\Models\TPS;
use Exception;

class TPSController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get items per page from request, default to 10
        $itemsPerPage = $request->input('itemsPerPage', 10);
        
        $kabupaten = Kabupaten::all();
        $kelurahan = Kelurahan::all();
        $tpsQuery = TPS::query();

        if ($request->has('cari')) {
            $kataKunci = $request->get('cari');

            // kembalikan lagi ke halaman Daftar TPS kalau query 'cari'-nya ternyata kosong.
            if ($kataKunci == '') {
                // jika pengguna juga mencari kabupaten, maka tetap sertakan kabupaten di URL-nya.
                if ($request->has('kabupaten')) {
                    return redirect()->route('tps', [
                        'kabupaten' => $request->get('kabupaten'),
                        'itemsPerPage' => $itemsPerPage
                    ]);
                }

                return redirect()->route('tps', ['itemsPerPage' => $itemsPerPage]);
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

        $tps = $tpsQuery->orderByDesc('id')
            ->paginate($itemsPerPage)
            ->withQueryString();
        
        return view('admin.tps.index', compact('tps', 'kelurahan', 'kabupaten'));
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

            return redirect()->back()->with('pesan_sukses', 'Berhasil menambah TPS.');
        } catch (Exception $exception) {
            return redirect()->back()->with('pesan_gagal', 'Gagal menambah TPS.');
        }
    }

    public function import(ImportTPSRequest $request)
    {
        try {
            if ($request->hasFile('spreadsheet')) {
                $namaSpreadsheet = $request->file('spreadsheet')->store(options: 'local');

                $tpsImport = new TPSImport();
                $tpsImport->import($namaSpreadsheet, disk: 'local');
                
                $catatan = $tpsImport->getCatatan();
                $redirectBackResponse = redirect()->back();

                if (count($catatan) > 0) {
                    $redirectBackResponse->with('catatan_impor', $catatan);
                }

                return $redirectBackResponse->with('pesan_sukses', 'Berhasil mengimpor data TPS.');
            }

            return redirect()->back()->with('pesan_gagal', 'Berkas .csv atau .xlsx tidak terunggah.');
        } catch (Exception $exception) {
            dd($exception);
            return redirect()->back()->with('pesan_gagal', 'Gagal mengimpor data TPS.');
        }
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

            return redirect()->back()->with('pesan_sukses', 'Berhasil mengedit TPS.');
        } catch (Exception $exception) {
            return redirect()->back()->with('pesan_gagal', 'Gagal mengedit TPS.');
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

            return redirect()->back()->with('pesan_sukses', 'Berhasil menghapus TPS.');
        } catch (Exception $exception) {
            return redirect()->back()->with('pesan_gagal', 'Gagal menghapus TPS.');
        }
    }
}
