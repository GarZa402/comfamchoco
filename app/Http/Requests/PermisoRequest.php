<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermisoRequest extends FormRequest
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
            'empleado_id' => 'required|exists:empleados,id',
            'tipo' => 'required|in:vacaciones,licencia,permiso',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'motivo' => 'required|string|max:500',
            'estado' => 'required|in:pendiente,aprobado,rechazado'
        ];

        // For update, make sure we don't allow changing the employee_id if it's already approved
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['empleado_id'] = 'sometimes|exists:empleados,id';
            
            // If the permission is already approved, don't allow changing certain fields
            if ($this->route('permiso') && $this->route('permiso')->estado === 'aprobado') {
                $rules['tipo'] = 'sometimes|in:vacaciones,licencia,permiso';
                $rules['fecha_inicio'] = 'sometimes|date';
                $rules['fecha_fin'] = 'sometimes|date|after_or_equal:fecha_inicio';
                $rules['motivo'] = 'sometimes|string|max:500';
            }
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
            'empleado_id.required' => 'El empleado es obligatorio.',
            'empleado_id.exists' => 'El empleado seleccionado no existe.',
            
            'tipo.required' => 'El tipo de permiso es obligatorio.',
            'tipo.in' => 'El tipo de permiso debe ser uno de los siguientes valores: vacaciones, licencia, permiso.',
            
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
            
            'fecha_fin.required' => 'La fecha de fin es obligatoria.',
            'fecha_fin.date' => 'La fecha de fin debe ser una fecha válida.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
            
            'motivo.required' => 'El motivo es obligatorio.',
            'motivo.string' => 'El motivo debe ser una cadena de texto.',
            'motivo.max' => 'El motivo no puede tener más de 500 caracteres.',
            
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado debe ser uno de los siguientes valores: pendiente, aprobado, rechazado.'
        ];
    }
}
