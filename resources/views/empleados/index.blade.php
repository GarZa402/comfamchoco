<x-app-layout>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Empleados</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('empleados.create') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-person-plus"></i> Nuevo Empleado
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Lista de Empleados</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Cédula</th>
                        <th>Email</th>
                        <th>Cargo</th>
                        <th>Tipo</th>
                        <th>Política</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($empleados as $empleado)
                    <tr>
                        <td>{{ $empleado->id }}</td>
                        <td>{{ $empleado->nombre }}</td>
                        <td>{{ $empleado->cedula }}</td>
                        <td>{{ $empleado->email }}</td>
                        <td>{{ $empleado->cargo }}</td>
                        <td>
                            <span class="badge bg-{{ $empleado->tipo_empleado === 'administrativo' ? 'primary' : ($empleado->tipo_empleado === 'operativo' ? 'success' : 'warning') }}">
                                {{ ucfirst($empleado->tipo_empleado) }}
                            </span>
                        </td>
                        <td>{{ $empleado->politica ? $empleado->politica->nombre : 'N/A' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('empleados.show', $empleado->id) }}" class="btn btn-sm btn-info" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('empleados.edit', $empleado->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('empleados.destroy', $empleado->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar este empleado?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No se encontraron empleados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $empleados->links() }}
        </div>
    </div>
    
    </x-app-layout>
</div>