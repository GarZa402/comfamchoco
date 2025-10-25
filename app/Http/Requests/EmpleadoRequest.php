<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpleadoRequest extends FormRequest
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
            'nombre' => 'required|string|max:255',
            'cedula' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'cargo' => 'required|string|max:255',
            'tipo_empleado' => 'required|in:administrativo,operativo,directivo',
            'politica_id' => 'nullable|exists:politicas,id'
        ];

        // For update, make cedula and email unique except for the current record
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['cedula'] .= ',unique:empleados,cedula,' . $this->route('empleado');
            $rules['email'] .= ',unique:empleados,email,' . $this->route('empleado');
        } else {
            $rules['cedula'] .= '|unique:empleados';
            $rules['email'] .= '|unique:empleados';
        }

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
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            
            'cedula.required' => 'La cédula es obligatoria.',
            'cedula.string' => 'La cédula debe ser una cadena de texto.',
            'cedula.max' => 'La cédula no puede tener más de 20 caracteres.',
            'cedula.unique' => 'Ya existe un empleado con esta cédula.',
            
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
            'email.unique' => 'Ya existe un empleado con este correo electrónico.',
            
            'cargo.required' => 'El cargo es obligatorio.',
            'cargo.string' => 'El cargo debe ser una cadena de texto.',
            'cargo.max' => 'El cargo no puede tener más de 255 caracteres.',
            
            'tipo_empleado.required' => 'El tipo de empleado es obligatorio.',
            'tipo_empleado.in' => 'El tipo de empleado debe ser uno de los siguientes valores: administrativo, operativo, directivo.',
            
            'politica_id.exists' => 'La política seleccionada no existe.'
        ];
    }
}
