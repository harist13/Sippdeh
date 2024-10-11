<?php

namespace App\Http\Requests;

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
        return [
            'nama_calon' => [
                'required',
                'max:300',
                Rule::unique('calon', 'nama')->ignore($id)
            ],
            'kabupaten_id_calon' => 'required|exists:kabupaten,id'
        ];
    }

    public function messages()
    {
        return [
            'nama_calon.required' => 'Mohon isi nama kabupaten.',
            'nama_calon.unique' => 'Kabupaten tersebut sudah ada.',
            'nama_calon.max' => 'Nama kabupaten terlalu panjang, maksimal 300 karakter.',

            'kabupaten_id_calon.required' => 'Mohon pilih kabupaten untuk kota tersebut.',
            'kabupaten_id_calon.exists' => 'Kabupaten yang anda pilih tidak tersedia di database.',
        ];
    }
}
