<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportProvinsiRequest extends FormRequest
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
            'spreadsheet' => 'required|mimes:csv|size:2048'
        ];
    }

    public function messages()
    {
        return [
            'spreadsheet.required' => 'Mohon pilih berkas .csv untuk diimpor.',
            'spreadsheet.mimes' => 'Berkas yang didukung hanya yang bertipe .csv.',
            'spreadsheet.size' => 'Ukuran berkas maksimal hanya 2 MB.',
        ];
    }
}
