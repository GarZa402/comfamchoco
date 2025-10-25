<x-app-layout>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-6">Dashboard de Recursos Humanos</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-blue-100 p-4 rounded-lg shadow">
                        <h2 class="text-lg font-semibold mb-2">Permisos Pendientes</h2>
                        <p class="text-3xl font-bold">{{ $pendientes->total() }}</p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-lg shadow">
                        <h2 class="text-lg font-semibold mb-2">Total Empleados</h2>
                        <p class="text-3xl font-bold">{{ $totalEmpleados }}</p>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded-lg shadow">
                        <h2 class="text-lg font-semibold mb-2">Permisos Este Mes</h2>
                        <p class="text-3xl font-bold">{{ $permisosMes }}</p>
                    </div>
                    <div class="bg-purple-100 p-4 rounded-lg shadow">
                        <h2 class="text-lg font-semibold mb-2">Total Políticas</h2>
                        <p class="text-3xl font-bold">{{ $politicas->count() }}</p>
                    </div>
                </div>
                
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">Permisos Pendientes de Aprobación</h2>
                        <a href="{{ route('permisos.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Ver Todos
                        </a>
                    </div>
                    
                    @if($pendientes->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 border-b text-left">Empleado</th>
                                        <th class="py-2 px-4 border-b text-left">Tipo</th>
                                        <th class="py-2 px-4 border-b text-left">Fecha Inicio</th>
                                        <th class="py-2 px-4 border-b text-left">Fecha Fin</th>
                                        <th class="py-2 px-4 border-b text-left">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendientes as $permiso)
                                    <tr>
                                        <td class="py-2 px-4 border-b">{{ $permiso->empleado->nombre }}</td>
                                        <td class="py-2 px-4 border-b">{{ $permiso->tipo }}</td>
                                        <td class="py-2 px-4 border-b">{{ $permiso->fecha_inicio->format('d/m/Y') }}</td>
                                        <td class="py-2 px-4 border-b">{{ $permiso->fecha_fin->format('d/m/Y') }}</td>
                                        <td class="py-2 px-4 border-b">
                                            <a href="{{ route('permisos.edit', $permiso->id) }}" class="text-blue-600 hover:text-blue-800 mr-2">Revisar</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $pendientes->links() }}
                        </div>
                    @else
                        <p class="text-gray-500">No hay permisos pendientes de aprobación.</p>
                    @endif
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold">Empleados Recientes</h2>
                            <a href="{{ route('empleados.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Ver Todos
                            </a>
                        </div>
                        
                        @if($empleadosRecientes->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="py-2 px-4 border-b text-left">Nombre</th>
                                            <th class="py-2 px-4 border-b text-left">Cargo</th>
                                            <th class="py-2 px-4 border-b text-left">Fecha Ingreso</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($empleadosRecientes as $empleado)
                                        <tr>
                                            <td class="py-2 px-4 border-b">{{ $empleado->nombre }}</td>
                                            <td class="py-2 px-4 border-b">{{ $empleado->cargo }}</td>
                                            <td class="py-2 px-4 border-b">{{ $empleado->fecha_ingreso->format('d/m/Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">No hay empleados registrados recientemente.</p>
                        @endif
                    </div>
                    
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold">Políticas</h2>
                            <a href="{{ route('politicas.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Ver Todas
                            </a>
                        </div>
                        
                        @if($politicas->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="py-2 px-4 border-b text-left">Tipo de Empleado</th>
                                            <th class="py-2 px-4 border-b text-left">Días Disponibles</th>
                                            <th class="py-2 px-4 border-b text-left">Última Actualización</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($politicas as $politica)
                                        <tr>
                                            <td class="py-2 px-4 border-b">{{ ucfirst($politica->tipo_empleado) }}</td>
                                            <td class="py-2 px-4 border-b">{{ $politica->dias_disponibles_anuales }}</td>
                                            <td class="py-2 px-4 border-b">{{ $politica->updated_at->format('d/m/Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">No hay políticas registradas.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>