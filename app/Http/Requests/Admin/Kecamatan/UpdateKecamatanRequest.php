<?php

namespace App\Http\Requests\Admin\Kecamatan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKecamatanRequest extends FormRequest
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
                Rule::unique('kecamatan', 'nama')->ignore($id)
            ],
            'kabupaten_id' => 'required|exists:kabupaten,id'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Mohon isi nama kecamatan.',
            'name.unique' => 'Kecamatan tersebut sudah ada.',
            'name.max' => 'Nama kecamatan terlalu panjang, maksimal 300 karakter.',

            'kabupaten_id.required' => 'Mohon pilih kabupaten untuk kota tersebut.',
            'kabupaten_id.exists' => 'Kabupaten yang anda pilih tidak tersedia di database.',
        ];
    }
}
