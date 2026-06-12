<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'payment_proof.file' => 'Bukti pembayaran harus berupa file',
            'payment_proof.mimes' => 'Bukti pembayaran harus berupa jpg, jpeg, png, atau pdf',
            'payment_proof.max' => 'Bukti pembayaran maksimal 2MB',
        ];
    }
}
