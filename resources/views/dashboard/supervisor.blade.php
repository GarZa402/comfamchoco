<x-app-layout>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-6">Dashboard de Supervisor</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-blue-100 p-4 rounded-lg shadow">
                        <h2 class="text-lg font-semibold mb-2">Permisos Pendientes</h2>
                        <p class="text-3xl font-bold">{{ $pendientes->total() }}</p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-lg shadow">
                        <h2 class="text-lg font-semibold mb-2">Permisos Aprobados</h2>
                        <p class="text-3xl font-bold">{{ $aprobados->total() }}</p>
                    </div>
                    <div class="bg-purple-100 p-4 rounded-lg shadow">
                        <h2 class="text-lg font-semibold mb-2">Miembros del Equipo</h2>
                        <p class="text-3xl font-bold">{{ $equipo }}</p>
                    </div>
                </div>
                
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4">Permisos Pendientes de Aprobación</h2>
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
                
                <div>
                    <h2 class="text-xl font-semibold mb-4">Permisos Recientemente Aprobados</h2>
                    @if($aprobados->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 border-b text-left">Empleado</th>
                                        <th class="py-2 px-4 border-b text-left">Tipo</th>
                                        <th class="py-2 px-4 border-b text-left">Fecha Inicio</th>
                                        <th class="py-2 px-4 border-b text-left">Fecha Fin</th>
                                        <th class="py-2 px-4 border-b text-left">Fecha Aprobación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($aprobados as $permiso)
                                    <tr>
                                        <td class="py-2 px-4 border-b">{{ $permiso->empleado->nombre }}</td>
                                        <td class="py-2 px-4 border-b">{{ $permiso->tipo }}</td>
                                        <td class="py-2 px-4 border-b">{{ $permiso->fecha_inicio->format('d/m/Y') }}</td>
                                        <td class="py-2 px-4 border-b">{{ $permiso->fecha_fin->format('d/m/Y') }}</td>
                                        <td class="py-2 px-4 border-b">{{ $permiso->updated_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $aprobados->links() }}
                        </div>
                    @else
                        <p class="text-gray-500">No hay permisos aprobados recientemente.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>