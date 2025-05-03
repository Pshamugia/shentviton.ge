<?php

namespace App\Http\Requests;

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
        'name' => 'required|string|min:2|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string',
        'address' => 'required|string|max:255',
        'city' => 'required|string|max:255',            // 🛑 Add city
        'delivery_price' => 'required|numeric|min:0',    // 🛑 Add delivery_price
    ];
}

}
