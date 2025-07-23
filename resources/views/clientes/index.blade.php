@extends('layouts.sidebar')

@section('title', 'Gestión de Clientes')
@section('page-title', 'Clientes')
@section('page-description', 'Gestión de la base de datos de clientes')

@section('page-actions')
    <a href="{{ route('clientes.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nuevo Cliente
    </a>
@endsection

@section('content')
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lista de Clientes</h6>
                </div>
                <div class="card-body">
                    @if($clientes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre Completo</th>
                                        <th>Tipo Doc.</th>
                                        <th>Número Doc.</th>
                                        <th>Email</th>
                                        <th>Tipo Cliente</th>
                                        <th>Ciudad</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clientes as $cliente)
                                    <tr>
                                        <td>{{ $cliente->id }}</td>
                                        <td>
                                            <strong>{{ $cliente->nombre_1 }} {{ $cliente->nombre_2 }} {{ $cliente->apellido_1 }} {{ $cliente->apellido_2 }}</strong>
                                            @if($cliente->razon_social)
                                                <br><small class="text-muted">{{ $cliente->razon_social }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $cliente->tipoDocumento->nombre ?? 'N/A' }}</td>
                                        <td>{{ $cliente->numero_documento }}</td>
                                        <td>{{ $cliente->email ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge {{ $cliente->tipo_cliente == 'natural' ? 'bg-info' : 'bg-warning' }}">
                                                {{ ucfirst($cliente->tipo_cliente) }}
                                            </span>
                                        </td>
                                        <td>{{ $cliente->ciudad->nombre ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge {{ $cliente->activo ? 'bg-success' : 'bg-danger' }}">
                                                {{ $cliente->activo ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('clientes.show', $cliente->id) }}" class="btn btn-sm btn-outline-info" title="Ver">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar este cliente?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="d-flex justify-content-center">
                            {{ $clientes->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #6c757d;"></i>
                            <h4 class="text-muted">No hay clientes registrados</h4>
                            <p class="text-muted">Comienza agregando tu primer cliente.</p>
                            <a href="{{ route('clientes.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Crear Primer Cliente
                            </a>
                        </div>
                    @endif
                </div>
            </div>
@endsection
