<x-app-layout>
<div class="container-fluid">
    <header class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Panel de Administrador</h1>
        <div class="btn-toolbar mb-2 mb-md-0" role="toolbar" aria-label="Herramientas administrativas">
            <div class="btn-group me-2" role="group">
                <a href="{{ route('empleados.create') }}" class="btn btn-sm btn-success" aria-label="Agregar nuevo empleado">
                    <i class="bi bi-person-plus" aria-hidden="true"></i> Nuevo Empleado
                </a>
                <a href="{{ route('politicas.create') }}" class="btn btn-sm btn-primary" aria-label="Crear nueva política">
                    <i class="bi bi-gear" aria-hidden="true"></i> Nueva Política
                </a>
            </div>
        </div>
    </header>

    <section class="row mb-4" aria-label="Estadísticas principales">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Total de Empleados</h5>
                    <h2 class="card-text text-primary">{{ $totalEmpleados ?? 0 }}</h2>
                    <a href="{{ route('empleados.index') }}" class="btn btn-sm btn-outline-primary">Ver Empleados</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Permisos Pendientes</h5>
                    <h2 class="card-text text-warning">{{ $permisosPendientes ?? 0 }}</h2>
                    <a href="{{ route('permisos.index') }}" class="btn btn-sm btn-outline-warning">Ver Permisos</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Políticas Activas</h5>
                    <h2 class="card-text text-info">{{ $politicasActivas ?? 0 }}</h2>
                    <a href="{{ route('politicas.index') }}" class="btn btn-sm btn-outline-info">Ver Políticas</a>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Permisos Recientes</h5>
                </div>
                <div class="card-body">
                    @if($permisosRecientes && $permisosRecientes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped" aria-label="Tabla de permisos recientes">
                            <caption class="visually-hidden">Lista de permisos recientes con su estado</caption>
                            <thead>
                                <tr>
                                    <th scope="col">Empleado</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Fecha Inicio</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permisosRecientes as $permiso)
                                <tr>
                                    <td>{{ $permiso->empleado->nombre ?? 'N/A' }}</td>
                                    <td>{{ $permiso->tipo ?? 'N/A' }}</td>
                                    <td>{{ $permiso->fecha_inicio ? \Carbon\Carbon::parse($permiso->fecha_inicio)->format('d/m/Y') : 'N/A' }}</td>
                                    <td>
                                        @php
                                            $estadoClass = match($permiso->estado ?? '') {
                                                'aprobado' => 'bg-success',
                                                'pendiente' => 'bg-warning',
                                                'rechazado' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                            $estadoText = match($permiso->estado ?? '') {
                                                'aprobado' => 'Aprobado',
                                                'pendiente' => 'Pendiente',
                                                'rechazado' => 'Rechazado',
                                                default => 'Desconocido'
                                            };
                                        @endphp
                                        <span class="badge {{ $estadoClass }}">{{ $estadoText }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('permisos.show', $permiso->id) }}" class="btn btn-sm btn-info" aria-label="Ver detalles del permiso">
                                            <i class="bi bi-eye" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="alert alert-info" role="alert">
                        No hay permisos recientes para mostrar.
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Empleados por Tipo</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="empleadosPorTipoChart" aria-label="Gráfico de distribución de empleados por tipo"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartCanvas = document.getElementById('empleadosPorTipoChart');
        if (!chartCanvas) return;

        try {
            const ctx = chartCanvas.getContext('2d');
            const empleadosPorTipoChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Administrativo', 'Operativo', 'Directivo'],
                    datasets: [{
                        data: [
                            {{ $empleadosPorTipo->administrativo ?? 0 }},
                            {{ $empleadosPorTipo->operativo ?? 0 }},
                            {{ $empleadosPorTipo->directivo ?? 0 }}
                        ],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(75, 192, 192, 0.7)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        title: {
                            display: true,
                            text: 'Distribución de Empleados',
                            font: {
                                size: 16
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error al crear el gráfico:', error);
            const chartContainer = document.querySelector('.chart-container');
            if (chartContainer) {
                chartContainer.innerHTML = '<div class="alert alert-danger">No se pudo cargar el gráfico.</div>';
            }
        }
    });
</script>
@endpush