<?php

namespace App\Http\Controllers\Admin;

use App\Exports\KecamatanExport;
use App\Imports\KecamatanImport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Kecamatan\ImportKecamatanRequest;
use App\Http\Requests\Admin\Kecamatan\StoreKecamatanRequest;
use App\Http\Requests\Admin\Kecamatan\UpdateKecamatanRequest;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Sentry\SentrySdk;
use Exception;

class KecamatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
        return view('admin.kecamatan.index');
    }

    public function export(Request $request)
    {
        if ($request->has('kabupaten_id') && is_numeric($request->get('kabupaten_id'))) {
            return Excel::download(new KecamatanExport($request->get('kabupaten_id')), 'kecamatan.xlsx');
        }
        
        return redirect()->back()->with('pesan_gagal', 'Gagal mengekspor kecamatan,');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKecamatanRequest $request)
    {
        try {
            $validated = $request->validated();

            $kecamatan = new Kecamatan();
            $kecamatan->nama = $validated['name'];
            $kecamatan->kabupaten_id = $validated['kabupaten_id'];

            $kecamatan->save();

            return redirect()->back()->with('pesan_sukses', 'Berhasil menambah kecamatan.');
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);
            
            return redirect()->back()->with('pesan_gagal', 'Gagal menambah kecamatan.');
        }
    }

    public function import(ImportKecamatanRequest $request)
    {
        try {
            if ($request->hasFile('spreadsheet')) {
                $namaSpreadsheet = $request->file('spreadsheet')->store(options: 'local');

                $kecamatanImport = new KecamatanImport();
                $kecamatanImport->import($namaSpreadsheet, disk: 'local');
                
                $catatan = $kecamatanImport->getCatatan();
                $redirectBackResponse = redirect()->back();

                if (count($catatan) > 0) {
                    $redirectBackResponse->with('catatan_impor', $catatan);
                }

                return $redirectBackResponse->with('pesan_sukses', 'Berhasil mengimpor data kecamatan.');
            }

            return redirect()->back()->with('pesan_gagal', 'Berkas .csv atau .xlsx tidak terunggah.');
        } catch (Exception $exception) {
            dd($exception);
            return redirect()->back()->with('pesan_gagal', 'Gagal mengimpor data kecamatan.');
        }
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

            return redirect()->back()->with('pesan_sukses', 'Berhasil mengedit kecamatan.');
        } catch (Exception $exception) {
            return redirect()->back()->with('pesan_gagal', 'Gagal mengedit kecamatan.');
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

            return redirect()->back()->with('pesan_sukses', 'Berhasil menghapus kecamatan.');
        } catch (Exception $exception) {
            return redirect()->back()->with('pesan_gagal', 'Gagal menghapus kecamatan.');
        }
    }
}
