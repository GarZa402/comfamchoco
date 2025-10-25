<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permiso extends Model
{
    protected $fillable = [
        'empleado_id',
        'tipo',
        'fecha_inicio',
        'fecha_fin',
        'motivo',
        'estado',
        'aprobado_por',
        'observaciones'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'estado' => 'enum:pendiente,aprobado,rechazado'
    ];

    /**
     * Get the employee that owns the permission.
     */
    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    /**
     * Get the user who approved the permission.
     */
    public function aprobador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }

    /**
     * Get the policy associated with the permission.
     */
    public function politica(): BelongsTo
    {
        return $this->belongsTo(Politica::class);
    }

    /**
     * Calculate the number of days for this permission
     */
    public function diasTotales()
    {
        return $this->fecha_fin->diffInDays($this->fecha_inicio) + 1;
    }
}
