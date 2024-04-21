<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "merek" => ['required'],
            "model" => ['required'],
            "no_plat" => ['required'],
            "tarif_sewa" => ['required'],
            "qty" => ['required', 'integer'],
            "gambar" => ['nullable'],
        ];
    }
}
