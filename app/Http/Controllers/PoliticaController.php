<?php

namespace App\Http\Controllers;

use App\Models\Politica;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PoliticaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $politicas = Politica::withCount('empleados')->paginate(10);
        return view('politicas.index', compact('politicas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('politicas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipo_empleado' => 'required|in:administrativo,operativo,directivo',
            'dias_disponibles_anuales' => 'required|integer|min:0',
            'reglas_especiales' => 'nullable|string|max:1000'
        ]);

        DB::beginTransaction();
        try {
            Politica::create($request->all());
            DB::commit();
            return redirect()->route('politicas.index')->with('success', 'Política creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error al crear la política: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $politica = Politica::with('empleados')->findOrFail($id);
        return view('politicas.show', compact('politica'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $politica = Politica::findOrFail($id);
        return view('politicas.edit', compact('politica'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $politica = Politica::findOrFail($id);
        
        $request->validate([
            'tipo_empleado' => 'required|in:administrativo,operativo,directivo',
            'dias_disponibles_anuales' => 'required|integer|min:0',
            'reglas_especiales' => 'nullable|string|max:1000'
        ]);

        DB::beginTransaction();
        try {
            $politica->update($request->all());
            DB::commit();
            return redirect()->route('politicas.index')->with('success', 'Política actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error al actualizar la política: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $politica = Politica::findOrFail($id);
        
        // Check if policy is being used by employees
        if ($politica->empleados()->count() > 0) {
            return back()->withErrors(['error' => 'No se puede eliminar una política que está siendo utilizada por empleados.']);
        }
        
        DB::beginTransaction();
        try {
            $politica->delete();
            DB::commit();
            return redirect()->route('politicas.index')->with('success', 'Política eliminada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al eliminar la política: ' . $e->getMessage()]);
        }
    }
}
