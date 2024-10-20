<?php

namespace App\Http\Requests\Admin\Kabupaten;

use Illuminate\Foundation\Http\FormRequest;

class StoreKabupatenRequest extends FormRequest
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
            'nama_kabupaten_baru' => 'required|unique:kabupaten,nama|max:300',
            'provinsi_id_kabupaten_baru' => 'required|exists:provinsi,id'
        ];
    }

    public function messages()
    {
        return [
            'nama_kabupaten_baru.required' => 'Mohon isi nama kabupaten.',
            'nama_kabupaten_baru.unique' => 'Kabupaten tersebut sudah ada.',
            'nama_kabupaten_baru.max' => 'Nama kabupaten terlalu panjang, maksimal 300 karakter.',

            'provinsi_id_kabupaten_baru.required' => 'Mohon pilih provinsi untuk kota tersebut.',
            'provinsi_id_kabupaten_baru.exists' => 'Provinsi yang anda pilih tidak tersedia di database.',
        ];
    }
}
