<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Politica extends Model
{
    protected $fillable = [
        'tipo_empleado',
        'dias_disponibles_anuales',
        'reglas_especiales'
    ];

    protected $casts = [
        'tipo_empleado' => 'enum:administrativo,operativo,directivo',
        'dias_disponibles_anuales' => 'integer'
    ];

    /**
     * Get the employees for the policy.
     */
    public function empleados(): HasMany
    {
        return $this->hasMany(Empleado::class);
    }
}
