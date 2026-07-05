<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'event_id' => 'required|integer|exists:events,id',
            'quantity' => 'required|integer|min:1|max:100',
            'promo_code' => 'nullable|string|exists:promos,promo_code',
            'payment_method' => 'required|in:bank_transfer,e_wallet,credit_card',
        ];
    }

    public function messages(): array
    {
        return [
            'event_id.required' => 'Event ID wajib diisi',
            'event_id.exists' => 'Event tidak ditemukan',
            'quantity.required' => 'Jumlah tiket wajib diisi',
            'quantity.min' => 'Jumlah tiket minimal 1',
            'quantity.max' => 'Jumlah tiket maksimal 100',
            'promo_code.exists' => 'Kode promo tidak valid',
            'payment_method.required' => 'Metode pembayaran wajib diisi',
            'payment_method.in' => 'Metode pembayaran tidak valid',
        ];
    }
}
