<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransaccionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comercio_id' => [
                'required',
                Rule::exists('comercios', 'id'), // <-- Simplificado temporalmente para la API
            ],
            'cliente_nombre' => ['required', 'string', 'max:255'],
            'monto' => ['required', 'numeric','decimal:0,2','max:99999999.99', 'min:0.01'],
            'estado' => ['prohibited'],
        ];
    }
    public function messages(): array
    {
        return [
            'comercio_id.required' => 'El comercio es obligatorio.',
            'comercio_id.exists' => 'El comercio seleccionado no existe.',
            'cliente_nombre.required' => 'Debes indicar el nombre del cliente.',
            'monto.required' => 'Debes indicar un monto.',
            'monto.numeric' => 'El monto debe ser un número.',
            'monto.max' => 'El monto no puede superar 99,999,999.99.',
            'monto.decimal' => 'El monto admite como máximo dos decimales.',
            'monto.min' => 'El monto debe ser mayor o igual a 0.01.', // <--- Mensaje solicitado
            'estado.prohibited' => 'El estado no está permitido.',
            'estado.string' => 'El estado debe ser un texto.',
        ];
    }
}
