@extends('layouts.sidebar')
@section('title', 'Facturas')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Facturas</h2>
        <a href="{{ route('facturas.create') }}" class="btn btn-primary">Nueva Factura</a>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="card">
        <div class="card-body">
            @if($facturas && $facturas->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Factura</th>
                                <th>Cliente</th>
                                <th>Bodega</th>
                                <th>Estado</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($facturas as $factura)
                            <tr>
                                <td>{{ $factura->prefijo }}-{{ $factura->consecutivo }}</td>
                                <td>
                                    @if($factura->cliente)
                                        {{ $factura->cliente->nombre_1 }} {{ $factura->cliente->apellido_1 }}
                                        @if($factura->cliente->razon_social)
                                            <br><small>{{ $factura->cliente->razon_social }}</small>
                                        @endif
                                    @else
                                        Sin cliente
                                    @endif
                                </td>
                                <td>
                                    @if($factura->bodega)
                                        {{ $factura->bodega->nombre }}
                                    @else
                                        Sin bodega
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $factura->estado == 'pagada' ? 'success' : ($factura->estado == 'anulada' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($factura->estado) }}
                                    </span>
                                </td>
                                <td>${{ number_format($factura->total, 2) }}</td>
                                <td>
                                    <a href="{{ route('facturas.show', $factura) }}" class="btn btn-sm btn-info">Ver</a>
                                    @if($factura->estado == 'pendiente')
                                        <form action="{{ route('facturas.anular', $factura) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('¿Anular factura?')">Anular</button>
                                        </form>
                                        <form action="{{ route('facturas.pagar', $factura) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-success">Marcar Pagada</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div class="mt-3">
                        {{ $facturas->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <h5 class="text-muted">No hay facturas registradas</h5>
                    <p class="text-muted">Haz clic en "Nueva Factura" para crear la primera factura.</p>
                    <a href="{{ route('facturas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Crear Primera Factura
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
