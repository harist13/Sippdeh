<?php

namespace App\Http\Requests\Admin\Provinsi;

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
            'name' => 'required|unique:provinsi,nama|max:300',
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
