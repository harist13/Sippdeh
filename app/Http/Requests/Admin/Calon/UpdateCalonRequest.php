<?php

namespace App\Http\Requests\Admin\Calon;

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
            'nama_calon_wakil' => [
                'required',
                'max:300',
                Rule::unique('calon', 'nama_wakil')->ignore($id)
            ],
            'kabupaten_id_calon' => 'required|exists:kabupaten,id'
        ];
    }

    public function messages()
    {
        return [
            'nama_calon.required' => 'Mohon isi nama calon.',
            'nama_calon.unique' => 'Kabupaten tersebut sudah ada.',
            'nama_calon.max' => 'Nama calon terlalu panjang, maksimal 300 karakter.',

            'nama_calon.required' => 'Mohon isi nama calon wakil.',
            'nama_calon.unique' => 'Calon wakil tersebut sudah ada.',
            'nama_calon.max' => 'Nama calon wakil terlalu panjang, maksimal 300 karakter.',

            'kabupaten_id_calon.required' => 'Mohon pilih kabupaten untuk kota tersebut.',
            'kabupaten_id_calon.exists' => 'Kabupaten yang anda pilih tidak tersedia di database.',
        ];
    }
}
