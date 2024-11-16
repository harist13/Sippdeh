<?php

namespace App\Http\Requests\Admin\TPS;

use Illuminate\Foundation\Http\FormRequest;

class StoreTPSRequest extends FormRequest
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
            'name' => 'required|unique:tps,nama|max:300',
            'dpt' => 'required',
            'kelurahan_id' => 'required|exists:kelurahan,id'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Mohon isi nama tps.',
            'name.unique' => 'TPS tersebut sudah ada.',
            'name.max' => 'Nama TPS terlalu panjang, maksimal 300 karakter.',

            'dpt.required' => 'Mohon isi DPT.',

            'kelurahan_id.required' => 'Mohon pilih kelurahan untuk kota tersebut.',
            'kelurahan_id.exists' => 'Kelurahan yang anda pilih tidak tersedia di database.',
        ];
    }
}
