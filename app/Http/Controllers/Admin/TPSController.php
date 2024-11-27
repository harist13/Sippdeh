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
use App\Models\SuaraTPS;
use App\Models\TPS;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Sentry\SentrySdk;
use Exception;

class TPSController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Superadmin.tps.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTPSRequest $request)
    {
        try {
            $validated = $request->validated();

            $tps = new TPS();
            $tps->nama = $validated['name'];
            $tps->dpt = $validated['dpt'];
            $tps->kelurahan_id = $validated['kelurahan_id'];
            $tps->save();

            return redirect()->back()->with('pesan_sukses', 'Berhasil menambah TPS.');
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);
            
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

                return redirect()->back()->with('pesan_sukses', 'Berhasil mengimpor data TPS.');
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
            $tps->nama = $validated['name'];
            $tps->dpt = $validated['dpt'];
            $tps->kelurahan_id = $validated['kelurahan_id'];
            $tps->save();

            return redirect()->back()->with('pesan_sukses', 'Berhasil mengedit TPS.');
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);

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
