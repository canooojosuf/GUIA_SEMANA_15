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
            'monto' => ['required', 'numeric', 'gt:0'],
            'estado' => ['required', 'string'],
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
            'monto.gt' => 'El monto debe ser mayor a cero.', // <--- Mensaje solicitado
            'estado.required' => 'El estado es obligatorio.',
            'estado.string' => 'El estado debe ser un texto.',
        ];
    }
}
