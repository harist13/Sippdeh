<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKecamatanRequest extends FormRequest
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
        return [
            'nama_kecamatan_baru' => 'required|unique:kecamatan,nama|max:300',
            'kabupaten_id_kecamatan_baru' => 'required|exists:kabupaten,id'
        ];
    }

    public function messages()
    {
        return [
            'nama_kecamatan_baru.required' => 'Mohon isi nama kabupaten.',
            'nama_kecamatan_baru.unique' => 'Kabupaten tersebut sudah ada.',
            'nama_kecamatan_baru.max' => 'Nama kabupaten terlalu panjang, maksimal 300 karakter.',

            'kabupaten_id_kecamatan_baru.required' => 'Mohon pilih kabupaten untuk kota tersebut.',
            'kabupaten_id_kecamatan_baru.exists' => 'Provinsi yang anda pilih tidak tersedia di database.',
        ];
    }
}
