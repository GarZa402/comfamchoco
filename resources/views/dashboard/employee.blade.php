<x-app-layout>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-6">Dashboard de Empleado</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-blue-100 p-4 rounded-lg shadow">
                        <h2 class="text-lg font-semibold mb-2">Días Disponibles</h2>
                        <p class="text-3xl font-bold">{{ $diasDisponibles }}</p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-lg shadow">
                        <h2 class="text-lg font-semibold mb-2">Permisos Aprobados</h2>
                        <p class="text-3xl font-bold">{{ $permisos->where('estado', 'aprobado')->count() }}</p>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded-lg shadow">
                        <h2 class="text-lg font-semibold mb-2">Permisos Pendientes</h2>
                        <p class="text-3xl font-bold">{{ $permisos->where('estado', 'pendiente')->count() }}</p>
                    </div>
                </div>
                
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">Próximos Permisos</h2>
                        <a href="{{ route('permisos.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Solicitar Permiso
                        </a>
                    </div>
                    
                    @if($proximosPermisos->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 border-b text-left">Tipo</th>
                                        <th class="py-2 px-4 border-b text-left">Fecha Inicio</th>
                                        <th class="py-2 px-4 border-b text-left">Fecha Fin</th>
                                        <th class="py-2 px-4 border-b text-left">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($proximosPermisos as $permiso)
                                    <tr>
                                        <td class="py-2 px-4 border-b">{{ $permiso->tipo }}</td>
                                        <td class="py-2 px-4 border-b">{{ $permiso->fecha_inicio->format('d/m/Y') }}</td>
                                        <td class="py-2 px-4 border-b">{{ $permiso->fecha_fin->format('d/m/Y') }}</td>
                                        <td class="py-2 px-4 border-b">
                                            <span class="px-2 py-1 rounded 
                                                {{ $permiso->estado == 'aprobado' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $permiso->estado == 'pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $permiso->estado == 'rechazado' ? 'bg-red-100 text-red-800' : '' }}
                                            ">
                                                {{ ucfirst($permiso->estado) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">No tienes permisos próximos programados.</p>
                    @endif
                </div>
                
                <div>
                    <h2 class="text-xl font-semibold mb-4">Historial de Permisos</h2>
                    
                    @if($permisos->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 border-b text-left">Tipo</th>
                                        <th class="py-2 px-4 border-b text-left">Fecha Inicio</th>
                                        <th class="py-2 px-4 border-b text-left">Fecha Fin</th>
                                        <th class="py-2 px-4 border-b text-left">Estado</th>
                                        <th class="py-2 px-4 border-b text-left">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($permisos as $permiso)
                                    <tr>
                                        <td class="py-2 px-4 border-b">{{ $permiso->tipo }}</td>
                                        <td class="py-2 px-4 border-b">{{ $permiso->fecha_inicio->format('d/m/Y') }}</td>
                                        <td class="py-2 px-4 border-b">{{ $permiso->fecha_fin->format('d/m/Y') }}</td>
                                        <td class="py-2 px-4 border-b">
                                            <span class="px-2 py-1 rounded 
                                                {{ $permiso->estado == 'aprobado' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $permiso->estado == 'pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $permiso->estado == 'rechazado' ? 'bg-red-100 text-red-800' : '' }}
                                            ">
                                                {{ ucfirst($permiso->estado) }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-4 border-b">
                                            @if($permiso->estado == 'pendiente')
                                                <a href="{{ route('permisos.edit', $permiso->id) }}" class="text-blue-600 hover:text-blue-800">Editar</a>
                                            @else
                                                <span class="text-gray-400">Editar</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $permisos->links() }}
                        </div>
                    @else
                        <p class="text-gray-500">No tienes permisos registrados.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>