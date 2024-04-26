<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransaksiPenjualanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            /**
             * Rule for t_penjualan table
             */
            'user_id' => 'bail|required|exists:m_user',
            'pembeli' => 'bail|required|string|max:50',
            'penjualan_kode' => 'required',
            'penjualan_tanggal' => 'bail|required|date',

            /**
             * Rule For t_penjualan_detail
             */
            'penjualan_id' => 'bail|required|exists:t_penjualan',
            'barang_id' => 'bail|required|exists:m_barang',
            'harga' => 'required|integer',
            'jumlah' => 'required',
        ];
    }
}