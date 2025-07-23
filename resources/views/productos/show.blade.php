@extends('layouts.app')

@section('title', 'Detalle del Producto')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Detalle del Producto</h1>
    <div class="btn-group">
        <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Información del Producto</h5>
                @if($producto->activo)
                    <span class="badge bg-success">Activo</span>
                @else
                    <span class="badge bg-secondary">Inactivo</span>
                @endif
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Código:</label>
                        <p class="form-control-plaintext">{{ $producto->codigo }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nombre:</label>
                        <p class="form-control-plaintext">{{ $producto->nombre }}</p>
                    </div>
                </div>
                
                @if($producto->descripcion)
                <div class="mb-3">
                    <label class="form-label fw-bold">Descripción:</label>
                    <p class="form-control-plaintext">{{ $producto->descripcion }}</p>
                </div>
                @endif
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Categoría:</label>
                        <p class="form-control-plaintext">
                            <span class="badge bg-primary">{{ $producto->categoria->nombre ?? 'N/A' }}</span>
                        </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Marca:</label>
                        <p class="form-control-plaintext">
                            <span class="badge bg-info">{{ $producto->marca->nombre ?? 'N/A' }}</span>
                        </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Proveedor:</label>
                        <p class="form-control-plaintext">
                            <span class="badge bg-warning text-dark">{{ $producto->proveedor->nombre ?? 'N/A' }}</span>
                        </p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Precio:</label>
                        <p class="form-control-plaintext fs-5 text-success">
                            ${{ number_format($producto->precio, 2, ',', '.') }}
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Stock Mínimo:</label>
                        <p class="form-control-plaintext">
                            <span class="badge bg-warning text-dark">{{ $producto->stock_minimo }}</span>
                        </p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Fecha de Creación:</label>
                        <p class="form-control-plaintext">{{ $producto->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Última Actualización:</label>
                        <p class="form-control-plaintext">{{ $producto->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información de Inventario</h5>
            </div>
            <div class="card-body">
                @if($producto->inventario)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Stock Actual:</label>
                        <p class="form-control-plaintext">
                            @if($producto->inventario->stock <= $producto->stock_minimo)
                                <span class="badge bg-danger fs-6">
                                    {{ $producto->inventario->stock }} (Crítico)
                                </span>
                            @elseif($producto->inventario->stock <= ($producto->stock_minimo * 1.5))
                                <span class="badge bg-warning text-dark fs-6">
                                    {{ $producto->inventario->stock }} (Bajo)
                                </span>
                            @else
                                <span class="badge bg-success fs-6">
                                    {{ $producto->inventario->stock }}
                                </span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Valor Total:</label>
                        <p class="form-control-plaintext text-success">
                            ${{ number_format($producto->inventario->stock * $producto->precio, 2, ',', '.') }}
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Última Actualización:</label>
                        <p class="form-control-plaintext">
                            {{ $producto->inventario->updated_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        No hay información de inventario para este producto.
                    </div>
                @endif
                
                <div class="d-grid gap-2">
                    <a href="{{ route('inventarios.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-boxes"></i> Ver Inventario
                    </a>
                    @if($producto->inventario)
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#entradaModal">
                            <i class="bi bi-plus-circle"></i> Entrada
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#salidaModal">
                            <i class="bi bi-dash-circle"></i> Salida
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($producto->inventario)
<!-- Modal para Entrada de Inventario -->
<div class="modal fade" id="entradaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Entrada de Inventario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('movimientos.store') }}" method="POST">
                @csrf
                <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                <input type="hidden" name="tipo" value="entrada">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="cantidad" class="form-label">Cantidad *</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Registrar Entrada</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Salida de Inventario -->
<div class="modal fade" id="salidaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Salida de Inventario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('movimientos.store') }}" method="POST">
                @csrf
                <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                <input type="hidden" name="tipo" value="salida">
                
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Stock actual:</strong> {{ $producto->inventario->stock }} unidades
                    </div>
                    
                    <div class="mb-3">
                        <label for="cantidad_salida" class="form-label">Cantidad *</label>
                        <input type="number" class="form-control" id="cantidad_salida" name="cantidad" 
                               min="1" max="{{ $producto->inventario->stock }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones_salida" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones_salida" name="observaciones" rows="3"></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Registrar Salida</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
