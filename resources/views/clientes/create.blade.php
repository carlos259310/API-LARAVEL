@extends('layouts.app')

@section('title', 'Crear Cliente')

@section('content')
<div class="container-fluid" data-ciudades="{{ json_encode($ciudades) }}">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-person-plus"></i> Crear Nuevo Cliente</h1>
                <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>

            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Información del Cliente</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('clientes.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Información Personal -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Información Personal</h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nombre_1" class="form-label">Primer Nombre *</label>
                                        <input type="text" class="form-control @error('nombre_1') is-invalid @enderror" 
                                               id="nombre_1" name="nombre_1" value="{{ old('nombre_1') }}" required>
                                        @error('nombre_1')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="nombre_2" class="form-label">Segundo Nombre</label>
                                        <input type="text" class="form-control @error('nombre_2') is-invalid @enderror" 
                                               id="nombre_2" name="nombre_2" value="{{ old('nombre_2') }}">
                                        @error('nombre_2')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="apellido_1" class="form-label">Primer Apellido *</label>
                                        <input type="text" class="form-control @error('apellido_1') is-invalid @enderror" 
                                               id="apellido_1" name="apellido_1" value="{{ old('apellido_1') }}" required>
                                        @error('apellido_1')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="apellido_2" class="form-label">Segundo Apellido</label>
                                        <input type="text" class="form-control @error('apellido_2') is-invalid @enderror" 
                                               id="apellido_2" name="apellido_2" value="{{ old('apellido_2') }}">
                                        @error('apellido_2')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="id_tipo_documento" class="form-label">Tipo de Documento *</label>
                                        <select class="form-control @error('id_tipo_documento') is-invalid @enderror" 
                                                id="id_tipo_documento" name="id_tipo_documento" required>
                                            <option value="">Seleccionar...</option>
                                            @foreach($tiposDocumento as $tipo)
                                                <option value="{{ $tipo->id }}" {{ old('id_tipo_documento') == $tipo->id ? 'selected' : '' }}>
                                                    {{ $tipo->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_tipo_documento')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="numero_documento" class="form-label">Número de Documento *</label>
                                        <input type="text" class="form-control @error('numero_documento') is-invalid @enderror" 
                                               id="numero_documento" name="numero_documento" value="{{ old('numero_documento') }}" required>
                                        @error('numero_documento')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="tipo_cliente" class="form-label">Tipo de Cliente *</label>
                                    <select class="form-control @error('tipo_cliente') is-invalid @enderror" 
                                            id="tipo_cliente" name="tipo_cliente" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="natural" {{ old('tipo_cliente') == 'natural' ? 'selected' : '' }}>Natural</option>
                                        <option value="juridico" {{ old('tipo_cliente') == 'juridico' ? 'selected' : '' }}>Jurídico</option>
                                    </select>
                                    @error('tipo_cliente')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 d-none" id="razon_social_group">
                                    <label for="razon_social" class="form-label">Razón Social</label>
                                    <input type="text" class="form-control @error('razon_social') is-invalid @enderror" 
                                           id="razon_social" name="razon_social" value="{{ old('razon_social') }}">
                                    @error('razon_social')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Información de Contacto -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Información de Contacto</h5>
                                
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control @error('telefono') is-invalid @enderror" 
                                           id="telefono" name="telefono" value="{{ old('telefono') }}">
                                    @error('telefono')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <textarea class="form-control @error('direccion') is-invalid @enderror" 
                                              id="direccion" name="direccion" rows="3">{{ old('direccion') }}</textarea>
                                    @error('direccion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="id_departamento" class="form-label">Departamento *</label>
                                    <select class="form-control @error('id_departamento') is-invalid @enderror" 
                                            id="id_departamento" name="id_departamento" required>
                                        <option value="">Seleccionar...</option>
                                        @foreach($departamentos as $departamento)
                                            <option value="{{ $departamento->id }}" {{ old('id_departamento') == $departamento->id ? 'selected' : '' }}>
                                                {{ $departamento->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_departamento')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="id_ciudad" class="form-label">Ciudad *</label>
                                    <select class="form-control @error('id_ciudad') is-invalid @enderror" 
                                            id="id_ciudad" name="id_ciudad" required>
                                        <option value="">Seleccionar departamento primero...</option>
                                    </select>
                                    @error('id_ciudad')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="activo" name="activo" {{ old('activo', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="activo">
                                            Cliente Activo
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Guardar Cliente
                                </button>
                                <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/cliente-manager.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var container = document.querySelector('.container-fluid');
    var ciudadesData = JSON.parse(container.getAttribute('data-ciudades'));
    window.ClienteManager.init(ciudadesData);
});
</script>
@endpush
@endsection
