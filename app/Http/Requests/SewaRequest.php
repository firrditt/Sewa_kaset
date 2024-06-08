<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SewaRequest extends FormRequest
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
            // 'kasetId' => ['required'], // Pastikan kaset_id ada di tabel kaset
            'tgl_sewa' => ['required'], // Format tanggal: YYYY-MM-DD
            'hrg_sewa' => ['required'], // Harga sewa harus angka dan minimal 0
            ];
    }
}
