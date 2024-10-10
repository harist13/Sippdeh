<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProvinsiRequest extends FormRequest
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
            'nama_provinsi_baru' => 'required|unique:provinsi,nama|max:300'
        ];
    }

    public function messages()
    {
        return [
            'nama_provinsi_baru.required' => 'Mohon isi nama provinsi.',
            'nama_provinsi_baru.unique' => 'Provinsi tersebut sudah ada.',
            'nama_provinsi_baru.max' => 'Nama provinsi terlalu panjang, maksimal 300 karakter.',
        ];
    }
}
