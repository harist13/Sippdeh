<?php

namespace App\Http\Requests\Admin\Kabupaten;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKabupatenRequest extends FormRequest
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
        return [
            'nama_kabupaten' => [
                'required',
                'max:300',
                Rule::unique('kabupaten', 'nama')->ignore($id)
            ],
            'provinsi_id_kabupaten' => 'required|exists:provinsi,id',
            'logo_kabupaten' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'nama_kabupaten.required' => 'Mohon isi nama kabupaten.',
            'nama_kabupaten.unique' => 'Kabupaten tersebut sudah ada.',
            'nama_kabupaten.max' => 'Nama kabupaten terlalu panjang, maksimal 300 karakter.',

            'provinsi_id_kabupaten_baru.required' => 'Mohon pilih provinsi untuk kota tersebut.',
            'provinsi_id_kabupaten_baru.exists' => 'Provinsi yang anda pilih tidak tersedia di database.',

            'logo_kabupaten.image' => 'File harus berupa gambar.',
            'logo_kabupaten.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'logo_kabupaten.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
