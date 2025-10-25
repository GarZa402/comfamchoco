<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role.
     */
    public function index()
    {
        $user = auth()->user();
        
        switch ($user->role) {
            case 'empleado':
                return $this->employeeDashboard();
            case 'supervisor':
                return $this->supervisorDashboard();
            case 'rrhh':
                return $this->hrDashboard();
            default:
                return redirect()->route('login');
        }
    }
    
    /**
     * Employee dashboard
     */
    private function employeeDashboard()
    {
        $user = auth()->user();
        $empleado = $user->empleado;
        
        if (!$empleado) {
            return redirect()->route('empleados.create')->with('warning', 'Por favor complete su información de empleado.');
        }
        
        // Get employee's permissions
        $permisos = Permiso::where('empleado_id', $empleado->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        // Get available vacation days
        $diasDisponibles = $empleado->diasDisponibles();
        
        // Get upcoming permissions
        $proximosPermisos = Permiso::where('empleado_id', $empleado->id)
            ->where('fecha_inicio', '>=', Carbon::today())
            ->orderBy('fecha_inicio')
            ->take(5)
            ->get();
            
        return view('dashboard.employee', compact('empleado', 'permisos', 'diasDisponibles', 'proximosPermisos'));
    }
    
    /**
     * Supervisor dashboard
     */
    private function supervisorDashboard()
    {
        $user = auth()->user();
        
        // Get pending permissions for approval
        $pendientes = Permiso::where('estado', 'pendiente')
            ->whereHas('empleado', function($query) {
                // Only show permissions from employees under this supervisor
                // This would need to be implemented based on your organizational structure
                $query->where('tipo_empleado', 'operativo'); // Example: only operational employees
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        // Get recent approvals
        $aprobados = Permiso::where('estado', 'aprobado')
            ->where('aprobado_por', $user->id)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
            
        // Get team statistics
        $equipo = Empleado::where('tipo_empleado', 'operativo') // Example: only operational employees
            ->count();
            
        return view('dashboard.supervisor', compact('pendientes', 'aprobados', 'equipo'));
    }
    
    /**
     * HR dashboard
     */
    private function hrDashboard()
    {
        // Get all pending permissions
        $pendientes = Permiso::where('estado', 'pendiente')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        // Get all approved permissions for the current month
        $permisosMes = Permiso::whereMonth('fecha_inicio', Carbon::now()->month)
            ->whereYear('fecha_inicio', Carbon::now()->year)
            ->count();
            
        // Get all permissions by type
        $permisosPorTipo = Permiso::select('tipo', DB::raw('count(*) as total'))
            ->whereYear('fecha_inicio', Carbon::now()->year)
            ->groupBy('tipo')
            ->get();
            
        // Get employee statistics
        $totalEmpleados = Empleado::count();
        $empleadosPorTipo = Empleado::select('tipo_empleado', DB::raw('count(*) as total'))
            ->groupBy('tipo_empleado')
            ->get();
            
        // Get recent employees
        $empleadosRecientes = Empleado::orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Get policies
        $politicas = \App\Models\Politica::orderBy('updated_at', 'desc')
            ->take(5)
            ->get();
            
        return view('dashboard.hr', compact(
            'pendientes',
            'permisosMes',
            'permisosPorTipo',
            'totalEmpleados',
            'empleadosPorTipo',
            'empleadosRecientes',
            'politicas'
        ));
    }
    
    /**
     * Admin dashboard (for HR users)
     */
    public function adminDashboard()
    {
        // Get all pending permissions
        $pendientes = Permiso::where('estado', 'pendiente')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        // Get all approved permissions for the current month
        $aprobadosMes = Permiso::where('estado', 'aprobado')
            ->whereMonth('fecha_inicio', Carbon::now()->month)
            ->whereYear('fecha_inicio', Carbon::now()->year)
            ->count();
            
        // Get all permissions by type
        $permisosPorTipo = Permiso::select('tipo', DB::raw('count(*) as total'))
            ->whereYear('fecha_inicio', Carbon::now()->year)
            ->groupBy('tipo')
            ->get();
            
        // Get employee statistics
        $totalEmpleados = Empleado::count();
        $empleadosPorTipo = Empleado::select('tipo_empleado', DB::raw('count(*) as total'))
            ->groupBy('tipo_empleado')
            ->get();
            
        // Get permission statistics
        $totalPermisos = Permiso::count();
        $permisosAprobados = Permiso::where('estado', 'aprobado')->count();
        $permisosRechazados = Permiso::where('estado', 'rechazado')->count();
        
        return view('dashboard.admin', compact(
            'pendientes',
            'aprobadosMes',
            'permisosPorTipo',
            'totalEmpleados',
            'empleadosPorTipo',
            'totalPermisos',
            'permisosAprobados',
            'permisosRechazados'
        ));
    }
}
