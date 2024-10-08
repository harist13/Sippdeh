<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProvinsiRequest extends FormRequest
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
            'nama' => [
                'required',
                'max:300',
                Rule::unique('provinsi', 'nama')->ignore($id)
            ]
        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Mohon isi nama provinsi.',
            'nama.unique' => 'Provinsi tersebut sudah ada.',
            'nama.max' => 'Nama provinsi terlalu panjang, maksimal 300 karakter.',
        ];
    }
}