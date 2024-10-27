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
            'nama_calon_baru' => 'required|unique:calon,nama|max:300',
            'nama_calon_wakil_baru' => 'required|unique:calon,nama|max:300',
            'posisi' => 'required|in:GUBERNUR,WALIKOTA'
        ];

        $mencalonSebagai = $this->get('posisi');

        if ($mencalonSebagai == 'GUBERNUR') {
            $rules['provinsi_id_calon_baru'] = 'required|exists:provinsi,id';
        }

        if ($mencalonSebagai == 'WALIKOTA') {
            $rules['kabupaten_id_calon_baru'] = 'required|exists:kabupaten,id';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'nama_calon_baru.required' => 'Mohon isi nama calon.',
            'nama_calon_baru.unique' => 'Calon tersebut sudah ada.',
            'nama_calon_baru.max' => 'Nama calon terlalu panjang, maksimal 300 karakter.',

            'nama_calon_wakil_baru.required' => 'Mohon isi nama calon wakil.',
            'nama_calon_wakil_baru.unique' => 'Calon wakil tersebut sudah ada.',
            'nama_calon_wakil_baru.max' => 'Nama wakil calon terlalu panjang, maksimal 300 karakter.',

            'posisi.required' => 'Mohon pilih posisi pencalonan.',
            'posisi.in' => 'Posisi pencalonan tidak ditemukan.',

            'provinsi_id_calon_baru.required' => 'Mohon pilih provinsi untuk kota tersebut.',
            'provinsi_id_calon_baru.exists' => 'Provinsi yang anda pilih tidak tersedia di database.',
            
            'kabupaten_id_calon_baru.required' => 'Mohon pilih kabupaten untuk kota tersebut.',
            'kabupaten_id_calon_baru.exists' => 'Kabupaten yang anda pilih tidak tersedia di database.',
        ];
    }
}
