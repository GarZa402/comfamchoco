<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Politica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empleados = Empleado::with('politica')->paginate(10);
        return view('empleados.index', compact('empleados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $politicas = Politica::all();
        return view('empleados.create', compact('politicas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'cedula' => 'required|string|max:20|unique:empleados',
            'email' => 'required|email|unique:empleados',
            'cargo' => 'required|string|max:255',
            'tipo_empleado' => 'required|in:administrativo,operativo,directivo',
            'politica_id' => 'nullable|exists:politicas,id'
        ]);

        DB::beginTransaction();
        try {
            $empleado = Empleado::create($request->all());
            
            // Create user for the employee
            $user = \App\Models\User::create([
                'name' => $empleado->nombre,
                'email' => $empleado->email,
                'password' => bcrypt('password'), // Default password, should be changed
                'role' => 'empleado',
                'empleado_id' => $empleado->id
            ]);
            
            DB::commit();
            return redirect()->route('dashboard')->with('success', 'Empleado creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error al crear el empleado: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $empleado = Empleado::with(['politica', 'permisos', 'user'])->findOrFail($id);
        return view('empleados.show', compact('empleado'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $empleado = Empleado::findOrFail($id);
        $politicas = Politica::all();
        return view('empleados.edit', compact('empleado', 'politicas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $empleado = Empleado::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'cedula' => 'required|string|max:20|unique:empleados,cedula,'.$id,
            'email' => 'required|email|unique:empleados,email,'.$id,
            'cargo' => 'required|string|max:255',
            'tipo_empleado' => 'required|in:administrativo,operativo,directivo',
            'politica_id' => 'nullable|exists:politicas,id'
        ]);

        DB::beginTransaction();
        try {
            $empleado->update($request->all());
            
            // Update user if needed
            if ($empleado->user) {
                $empleado->user->update([
                    'name' => $empleado->nombre,
                    'email' => $empleado->email
                ]);
            }
            
            DB::commit();
            return redirect()->route('empleados.index')->with('success', 'Empleado actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error al actualizar el empleado: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $empleado = Empleado::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Delete associated user if exists
            if ($empleado->user) {
                $empleado->user->delete();
            }
            
            $empleado->delete();
            DB::commit();
            return redirect()->route('empleados.index')->with('success', 'Empleado eliminado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al eliminar el empleado: ' . $e->getMessage()]);
        }
    }
    /**
     * Generate employees report.
     */
    public function reporteEmpleados(Request $request)
    {
        $query = Empleado::with(['politica', 'user', 'permisos']);
        
        if ($request->filled('tipo_empleado')) {
            $query->where('tipo_empleado', $request->tipo_empleado);
        }
        
        if ($request->filled('politica_id')) {
            $query->where('politica_id', $request->politica_id);
        }
        
        if ($request->filled('cargo')) {
            $query->where('cargo', 'like', '%' . $request->cargo . '%');
        }
        
        $empleados = $query->latest()->get();
        
        // Calculate statistics
        $totalEmpleados = $empleados->count();
        $administrativos = $empleados->where('tipo_empleado', 'administrativo')->count();
        $operativos = $empleados->where('tipo_empleado', 'operativo')->count();
        $directivos = $empleados->where('tipo_empleado', 'directivo')->count();
        
        $politicas = Politica::all();
        
        return view('empleados.reporte', compact(
            'empleados',
            'totalEmpleados',
            'administrativos',
            'operativos',
            'directivos',
            'politicas'
        ));
    }
}
