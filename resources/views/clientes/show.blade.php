@extends('layouts.app')

@section('title', 'Ver Cliente')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-person"></i> Información del Cliente</h1>
                <div>
                    <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Cliente #{{ $cliente->id }} - 
                        <span class="badge {{ $cliente->activo ? 'bg-success' : 'bg-danger' }}">
                            {{ $cliente->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Información Personal -->
                        <div class="col-md-6">
                            <h5 class="mb-3 text-primary">Información Personal</h5>
                            
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Nombre Completo:</strong></td>
                                    <td>{{ $cliente->nombre_1 }} {{ $cliente->nombre_2 }} {{ $cliente->apellido_1 }} {{ $cliente->apellido_2 }}</td>
                                </tr>
                                @if($cliente->razon_social)
                                <tr>
                                    <td><strong>Razón Social:</strong></td>
                                    <td>{{ $cliente->razon_social }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>Tipo de Cliente:</strong></td>
                                    <td>
                                        <span class="badge {{ $cliente->tipo_cliente == 'natural' ? 'bg-info' : 'bg-warning' }}">
                                            {{ ucfirst($cliente->tipo_cliente) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Tipo de Documento:</strong></td>
                                    <td>{{ $cliente->tipoDocumento->nombre ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Número de Documento:</strong></td>
                                    <td><code>{{ $cliente->numero_documento }}</code></td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>
                                        @if($cliente->email)
                                            <a href="mailto:{{ $cliente->email }}">{{ $cliente->email }}</a>
                                        @else
                                            <span class="text-muted">No registrado</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Información de Contacto -->
                        <div class="col-md-6">
                            <h5 class="mb-3 text-primary">Información de Contacto</h5>
                            
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Teléfono:</strong></td>
                                    <td>
                                        @if($cliente->telefono)
                                            <a href="tel:{{ $cliente->telefono }}">{{ $cliente->telefono }}</a>
                                        @else
                                            <span class="text-muted">No registrado</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Dirección:</strong></td>
                                    <td>{{ $cliente->direccion ?? 'No registrada' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Ciudad:</strong></td>
                                    <td>{{ $cliente->ciudad->nombre ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Departamento:</strong></td>
                                    <td>{{ $cliente->departamento->nombre ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Fecha de Registro:</strong></td>
                                    <td>{{ $cliente->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Última Actualización:</strong></td>
                                    <td>{{ $cliente->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="border-top pt-3">
                                <h6 class="text-primary">Acciones</h6>
                                <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Editar Cliente
                                </a>
                                <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar este cliente?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash"></i> Eliminar Cliente
                                    </button>
                                </form>
                                <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-list"></i> Volver a la Lista
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
