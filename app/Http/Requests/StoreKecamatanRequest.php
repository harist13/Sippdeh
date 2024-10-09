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
            'nama' => 'required|unique:kabupaten|max:300',
            'kabupaten_id' => 'required|exists:kabupaten,id'
        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Mohon isi nama kabupaten.',
            'nama.unique' => 'Kabupaten tersebut sudah ada.',
            'nama.max' => 'Nama kabupaten terlalu panjang, maksimal 300 karakter.',

            'kabupaten_id.required' => 'Mohon pilih kabupaten untuk kota tersebut.',
            'kabupaten_id.exists' => 'Provinsi yang anda pilih tidak tersedia di database.',
        ];
    }
}
