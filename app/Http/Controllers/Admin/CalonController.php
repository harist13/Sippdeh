<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\Calon\StoreCalonRequest;
use App\Http\Requests\Admin\Calon\UpdateCalonRequest;
use App\Models\Calon;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use Exception;
use Sentry\SentrySdk;

class CalonController extends Controller
{
    private string $diskName = 'foto_calon_lokal';

    private Filesystem $disk;

    public function __construct()
    {
        $this->disk = Storage::disk($this->diskName);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get items per page from request, default to 10
        $itemsPerPage = $request->input('itemsPerPage', 10);
        
        $provinsi = Provinsi::all();
        $kabupaten = Kabupaten::all();

        $calonQuery = Calon::query();

        if ($request->has('cari')) {
            $kataKunci = $request->get('cari');

            // kembalikan lagi ke halaman Daftar Calon kalau query 'cari'-nya ternyata kosong.
            if ($kataKunci == '') {
                return $this->redirectBack($request);
            }

            $calonQuery->whereLike('nama', "%$kataKunci%")->orWhereLike('nama_wakil', "%$kataKunci%");
        }

        if ($request->has('kabupaten')) {
            $calonQuery->where('kabupaten_id', $request->get('kabupaten'));
        }

        $calon = $calonQuery->orderByDesc('id')
            ->paginate($itemsPerPage)
            ->withQueryString();
        
        return view('superadmin.calon.index', [...compact('provinsi', 'kabupaten', 'calon'), 'disk' => $this->disk]);
    }

    private function redirectBack(Request $request): RedirectResponse {
        if ($request->has('kabupaten')) {
            // jika pengguna juga mencari kabupaten, maka tetap sertakan kabupaten di URL-nya.
            return redirect()->route('calon', ['kabupaten' => $request->get('kabupaten')]);
        }

        return redirect()->route('calon');
    }

   /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCalonRequest $request)
    {
        try {
            $validated = $request->validated();

            $calon = new Calon();
            $calon->no_urut = $validated['no_urut'];
            $calon->nama = $validated['nama_calon_baru'];
            $calon->nama_wakil = $validated['nama_calon_wakil_baru'];
            $calon->posisi = $validated['posisi'];

            if ($calon->posisi == 'GUBERNUR') {
                $calon->provinsi_id = $validated['provinsi_id_calon_baru'];
            }

            if ($calon->posisi == 'WALIKOTA' || $calon->posisi == 'BUPATI') {
                $calon->kabupaten_id = $validated['kabupaten_id_calon_baru'];
            }

            if ($request->hasFile('foto_calon_baru')) {
                $calon->foto = $request->file('foto_calon_baru')->store(options: $this->diskName);
            }

            $calon->save();

            return redirect()->back()->with('pesan_sukses', 'Berhasil menambah pasangan calon.');
        } catch (Exception $exception) {
            SentrySdk::getCurrentHub()->captureException($exception);
            return redirect()->back()->with('pesan_gagal', 'Gagal menambah pasangan calon.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCalonRequest $request, string $id)
    {
        try {
            $validated = $request->validated();

            $calon = Calon::find($id);
            $calon->no_urut = $validated['no_urut'];
            $calon->nama = $validated['nama_calon'];
            $calon->nama_wakil = $validated['nama_calon_wakil'];
            $calon->posisi = $validated['posisi'];

            if ($calon->posisi == 'GUBERNUR') {
                $calon->provinsi_id = $validated['provinsi_id_calon'];
            }

            if ($calon->posisi == 'WALIKOTA' || $calon->posisi == 'BUPATI') {
                $calon->kabupaten_id = $validated['kabupaten_id_calon'];
            }

            if ($request->hasFile('foto_calon')) {
                // hapus foto lama
                if ($calon->foto != null) {
                    $this->disk->delete($calon->foto);
                }

                $calon->foto = $request->file('foto_calon')->store(options: $this->diskName);
            }

            $calon->save();

            return redirect()->back()->with('pesan_sukses', 'Berhasil mengedit pasangan calon.');
        } catch (Exception $exception) {
            \Log::error('Error updating calon: ' . $exception->getMessage());
            return redirect()->back()->with('pesan_gagal', 'Gagal mengedit pasangan calon.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $calon = Calon::find($id);
            $namaFoto = $calon->foto;

            $calon->delete();

            if ($namaFoto != null) {
                $this->disk->delete($namaFoto);
            }

            return redirect()->back()->with('pesan_sukses', 'Berhasil menghapus pasangan calon.');
        } catch (Exception $exception) {
            return redirect()->back()->with('pesan_gagal', 'Gagal menghapus pasangan calon.');
        }
    }

    public function destroyGambar(string $id)
    {
        try {
            $calon = Calon::find($id);

            $this->disk->delete($calon->foto);

            $calon->foto = null;
            $calon->save();

            return redirect()->back()->with('pesan_sukses', 'Berhasil menghapus gambar pasangan calon.');
        } catch (Exception $exception) {
            return redirect()->back()->with('pesan_gagal', 'Gagal menghapus gambar pasangan calon.');
        }
    }
}
