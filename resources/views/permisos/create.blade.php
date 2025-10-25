<x-app-layout>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Nuevo Permiso</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('permisos.index') }}" class="btn btn-sm btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Solicitud de Permiso</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('permisos.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="empleado_id" class="form-label">Empleado</label>
                    <select class="form-select @error('empleado_id') is-invalid @enderror" id="empleado_id" name="empleado_id" required>
                        <option value="" selected disabled>Seleccione un empleado</option>
                        @foreach($empleados as $empleado)
                            <option value="{{ $empleado->id }}">{{ $empleado->nombre }} ({{ $empleado->cargo }})</option>
                        @endforeach
                    </select>
                    @error('empleado_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="tipo" class="form-label">Tipo de Permiso</label>
                    <select class="form-select @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required>
                        <option value="" selected disabled>Seleccione un tipo</option>
                        <option value="vacaciones">Vacaciones</option>
                        <option value="licencia">Licencia</option>
                        <option value="permiso">Permiso</option>
                    </select>
                    @error('tipo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                    <input type="date" class="form-control @error('fecha_inicio') is-invalid @enderror" id="fecha_inicio" name="fecha_inicio" required>
                    @error('fecha_inicio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                    <input type="date" class="form-control @error('fecha_fin') is-invalid @enderror" id="fecha_fin" name="fecha_fin" required>
                    @error('fecha_fin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3">
                <label for="motivo" class="form-label">Motivo</label>
                <textarea class="form-control @error('motivo') is-invalid @enderror" id="motivo" name="motivo" rows="3" required></textarea>
                @error('motivo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Enviar Solicitud
                </button>
                <a href="{{ route('permisos.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

</x-app-layout>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('fecha_inicio').setAttribute('min', today);
        document.getElementById('fecha_fin').setAttribute('min', today);
        
        // Update minimum date for fecha_fin when fecha_inicio changes
        document.getElementById('fecha_inicio').addEventListener('change', function() {
            document.getElementById('fecha_fin').setAttribute('min', this.value);
        });
        
        // Show available days when employee and type are selected
        document.getElementById('empleado_id').addEventListener('change', checkAvailableDays);
        document.getElementById('tipo').addEventListener('change', checkAvailableDays);
        
        function checkAvailableDays() {
            const empleadoId = document.getElementById('empleado_id').value;
            const tipo = document.getElementById('tipo').value;
            
            if (empleadoId && tipo === 'vacaciones') {
                // Make an AJAX request to get available days
                fetch(`/empleados/${empleadoId}/dias-disponibles`)
                    .then(response => response.json())
                    .then(data => {
                        // Show a message with available days
                        const startDate = document.getElementById('fecha_inicio');
                        const endDate = document.getElementById('fecha_fin');
                        
                        if (startDate.value && endDate.value) {
                            const start = new Date(startDate.value);
                            const end = new Date(endDate.value);
                            const daysRequested = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;
                            
                            if (daysRequested > data.diasDisponibles) {
                                alert(`El empleado solo tiene ${data.diasDisponibles} días disponibles, pero está solicitando ${daysRequested} días.`);
                            }
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }
    });
</script>
@endpush