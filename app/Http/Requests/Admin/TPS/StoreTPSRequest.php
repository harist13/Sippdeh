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
            'nama_tps_baru' => 'required|unique:tps,nama|max:300',
            'kelurahan_id_tps_baru' => 'required|exists:kelurahan,id'
        ];
    }

    public function messages()
    {
        return [
            'nama_tps_baru.required' => 'Mohon isi nama tps.',
            'nama_tps_baru.unique' => 'TPS tersebut sudah ada.',
            'nama_tps_baru.max' => 'Nama TPS terlalu panjang, maksimal 300 karakter.',

            'kelurahan_id_tps_baru.required' => 'Mohon pilih kelurahan untuk kota tersebut.',
            'kelurahan_id_tps_baru.exists' => 'Kelurahan yang anda pilih tidak tersedia di database.',
        ];
    }
}
