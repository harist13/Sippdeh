<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTPSRequest extends FormRequest
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
            'nama_tps' => [
                'required',
                'max:300',
                Rule::unique('tps', 'nama')->ignore($id)
            ],
            'kelurahan_id_tps' => 'required|exists:kecamatan,id'
        ];
    }

    public function messages()
    {
        return [
            'nama_tps.required' => 'Mohon isi nama TPS.',
            'nama_tps.unique' => 'Kelurahan tersebut sudah ada.',
            'nama_tps.max' => 'Nama TPS terlalu panjang, maksimal 300 karakter.',

            'kelurahan_id_tps.required' => 'Mohon pilih kecamatan untuk kota tersebut.',
            'kelurahan_id_tps.exists' => 'Kecamatan yang anda pilih tidak tersedia di database.',
        ];
    }
}
