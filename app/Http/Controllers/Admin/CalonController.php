<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Calon\StoreCalonRequest;
use App\Http\Requests\Admin\Calon\UpdateCalonRequest;
use App\Models\Calon;
use App\Models\Kabupaten;
use App\Traits\UploadImage;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class CalonController extends Controller
{
    use UploadImage;

    private string $diskName = 'foto_calon_lokal';

    public function __construct()
    {
        $this->imageManager = ImageManager::imagick();
        $this->disk = Storage::disk($this->diskName);
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
                return $this->arahkanKembali($request);
            }

            $calonQuery->whereLike('nama', "%$kataKunci%");
        }

        if ($request->has('kabupaten')) {
            $calonQuery->where('kabupaten_id', $request->get('kabupaten'));
        }

        $calon = $calonQuery->orderByDesc('id')->paginate(10);
        
        return view('admin.calon.index', [...compact('kabupaten', 'calon'), 'disk' => $this->disk]);
    }

    private function arahkanKembali(Request $request): RedirectResponse {
        if ($request->has('kabupaten')) {
            // jika pengguna juga mencari kabupaten, maka tetap sertakan kabupaten di URL-nya.
            return redirect()->route('calon', ['kabupaten' => $request->get('kabupaten')]);
        }

        return redirect()->route('calon');
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
                $calon->foto = $this->simpanFoto($request->file('foto_calon_baru'));
            }

            $calon->save();

            return redirect()->back()->with('status_pembuatan_calon', 'berhasil');
        } catch (Exception $exception) {
            return redirect()->back()->with('status_pembuatan_calon', 'gagal');
        }
    }

    private function simpanFoto(UploadedFile $foto): string {
        try {
            $namaFoto = $foto->store(options: $this->diskName);
            $pathFoto = $this->disk->path($namaFoto);

            // Ubah ukuran foto menjadi 300x200
            $namaFoto = $this->resizeImage($pathFoto, 300, 200, null, $this->disk->path(''));

            return $namaFoto;
        } catch (Exception $exception) {
            throw $exception;
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

            if ($request->hasFile('foto_calon')) {
                // hapus foto lama
                if ($calon->foto != null) {
                    $this->disk->delete($calon->foto);
                }

                $calon->foto = $this->simpanFoto($request->file('foto_calon'));
            }

            $calon->save();

            return redirect()->back()->with('status_pengeditan_calon', 'berhasil');
        } catch (Exception $exception) {
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

            $this->disk->delete($namaFoto);

            return redirect()->back()->with('status_penghapusan_calon', 'berhasil');
        } catch (Exception $exception) {
            return redirect()->back()->with('status_penghapusan_calon', 'gagal');
        }
    }

    public function destroyGambar(string $id)
    {
        try {
            $calon = Calon::find($id);

            $this->disk->delete($calon->foto);

            $calon->foto = null;
            $calon->save();

            return redirect()->back()->with('status_penghapusan_calon', 'berhasil');
        } catch (Exception $exception) {
            return redirect()->back()->with('status_penghapusan_calon', 'gagal');
        }
    }
}
