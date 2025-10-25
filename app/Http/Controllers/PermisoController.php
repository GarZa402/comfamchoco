<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PermisoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Permiso::with(['empleado', 'aprobador']);
        
        if ($request->filled('empleado_id')) {
            $query->where('empleado_id', $request->empleado_id);
        }
        
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        
        $permisos = $query->latest()->paginate(10);
        $empleados = Empleado::all();
        
        return view('permisos.index', compact('permisos', 'empleados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $empleados = Empleado::all();
        return view('permisos.create', compact('empleados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'tipo' => 'required|in:vacaciones,licencia,permiso',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'motivo' => 'required|string|max:500'
        ]);

        // Check if employee has enough available days
        $empleado = Empleado::findOrFail($request->empleado_id);
        
        if ($request->tipo === 'vacaciones') {
            $diasSolicitados = Carbon::parse($request->fecha_inicio)->diffInDays(Carbon::parse($request->fecha_fin)) + 1;
            $disponibles = $empleado->diasDisponibles();
            
            if ($diasSolicitados > $disponibles) {
                return back()->withInput()->withErrors([
                    'error' => "El empleado solo tiene {$disponibles} días disponibles, pero está solicitando {$diasSolicitados} días."
                ]);
            }
        }

        DB::beginTransaction();
        try {
            $permiso = Permiso::create([
                'empleado_id' => $request->empleado_id,
                'tipo' => $request->tipo,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'motivo' => $request->motivo,
                'estado' => 'pendiente',
                // No establecemos aprobado_por aquí porque está pendiente
                'solicitado_por' => Auth::id() // Guardamos quién solicitó el permiso
            ]);
            
            DB::commit();
            return redirect()->route('permisos.index')->with('success', 'Solicitud de permiso creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error al crear la solicitud: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $permiso = Permiso::with(['empleado', 'aprobador', 'solicitante'])->findOrFail($id);
        return view('permisos.show', compact('permiso'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $permiso = Permiso::findOrFail($id);
        $empleados = Empleado::all();
        return view('permisos.edit', compact('permiso', 'empleados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $permiso = Permiso::findOrFail($id);
        
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'tipo' => 'required|in:vacaciones,licencia,permiso',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'motivo' => 'required|string|max:500',
            'estado' => 'required|in:pendiente,aprobado,rechazado'
        ]);

        // Check if employee has enough available days (only if changing to approved)
        if ($request->estado === 'aprobado' && $permiso->estado !== 'aprobado') {
            $empleado = Empleado::findOrFail($request->empleado_id);
            
            if ($request->tipo === 'vacaciones') {
                $diasSolicitados = Carbon::parse($request->fecha_inicio)->diffInDays(Carbon::parse($request->fecha_fin)) + 1;
                $disponibles = $empleado->diasDisponibles();
                
                if ($diasSolicitados > $disponibles) {
                    return back()->withInput()->withErrors([
                        'error' => "El empleado solo tiene {$disponibles} días disponibles, pero está solicitando {$diasSolicitados} días."
                    ]);
                }
            }
        }

        DB::beginTransaction();
        try {
            $updateData = $request->all();
            
            // If status is being changed to approved or rejected, set the approver
            if (($request->estado === 'aprobado' || $request->estado === 'rechazado') && 
                $permiso->estado !== $request->estado) {
                $updateData['aprobado_por'] = Auth::id();
                $updateData['fecha_aprobacion'] = now();
            }
            
            $permiso->update($updateData);
            
            // If approved, send notification
            if ($request->estado === 'aprobado' && $permiso->estado !== 'aprobado') {
                // TODO: Implement notification system
            }
            
            DB::commit();
            return redirect()->route('permisos.index')->with('success', 'Solicitud de permiso actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error al actualizar la solicitud: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permiso = Permiso::findOrFail($id);
        
        // Only allow deletion if not approved
        if ($permiso->estado === 'aprobado') {
            return back()->withErrors(['error' => 'No se puede eliminar una solicitud aprobada.']);
        }
        
        DB::beginTransaction();
        try {
            $permiso->delete();
            DB::commit();
            return redirect()->route('permisos.index')->with('success', 'Solicitud eliminada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al eliminar la solicitud: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Display permissions for the authenticated employee.
     */
    public function misPermisos(Request $request)
    {
        // Verificar si el usuario autenticado tiene un empleado asociado
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('warning', 'Por favor inicie sesión.');
        }
        
        $empleado = $user->empleado;
        
        if (!$empleado) {
            return redirect()->route('empleados.create')->with('warning', 'Por favor complete su información de empleado.');
        }
        
        $query = Permiso::where('empleado_id', $empleado->id);
        
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        
        $permisos = $query->latest()->paginate(10);
        
        return view('permisos.mis_permisos', compact('permisos'));
    }
    
    /**
     * Approve a permission request.
     */
    public function aprobar(Request $request, $id)
    {
        $permiso = Permiso::findOrFail($id);
        
        // Check if the permission is still pending
        if ($permiso->estado !== 'pendiente') {
            return back()->withErrors(['error' => 'Esta solicitud ya ha sido procesada.']);
        }
        
        // Check if the user has permission to approve this request
        $user = Auth::user();
        if (!$user || ($user->role !== 'supervisor' && $user->role !== 'rrhh')) {
            return back()->withErrors(['error' => 'No tienes permiso para aprobar esta solicitud.']);
        }
        
        DB::beginTransaction();
        try {
            $permiso->update([
                'estado' => 'aprobado',
                'aprobado_por' => $user->id,
                'fecha_aprobacion' => now()
            ]);
            
            // TODO: Send notification to the employee
            
            DB::commit();
            return back()->with('success', 'Solicitud aprobada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al aprobar la solicitud: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Reject a permission request.
     */
    public function rechazar(Request $request, $id)
    {
        $request->validate([
            'motivo_rechazo' => 'required|string|max:500'
        ]);
        
        $permiso = Permiso::findOrFail($id);
        
        // Check if the permission is still pending
        if ($permiso->estado !== 'pendiente') {
            return back()->withErrors(['error' => 'Esta solicitud ya ha sido procesada.']);
        }
        
        // Check if the user has permission to reject this request
        $user = Auth::user();
        if (!$user || ($user->role !== 'supervisor' && $user->role !== 'rrhh')) {
            return back()->withErrors(['error' => 'No tienes permiso para rechazar esta solicitud.']);
        }
        
        DB::beginTransaction();
        try {
            $permiso->update([
                'estado' => 'rechazado',
                'aprobado_por' => $user->id,
                'fecha_aprobacion' => now(),
                'motivo_rechazo' => $request->motivo_rechazo
            ]);
            
            // TODO: Send notification to the employee
            
            DB::commit();
            return back()->with('success', 'Solicitud rechazada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al rechazar la solicitud: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Generate permissions report.
     */
    public function reportePermisos(Request $request)
    {
        $query = Permiso::with(['empleado', 'aprobador']);
        
        if ($request->filled('fecha_inicio')) {
            $query->where('fecha_inicio', '>=', $request->fecha_inicio);
        }
        
        if ($request->filled('fecha_fin')) {
            $query->where('fecha_fin', '<=', $request->fecha_fin);
        }
        
        if ($request->filled('empleado_id')) {
            $query->where('empleado_id', $request->empleado_id);
        }
        
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        
        $permisos = $query->latest()->get();
        
        // Calculate statistics
        $totalPermisos = $permisos->count();
        $aprobados = $permisos->where('estado', 'aprobado')->count();
        $pendientes = $permisos->where('estado', 'pendiente')->count();
        $rechazados = $permisos->where('estado', 'rechazado')->count();
        
        $empleados = Empleado::all();
        
        return view('permisos.reporte', compact(
            'permisos',
            'totalPermisos',
            'aprobados',
            'pendientes',
            'rechazados',
            'empleados'
        ));
    }
}