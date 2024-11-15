<?php

namespace App\Http\Requests\Admin\Provinsi;

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
            'name' => [
                'required',
                'max:300',
                Rule::unique('provinsi', 'nama')->ignore($id)
            ],
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Mohon isi nama provinsi.',
            'name.unique' => 'Provinsi tersebut sudah ada.',
            'name.max' => 'Nama provinsi terlalu panjang, maksimal 300 karakter.',
        ];
    }
}
