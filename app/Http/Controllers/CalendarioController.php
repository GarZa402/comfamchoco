<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarioController extends Controller
{
    /**
     * Muestra el calendario compartido de ausencias
     */
    public function index(Request $request)
    {
        $query = Permiso::with(['empleado'])
            ->where('estado', 'aprobado');
        
        // Filtrar por departamento si se especifica
        if ($request->filled('departamento')) {
            $query->whereHas('empleado', function($q) use ($request) {
                $q->where('cargo', $request->departamento);
            });
        }
        
        // Filtrar por tipo de permiso si se especifica
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        
        // Obtener todos los permisos aprobados
        $permisos = $query->get();
        
        // Formatear los permisos para el calendario
        $eventos = [];
        foreach ($permisos as $permiso) {
            $color = $this->getColorByTipo($permiso->tipo);
            
            $eventos[] = [
                'id' => $permiso->id,
                'title' => $permiso->empleado->nombre . ' - ' . ucfirst($permiso->tipo),
                'start' => $permiso->fecha_inicio->format('Y-m-d'),
                'end' => $permiso->fecha_fin->addDay()->format('Y-m-d'), // Se añade un día para que FullCalendar muestre correctamente el último día
                'color' => $color,
                'url' => route('permisos.show', $permiso->id)
            ];
        }
        
        // Obtener todos los departamentos para el filtro
        $departamentos = Empleado::select('cargo')->distinct()->pluck('cargo');
        
        return view('calendario.index', compact('eventos', 'departamentos'));
    }
    
    /**
     * Asigna un color según el tipo de permiso
     */
    private function getColorByTipo($tipo)
    {
        switch ($tipo) {
            case 'vacaciones':
                return '#4CAF50'; // Verde
            case 'licencia':
                return '#2196F3'; // Azul
            case 'permiso':
                return '#FF9800'; // Naranja
            default:
                return '#9E9E9E'; // Gris
        }
    }
}