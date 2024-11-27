<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Provinsi;
use App\Http\Requests\Admin\Provinsi\ImportProvinsiRequest;
use App\Http\Requests\Admin\Provinsi\StoreProvinsiRequest;
use App\Http\Requests\Admin\Provinsi\UpdateProvinsiRequest;
use App\Exports\ProvinsiExport;
use App\Imports\ProvinsiImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Sentry\SentrySdk;
use Exception;

class ProvinsiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
    {
        return view('Superadmin.provinsi.index');
    }

    public function store(StoreProvinsiRequest $request)
    {
        try {
            $validated = $request->validated();

            $provinsi = new Provinsi();
            $provinsi->nama = $validated['name'];
            
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $logoName = time() . '_' . $logo->getClientOriginalName();
                $logo->move(public_path('storage/kabupaten_logo'), $logoName);
                $provinsi->logo = $logoName;
            }
            
            $provinsi->save();

            return redirect()->back()->with('pesan_sukses', 'Berhasil menambah provinsi.');
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);
            
            return redirect()->back()->with('pesan_gagal', 'Gagal manambah provinsi.');
        }
    }

    public function export(Request $request)
    {
        if ($request->has('kabupaten_id') && is_numeric($request->get('kabupaten_id'))) {
            return Excel::download(new ProvinsiExport($request->get('kabupaten_id')), 'provinsi.xlsx');
        }
        
        return redirect()->back()->with('pesan_gagal', 'Gagal mengimpor provinsi.');
    }

    public function update(UpdateProvinsiRequest $request, string $id)
    {
        try {
            $validated = $request->validated();

            $provinsi = Provinsi::find($id);
            $provinsi->nama = $validated['name'];
            
            // Handle logo update
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($provinsi->logo && file_exists(public_path('storage/kabupaten_logo/' . $provinsi->logo))) {
                    unlink(public_path('storage/kabupaten_logo/' . $provinsi->logo));
                }
                
                $logo = $request->file('logo');
                $logoName = time() . '_' . $logo->getClientOriginalName(); // Menggunakan nama asli file
                // Simpan file ke storage
                $logo->move(public_path('storage/kabupaten_logo'), $logoName);
                $provinsi->logo = $logoName;
            }
            
            $provinsi->save();

            return redirect()->back()->with('pesan_sukses', 'Berhasil mengedit provinsi.');
        } catch (Exception $exception) {
            dd($exception); // Untuk debugging
            return redirect()->back()->with('pesan_gagal', 'Gagal mengedit provinsi.');
        }
    }

    public function destroy(string $id)
    {
        try {
            $provinsi = Provinsi::find($id);
            
            // Delete logo if exists
            if ($provinsi->logo && file_exists(public_path('storage/kabupaten_logo/' . $provinsi->logo))) {
                unlink(public_path('storage/kabupaten_logo/' . $provinsi->logo));
            }
            
            $provinsi->delete();

            return redirect()->back()->with('pesan_sukses', 'Berhasil menghapus provinsi.');
        } catch (Exception $exception) {
            dd($exception); // Untuk debugging
            return redirect()->back()->with('pesan_gagal', 'Gagal menghapus provinsi.');
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

}
