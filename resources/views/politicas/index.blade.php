<x-app-layout>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Políticas de Permisos</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('politicas.create') }}" class="btn btn-sm btn-success">
                <i class="bi bi-plus-circle"></i> Nueva Política
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Lista de Políticas</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Tipo de Empleado</th>
                        <th>Límite de Días</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($politicas as $politica)
                    <tr>
                        <td>{{ $politica->id }}</td>
                        <td>{{ $politica->nombre }}</td>
                        <td>{{ $politica->descripcion }}</td>
                        <td>{{ $politica->tipo_empleado }}</td>
                        <td>{{ $politica->limite_dias }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('politicas.show', $politica->id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('politicas.edit', $politica->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
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