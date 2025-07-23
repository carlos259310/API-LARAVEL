@extends('layouts.app')

@section('title', 'Movimientos de Inventario - ' . $producto->nombre)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Movimientos de Inventario</h1>
    <a href="{{ route('inventarios.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver al Inventario
    </a>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ $producto->nombre }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Código:</strong> {{ $producto->codigo }}
                    </div>
                    <div class="col-md-3">
                        <strong>Stock Actual:</strong> 
                        <span class="badge bg-info">{{ $producto->inventario->stock_actual ?? 0 }}</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Stock Mínimo:</strong> {{ $producto->stock_minimo }}
                    </div>
                    <div class="col-md-3">
                        <strong>Precio:</strong> ${{ number_format($producto->precio_venta, 2, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Historial de Movimientos</h5>
    </div>
    <div class="card-body">
        @if($producto->movimientosInventario->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($producto->movimientosInventario as $movimiento)
                            <tr>
                                <td>{{ $movimiento->fecha_movimiento ? $movimiento->fecha_movimiento->format('d/m/Y H:i') : $movimiento->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($movimiento->tipo_movimiento === 'entrada')
                                        <span class="badge bg-success">Entrada</span>
                                    @else
                                        <span class="badge bg-danger">Salida</span>
                                    @endif
                                </td>
                                <td>
                                    @if($movimiento->tipo_movimiento === 'entrada')
                                        <span class="text-success">+{{ $movimiento->cantidad }}</span>
                                    @else
                                        <span class="text-danger">-{{ $movimiento->cantidad }}</span>
                                    @endif
                                </td>
                                <td>{{ $movimiento->observaciones ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center text-muted py-4">
                <i class="bi bi-clock-history fs-1"></i>
                <p class="mt-2">No hay movimientos registrados para este producto</p>
            </div>
        @endif
    </div>
</div>
@endsection
