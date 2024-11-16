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
            'name' => [
                'required',
                'max:300',
                Rule::unique('kabupaten', 'nama')->ignore($id)
            ],
            'provinsi_id' => 'required|exists:provinsi,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Mohon isi nama kabupaten/kota.',
            'name.unique' => 'Kabupaten/kota tersebut sudah ada.',
            'name.max' => 'Nama kabupaten terlalu panjang, maksimal 300 karakter.',

            'provinsi_id.required' => 'Mohon pilih provinsi untuk kabupaten/kota tersebut.',
            'provinsi_id.exists' => 'Provinsi yang anda pilih tidak tersedia di database.',

            'logo.image' => 'File harus berupa gambar.',
            'logo.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'logo.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
