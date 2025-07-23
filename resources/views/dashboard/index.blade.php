@extends('layouts.sidebar')

@section('title', 'Dashboard Principal')
@section('page-title', 'Dashboard')
@section('page-description', 'Resumen general del sistema')

@section('content')
<!-- Estadísticas Generales -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stats-card text-center">
            <div class="card-body">
                <div class="text-primary mb-2">
                    <i class="bi bi-people fs-1"></i>
                </div>
                <h3 class="card-title">{{ $totalClientes }}</h3>
                <p class="card-text text-muted">Clientes</p>
                <a href="{{ route('clientes.index') }}" class="btn btn-sm btn-outline-primary">Ver todos</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card text-center">
            <div class="card-body">
                <div class="text-success mb-2">
                    <i class="bi bi-box-seam fs-1"></i>
                </div>
                <h3 class="card-title">{{ $totalProductos }}</h3>
                <p class="card-text text-muted">Productos</p>
                <a href="{{ route('productos.index') }}" class="btn btn-sm btn-outline-success">Ver todos</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card text-center">
            <div class="card-body">
                <div class="text-warning mb-2">
                    <i class="bi bi-boxes fs-1"></i>
                </div>
                <h3 class="card-title">{{ $totalInventario }}</h3>
                <p class="card-text text-muted">Stock Total</p>
                <a href="{{ route('inventarios.index') }}" class="btn btn-sm btn-outline-warning">Ver inventario</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card text-center">
            <div class="card-body">
                <div class="text-danger mb-2">
                    <i class="bi bi-exclamation-triangle fs-1"></i>
                </div>
                <h3 class="card-title">{{ $stockBajo }}</h3>
                <p class="card-text text-muted">Stock Bajo</p>
                @if($stockBajo > 0)
                    <a href="{{ route('inventarios.index') }}" class="btn btn-sm btn-outline-danger">Revisar</a>
                @else
                    <span class="badge bg-success">Todo OK</span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Accesos Rápidos -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Accesos Rápidos
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-grid">
                            <a href="{{ route('clientes.create') }}" class="btn btn-outline-primary">
                                <i class="bi bi-person-plus me-2"></i>
                                Nuevo Cliente
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-grid">
                            <a href="{{ route('productos.create') }}" class="btn btn-outline-success">
                                <i class="bi bi-box-seam me-2"></i>
                                Nuevo Producto
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-grid">
                            <a href="{{ route('inventarios.index') }}" class="btn btn-outline-info">
                                <i class="bi bi-arrow-up me-2"></i>
                                Entrada Inventario
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-grid">
                            <a href="{{ route('inventarios.index') }}" class="btn btn-outline-warning">
                                <i class="bi bi-arrow-down me-2"></i>
                                Salida Inventario
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-grid">
                            <a href="{{ route('facturas.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-receipt me-2"></i>
                                Ver Facturas
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-grid">
                            <a href="{{ route('facturas.create') }}" class="btn btn-outline-success">
                                <i class="bi bi-plus-circle me-2"></i>
                                Nueva Factura
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Estado del Sistema
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Base de Datos:</span>
                        <span class="badge bg-success">Activa</span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Inventario:</span>
                        @if($stockBajo == 0)
                            <span class="badge bg-success">Óptimo</span>
                        @else
                            <span class="badge bg-warning">Atención</span>
                        @endif
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Último backup:</span>
                        <span class="badge bg-info">Hoy</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($stockBajo > 0)
<!-- Alertas de Stock Bajo -->
<div class="row">
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Productos con Stock Bajo
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Los siguientes productos necesitan reposición:</p>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Stock Actual</th>
                                <th>Stock Mínimo</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productosStockBajo as $producto)
                                <tr>
                                    <td>{{ $producto->nombre }}</td>
                                    <td>
                                        <span class="badge bg-danger">{{ $producto->inventario->stock_actual ?? 0 }}</span>
                                    </td>
                                    <td>{{ $producto->stock_minimo }}</td>
                                    <td>
                                        <a href="{{ route('inventarios.index') }}" class="btn btn-sm btn-outline-primary">
                                            Reabastecer
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
