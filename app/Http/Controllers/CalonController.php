<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCalonRequest;
use App\Http\Requests\UpdateCalonRequest;
use App\Models\Calon;
use App\Models\Kabupaten;
use App\Traits\UploadImage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class CalonController extends Controller
{
    use UploadImage;

    public function __construct()
    {
        $this->imageManager = ImageManager::imagick();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $kabupaten = Kabupaten::all();
        $calonQuery = Calon::query();

        if ($request->has('cari')) {
            $kataKunci = $request->get('cari');

            // kembalikan lagi ke halaman Daftar Kecamatan kalau query 'cari'-nya ternyata kosong.
            if ($kataKunci == '') {
                // jika pengguna juga mencari kabupaten, maka tetap sertakan kabupaten di URL-nya.
                if ($request->has('kabupaten')) {
                    return redirect()->route('calon', ['kabupaten' => $request->get('kabupaten')]);
                }

                return redirect()->route('calon');
            }

            $calonQuery->whereLike('nama', "%$kataKunci%");
        }

        if ($request->has('kabupaten')) {
            $calonQuery->where('kabupaten_id', $request->get('kabupaten'));
        }

        $calon = $calonQuery->orderByDesc('id')->paginate(10);
        
        return view('admin.calon.index', compact('kabupaten', 'calon'));
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
    public function store(StoreCalonRequest $request)
    {
        try {
            $validated = $request->validated();

            $calon = new Calon();
            $calon->nama = $validated['nama_calon_baru'];
            $calon->kabupaten_id = $validated['kabupaten_id_calon_baru'];

            if ($request->hasFile('foto_calon_baru')) {
                $foto = $request->file('foto_calon_baru');
                $namaFoto = $foto->store('', 'foto_calon_lokal');
                $pathFoto = Storage::disk('foto_calon_lokal')->path($namaFoto);

                $namaFoto = $this->resizeImage($pathFoto, 300, 200, null, storage_path('app/public/foto_calon'));
                $calon->foto = $namaFoto;
            }

            $calon->save();

            return redirect()->back()->with('status_pembuatan_calon', 'berhasil');
        } catch (Exception $error) {
            dd($error);
            return redirect()->back()->with('status_pembuatan_calon', 'gagal');
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
    public function update(UpdateCalonRequest $request, string $id)
    {
        try {
            $validated = $request->validated();

            $calon = Calon::find($id);
            $calon->nama = $validated['nama_calon'];
            $calon->kabupaten_id = $validated['kabupaten_id_calon'];

            if ($request->hasFile('foto_calon_baru')) {
                $foto = $request->file('foto_calon_baru');
                $namaFoto = $foto->store('', 'foto_calon_lokal');
                $pathFoto = Storage::disk('foto_calon_lokal')->path($namaFoto);

                $namaFoto = $this->resizeImage($pathFoto, 300, 200, null, storage_path('app/public/foto_calon'));
                $calon->foto = $namaFoto;
            }

            $calon->save();

            return redirect()->back()->with('status_pengeditan_calon', 'berhasil');
        } catch (Exception $error) {
            return redirect()->back()->with('status_pengeditan_calon', 'gagal');
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

            Storage::disk('foto_calon_lokal')->delete($namaFoto);

            return redirect()->back()->with('status_penghapusan_calon', 'berhasil');
        } catch (Exception $error) {
            return redirect()->back()->with('status_penghapusan_calon', 'gagal');
        }
    }
}
