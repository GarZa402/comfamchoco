<x-app-layout>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Permisos</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('permisos.create') }}" class="btn btn-sm btn-success">
                <i class="bi bi-plus-circle"></i> Nuevo Permiso
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Lista de Permisos</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Empleado</th>
                        <th>Tipo</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permisos as $permiso)
                    <tr>
                        <td>{{ $permiso->id }}</td>
                        <td>{{ $permiso->empleado->nombre }}</td>
                        <td>{{ $permiso->tipo }}</td>
                        <td>{{ $permiso->fecha_inicio }}</td>
                        <td>{{ $permiso->fecha_fin }}</td>
                        <td>
                            @if($permiso->estado === 'aprobado')
                                <span class="badge bg-success">Aprobado</span>
                            @elseif($permiso->estado === 'pendiente')
                                <span class="badge bg-warning">Pendiente</span>
                            @else
                                <span class="badge bg-danger">Rechazado</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('permisos.show', $permiso->id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($permiso->estado === 'pendiente')
                                    <a href="{{ route('permisos.edit', $permiso->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-app-layout>