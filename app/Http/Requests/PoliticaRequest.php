<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PoliticaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization will be handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'tipo_empleado' => 'required|in:administrativo,operativo,directivo',
            'dias_disponibles_anuales' => 'required|integer|min:0',
            'reglas_especiales' => 'nullable|string|max:1000'
        ];

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'tipo_empleado.required' => 'El tipo de empleado es obligatorio.',
            'tipo_empleado.in' => 'El tipo de empleado debe ser uno de los siguientes valores: administrativo, operativo, directivo.',
            
            'dias_disponibles_anuales.required' => 'Los días disponibles anuales son obligatorios.',
            'dias_disponibles_anuales.integer' => 'Los días disponibles anuales deben ser un número entero.',
            'dias_disponibles_anuales.min' => 'Los días disponibles anuales no pueden ser negativos.',
            
            'reglas_especiales.string' => 'Las reglas especiales deben ser una cadena de texto.',
            'reglas_especiales.max' => 'Las reglas especiales no pueden tener más de 1000 caracteres.'
        ];
    }
}
