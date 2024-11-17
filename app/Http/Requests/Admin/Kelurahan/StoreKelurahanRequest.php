<?php

namespace App\Http\Requests\Admin\Kelurahan;

use Illuminate\Foundation\Http\FormRequest;

class StoreKelurahanRequest extends FormRequest
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
            'name' => 'required|unique:kelurahan,nama|max:300',
            'kecamatan_id' => 'required|exists:kecamatan,id'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Mohon isi nama kelurahan.',
            'name.unique' => 'Kelurahan tersebut sudah ada.',
            'name.max' => 'Nama kelurahan terlalu panjang, maksimal 300 karakter.',

            'kecamatan_id.required' => 'Mohon pilih kecamatan untuk kota tersebut.',
            'kecamatan_id.exists' => 'Kecamatan yang anda pilih tidak tersedia di database.',
        ];
    }
}
