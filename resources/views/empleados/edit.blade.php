<x-app-layout>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Editar Empleado</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            @php $isRRHH = auth()->check() && auth()->user()->role === 'rrhh'; @endphp
            <a href="{{ $isRRHH ? route('empleados.index') : route('dashboard') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Información del Empleado</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('empleados.update', $empleado->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $empleado->nombre) }}" required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="cedula" class="form-label">Cédula</label>
                    <input type="text" class="form-control @error('cedula') is-invalid @enderror" id="cedula" name="cedula" value="{{ old('cedula', $empleado->cedula) }}" required>
                    @error('cedula')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $empleado->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="cargo" class="form-label">Cargo</label>
                    <input type="text" class="form-control @error('cargo') is-invalid @enderror" id="cargo" name="cargo" value="{{ old('cargo', $empleado->cargo) }}" required>
                    @error('cargo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tipo_empleado" class="form-label">Tipo de Empleado</label>
                    <select class="form-select @error('tipo_empleado') is-invalid @enderror" id="tipo_empleado" name="tipo_empleado" required>
                        <option value="">Seleccionar tipo</option>
                        <option value="administrativo" {{ old('tipo_empleado', $empleado->tipo_empleado) === 'administrativo' ? 'selected' : '' }}>Administrativo</option>
                        <option value="operativo" {{ old('tipo_empleado', $empleado->tipo_empleado) === 'operativo' ? 'selected' : '' }}>Operativo</option>
                        <option value="directivo" {{ old('tipo_empleado', $empleado->tipo_empleado) === 'directivo' ? 'selected' : '' }}>Directivo</option>
                    </select>
                    @error('tipo_empleado')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="politica_id" class="form-label">Política de Permisos</label>
                    <select class="form-select @error('politica_id') is-invalid @enderror" id="politica_id" name="politica_id">
                        <option value="">Seleccionar política (opcional)</option>
                        @foreach($politicas as $politica)
                            <option value="{{ $politica->id }}" {{ old('politica_id', $empleado->politica_id) == $politica->id ? 'selected' : '' }}>{{ $politica->nombre }}</option>
                        @endforeach
                    </select>
                    @error('politica_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Guardar Cambios
                    </button>
                    <a href="{{ $isRRHH ? route('empleados.index') : route('dashboard') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
</x-app-layout>