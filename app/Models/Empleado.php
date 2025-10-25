<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Empleado extends Model
{
    protected $fillable = [
        'nombre',
        'cedula',
        'email',
        'cargo',
        'tipo_empleado',
        'politica_id'
    ];

    protected $casts = [
        'tipo_empleado' => 'enum:administrativo,operativo,directivo'
    ];

    /**
     * Get the user associated with the employee.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the permissions for the employee.
     */
    public function permisos(): HasMany
    {
        return $this->hasMany(Permiso::class);
    }

    /**
     * Get the policy associated with the employee.
     */
    public function politica(): BelongsTo
    {
        return $this->belongsTo(Politica::class);
    }

    /**
     * Calculate available vacation days
     */
    public function diasDisponibles()
    {
        $politica = $this->politica;
        if (!$politica) {
            return 0;
        }

        $usados = $this->permisos()
            ->where('tipo', 'vacaciones')
            ->where('estado', 'aprobado')
            ->whereYear('fecha_inicio', date('Y'))
            ->sum(DB::raw('DATEDIFF(fecha_fin, fecha_inicio) + 1'));

        return max(0, $politica->dias_disponibles_anuales - $usados);
    }
}
