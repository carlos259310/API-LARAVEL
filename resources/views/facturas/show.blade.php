@extends('layouts.sidebar')
@section('title', 'Factura')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Factura {{ $factura->getAttribute('prefijo') }}-{{ $factura->getAttribute('consecutivo') }}</h2>
        <div>
            <a href="{{ route('facturas.index') }}" class="btn btn-secondary">Volver</a>
            @if($factura->getAttribute('estado') == 'pendiente')
                <form action="{{ route('facturas.anular', $factura) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-danger" onclick="return confirm('¿Anular factura?')">Anular</button>
                </form>
                <form action="{{ route('facturas.pagar', $factura) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-success">Marcar Pagada</button>
                </form>
            @endif
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4"><strong>Cliente:</strong> {{ $factura->cliente->getAttribute('nombre_1') . ' ' . $factura->cliente->getAttribute('apellido_1') }}</div>
                <div class="col-md-4"><strong>Bodega:</strong> {{ $factura->bodega->getAttribute('nombre') ?? '' }}</div>
                <div class="col-md-4"><strong>Estado:</strong> <span class="badge bg-{{ $factura->getAttribute('estado') == 'pagada' ? 'success' : ($factura->getAttribute('estado') == 'anulada' ? 'danger' : 'warning') }}">{{ ucfirst($factura->getAttribute('estado')) }}</span></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4"><strong>Fecha:</strong> {{ $factura->getAttribute('created_at')->format('d/m/Y H:i') }}</div>
                <div class="col-md-4"><strong>Total:</strong> ${{ number_format($factura->getAttribute('total'), 2) }}</div>
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header"><strong>Detalle</strong></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($factura->detalles as $detalle)
                        <tr>
                            <td>{{ $detalle->producto->nombre ?? '' }}</td>
                            <td>{{ $detalle->cantidad }}</td>
                            <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                            <td>${{ number_format($detalle->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mb-3">
        @if($factura->estado == 'pendiente')
            <form action="{{ route('facturas.anular', $factura) }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-danger" onclick="return confirm('¿Anular factura?')">Anular</button>
            </form>
            <form action="{{ route('facturas.pagar', $factura) }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-success">Marcar Pagada</button>
            </form>
        @endif
    </div>
</div>
@endsection
