<?php

namespace App\Http\Controllers\Admin;

use App\Exports\KabupatenExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Kabupaten\ImportKabupatenRequest;
use App\Http\Requests\Admin\Kabupaten\StoreKabupatenRequest;
use App\Http\Requests\Admin\Kabupaten\UpdateKabupatenRequest;
use App\Imports\KabupatenImport;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Exception;

class KabupatenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get items per page from request, default to 10
        $itemsPerPage = $request->input('itemsPerPage', 10);
        
        $provinsi = Provinsi::all();
        $kabupatenQuery = Kabupaten::query();

        if ($request->has('cari')) {
            $kataKunci = $request->get('cari');

            // kembalikan lagi ke halaman Daftar Kabupaten kalau query 'cari'-nya ternyata kosong.
            if ($kataKunci == '') {
                return redirect()->route('kabupaten', ['itemsPerPage' => $itemsPerPage]);
            }

            $kabupatenQuery->whereLike('nama', "%$kataKunci%");
        }

        $kabupaten = $kabupatenQuery->orderByDesc('id')
            ->paginate($itemsPerPage)
            ->withQueryString(); // Mempertahankan parameter URL saat paginasi

        return view('admin.kabupaten.index', compact('kabupaten', 'provinsi'));
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(StoreKabupatenRequest $request)
    {
        try {
            $validated = $request->validated();

            $kabupaten = new Kabupaten();
            $kabupaten->nama = $validated['name'];
            $kabupaten->provinsi_id = $validated['provinsi_id'];
            
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $path = $logo->store('kabupaten-logo', 'public');
                $kabupaten->logo = $path;
            }
            
            $kabupaten->save();

            return redirect()->back()->with('pesan_sukses', 'Berhasil menambahkan kabupaten.');
        } catch (Exception $exception) {
            return redirect()->back()->with('pesan_gagal', 'Gagal menambahkan kabupaten.');
        }
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

            return redirect()->back()->with('pesan_gagal', 'Berkas .csv atau .xlsx tidak terunggah.');
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

            $kabupaten = Kabupaten::findOrFail($id);
            $kabupaten->nama = $validated['nama_kabupaten'];
            $kabupaten->provinsi_id = $validated['provinsi_id_kabupaten'];
            
            if ($request->hasFile('logo_kabupaten')) {
                // Hapus logo lama jika ada
                if ($kabupaten->logo && Storage::disk('public')->exists($kabupaten->logo)) {
                    Storage::disk('public')->delete($kabupaten->logo);
                }
                
                $logo = $request->file('logo_kabupaten');
                $path = $logo->store('kabupaten-logo', 'public');
                $kabupaten->logo = $path;
            }
            
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
            $kabupaten = Kabupaten::findOrFail($id);
            
            if ($kabupaten->logo && Storage::disk('public')->exists($kabupaten->logo)) {
                Storage::disk('public')->delete($kabupaten->logo);
            }
            
            $kabupaten->delete();

            return redirect()->back()->with('pesan_sukses', 'Berhasil menghapus kabupaten.');
        } catch (Exception $exception) {
            return redirect()->back()->with('pesan_gagal', 'Gagal menghapus kabupaten.');
        }
    }
}
