<?php

namespace App\Http\Requests\Admin\Calon;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCalonRequest extends FormRequest
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
        $id = last(explode('/', $this->path()));
        $posisi = $this->get('posisi');

        $rules = [
            'no_urut' => 'required|integer|min:1',
            'nama_calon' => [
                'required',
                'max:300'
            ],
            'nama_calon_wakil' => [
                'required',
                'max:300'
            ],
            'posisi' => 'required|in:GUBERNUR,WALIKOTA,BUPATI',
            'foto_calon' => 'nullable|image|mimes:jpeg,png,jpg|dimensions:width=200,height=300'
        ];

        if ($posisi == 'GUBERNUR') {
            $rules['provinsi_id_calon'] = 'required|exists:provinsi,id';
            // Validasi unique untuk no_urut per provinsi, kecuali untuk ID yang sedang diedit
            $rules['no_urut'] = [
                'required',
                'integer',
                'min:1',
                Rule::unique('calon', 'no_urut')
                    ->ignore($id)
                    ->where(function ($query) {
                        return $query->where('posisi', 'GUBERNUR')
                                   ->where('provinsi_id', $this->get('provinsi_id_calon'));
                    })
            ];
        }

        if ($posisi == 'WALIKOTA' || $posisi == 'BUPATI') {
            $rules['kabupaten_id_calon'] = 'required|exists:kabupaten,id';
            // Validasi unique untuk no_urut per kabupaten, kecuali untuk ID yang sedang diedit
            $rules['no_urut'] = [
                'required',
                'integer',
                'min:1',
                Rule::unique('calon', 'no_urut')
                    ->ignore($id)
                    ->where(function ($query) {
                        return $query->where('posisi', $this->get('posisi'))
                                   ->where('kabupaten_id', $this->get('kabupaten_id_calon'));
                    })
            ];
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

            'nama_calon.required' => 'Mohon isi nama calon.',
            'nama_calon.max' => 'Nama calon terlalu panjang, maksimal 300 karakter.',

            'nama_calon_wakil.required' => 'Mohon isi nama calon wakil.',
            'nama_calon_wakil.max' => 'Nama calon wakil terlalu panjang, maksimal 300 karakter.',
            
            'posisi.required' => 'Mohon pilih posisi pencalonan.',
            'posisi.in' => 'Posisi pencalonan tidak ditemukan.',

            'provinsi_id_calon.required' => 'Mohon pilih provinsi.',
            'provinsi_id_calon.exists' => 'Provinsi yang anda pilih tidak tersedia di database.',
            
            'kabupaten_id_calon.required' => 'Mohon pilih kabupaten.',
            'kabupaten_id_calon.exists' => 'Kabupaten yang anda pilih tidak tersedia di database.',

            'foto_calon.image' => 'File harus berupa gambar.',
            'foto_calon.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'foto_calon.dimensions' => 'Ukuran gambar harus 200x300 pixel.'
        ];
    }
}