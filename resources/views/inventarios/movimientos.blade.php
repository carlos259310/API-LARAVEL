@extends('layouts.sidebar')

@section('title', 'Movimientos de Inventario')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0">Movimientos: {{ $producto->nombre ?? 'Producto' }}</h2>
                <a href="{{ route('inventarios.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver al Inventario
                </a>
            </div>

            <!-- Información del Producto -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Información del Producto</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nombre:</strong> {{ $producto->nombre ?? 'N/A' }}</p>
                            <p><strong>Código:</strong> {{ $producto->codigo ?? 'N/A' }}</p>
                            <p><strong>Descripción:</strong> {{ $producto->descripcion ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Stock Actual:</strong> {{ $producto->inventario->stock_actual ?? 0 }}</p>
                            <p><strong>Stock Disponible:</strong> {{ $producto->inventario->stock_disponible ?? 0 }}</p>
                            <p><strong>Costo Promedio:</strong> ${{ number_format($producto->inventario->costo_promedio ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Movimientos -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Historial de Movimientos</h5>
                </div>
                <div class="card-body">
                    @if(isset($producto->movimientosInventario) && $producto->movimientosInventario->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Cantidad</th>
                                        <th>Stock Anterior</th>
                                        <th>Stock Nuevo</th>
                                        <th>Motivo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($producto->movimientosInventario as $movimiento)
                                        <tr>
                                            <td>{{ $movimiento->created_at->format('d/m/Y H:i:s') }}</td>
                                            <td>
                                                @if($movimiento->tipo_movimiento === 'entrada')
                                                    <span class="badge bg-success">Entrada</span>
                                                @else
                                                    <span class="badge bg-danger">Salida</span>
                                                @endif
                                            </td>
                                            <td>{{ number_format($movimiento->cantidad) }}</td>
                                            <td>{{ number_format($movimiento->stock_anterior ?? 0) }}</td>
                                            <td>{{ number_format($movimiento->stock_nuevo ?? 0) }}</td>
                                            <td>{{ $movimiento->motivo ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-clock-history display-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">No hay movimientos registrados</h5>
                            <p class="text-muted">Los movimientos de inventario aparecerán aquí.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
@endpush
