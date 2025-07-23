@extends('layouts.sidebar')

@section('title', 'Inventario')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0">Inventario</h2>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#movimientoModal">
                    <i class="bi bi-plus-circle"></i> Nueva Entrada/Salida
                </button>
            </div>

            <!-- Estadísticas -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Total Productos</h6>
                                    <h4>{{ $totalProductos ?? 0 }}</h4>
                                </div>
                                <i class="bi bi-box-seam fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Stock Total</h6>
                                    <h4>{{ number_format($totalStock ?? 0) }}</h4>
                                </div>
                                <i class="bi bi-boxes fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Stock Bajo</h6>
                                    <h4>{{ $stockBajo ?? 0 }}</h4>
                                </div>
                                <i class="bi bi-exclamation-triangle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Valor Total</h6>
                                    <h4>${{ number_format($valorTotal ?? 0, 2) }}</h4>
                                </div>
                                <i class="bi bi-currency-dollar fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Inventario -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Lista de Inventario</h5>
                </div>
                <div class="card-body">
                    @if(isset($inventarios) && $inventarios->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Código</th>
                                        <th>Stock Actual</th>
                                        <th>Stock Reservado</th>
                                        <th>Stock Disponible</th>
                                        <th>Costo Promedio</th>
                                        <th>Última Entrada</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inventarios as $inventario)
                                        <tr>
                                            <td>
                                                <strong>{{ $inventario->producto->nombre ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">{{ $inventario->producto->descripcion ?? '' }}</small>
                                            </td>
                                            <td>{{ $inventario->producto->codigo ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $inventario->stock_actual ?? 0 }}</span>
                                            </td>
                                            <td>{{ $inventario->stock_reservado ?? 0 }}</td>
                                            <td>{{ $inventario->stock_disponible ?? 0 }}</td>
                                            <td>${{ number_format($inventario->costo_promedio ?? 0, 2) }}</td>
                                            <td>
                                                @if($inventario->ultima_entrada)
                                                    {{ $inventario->ultima_entrada->format('d/m/Y H:i') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('inventarios.movimientos', $inventario->producto->id ?? 0) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-list-ul"></i> Movimientos
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if(method_exists($inventarios, 'links'))
                            <div class="mt-3">
                                {{ $inventarios->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">No hay productos en inventario</h5>
                            <p class="text-muted">Agrega productos para ver el inventario aquí.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Entrada/Salida -->
<div class="modal fade" id="movimientoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva Entrada/Salida</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('movimientos.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_producto" class="form-label">Producto</label>
                        <select class="form-select" id="id_producto" name="id_producto" required>
                            <option value="">Seleccionar producto...</option>
                            @if(isset($productos))
                                @foreach($productos as $producto)
                                    <option value="{{ $producto->id }}">{{ $producto->nombre }} ({{ $producto->codigo }})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tipo_movimiento" class="form-label">Tipo de Movimiento</label>
                        <select class="form-select" id="tipo_movimiento" name="tipo_movimiento" required>
                            <option value="">Seleccionar tipo...</option>
                            <option value="entrada">Entrada</option>
                            <option value="salida">Salida</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="cantidad" class="form-label">Cantidad</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Motivo</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Movimiento</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
@endpush
