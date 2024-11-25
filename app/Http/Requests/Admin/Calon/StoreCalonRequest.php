<?php

namespace App\Http\Requests\Admin\Calon;

use Illuminate\Foundation\Http\FormRequest;

class StoreCalonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'no_urut' => 'required|integer|min:1',
            'nama_calon_baru' => 'required|max:300',
            'nama_calon_wakil_baru' => 'required|max:300',
            'posisi' => 'required|in:GUBERNUR,WALIKOTA,BUPATI',
            'foto_calon_baru' => 'nullable|image|mimes:jpeg,png,jpg'
        ];

        $mencalonSebagai = $this->get('posisi');

        if ($mencalonSebagai == 'GUBERNUR') {
            $rules['provinsi_id_calon_baru'] = 'required|exists:provinsi,id';
            // Validasi unique untuk no_urut per provinsi
            $rules['no_urut'] .= '|unique:calon,no_urut,NULL,id,posisi,GUBERNUR,provinsi_id,' . $this->get('provinsi_id_calon_baru');
        }

        if ($mencalonSebagai == 'WALIKOTA' || $mencalonSebagai == 'BUPATI') {
            $rules['kabupaten_id_calon_baru'] = 'required|exists:kabupaten,id';
            // Validasi unique untuk no_urut per kabupaten
            $rules['no_urut'] .= '|unique:calon,no_urut,NULL,id,posisi,' . $mencalonSebagai . ',kabupaten_id,' . $this->get('kabupaten_id_calon_baru');
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'no_urut.required' => 'Nomor urut harus diisi.',
            'no_urut.integer' => 'Nomor urut harus berupa angka.',
            'no_urut.min' => 'Nomor urut minimal 1.',
            'no_urut.unique' => 'Nomor urut sudah digunakan untuk wilayah ini.',

            'nama_calon_baru.required' => 'Mohon isi nama calon.',
            'nama_calon_baru.max' => 'Nama calon terlalu panjang, maksimal 300 karakter.',

            'nama_calon_wakil_baru.required' => 'Mohon isi nama calon wakil.',
            'nama_calon_wakil_baru.max' => 'Nama wakil calon terlalu panjang, maksimal 300 karakter.',

            'posisi.required' => 'Mohon pilih posisi pencalonan.',
            'posisi.in' => 'Posisi pencalonan tidak ditemukan.',

            'provinsi_id_calon_baru.required' => 'Mohon pilih provinsi.',
            'provinsi_id_calon_baru.exists' => 'Provinsi yang anda pilih tidak tersedia di database.',
            
            'kabupaten_id_calon_baru.required' => 'Mohon pilih kabupaten.',
            'kabupaten_id_calon_baru.exists' => 'Kabupaten yang anda pilih tidak tersedia di database.',

            'foto_calon_baru.image' => 'File harus berupa gambar.',
            'foto_calon_baru.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'foto_calon_baru.dimensions' => 'Ukuran gambar harus 200x300 pixel.'
        ];
    }
}