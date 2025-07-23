@extends('layouts.sidebar')

@section('title', 'Gestión de Productos')
@section('page-title', 'Productos')
@section('page-description', 'Catálogo de productos y gestión de información')

@section('page-actions')
    <a href="{{ route('productos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nuevo Producto
    </a>
@endsection

@section('content')

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Marca</th>
                        <th>Proveedor</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $producto)
                        <tr>
                            <td>{{ $producto->codigo }}</td>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->categoria->nombre ?? 'N/A' }}</td>
                            <td>{{ $producto->marca->nombre ?? 'N/A' }}</td>
                            <td>{{ $producto->proveedor->nombre ?? 'N/A' }}</td>
                            <td>${{ number_format($producto->precio_venta, 2, ',', '.') }}</td>
                            <td>
                                @if($producto->inventario && $producto->inventario->stock_actual <= $producto->stock_minimo)
                                    <span class="badge bg-danger">
                                        {{ $producto->inventario->stock_actual ?? 0 }}
                                    </span>
                                @elseif($producto->inventario && $producto->inventario->stock_actual <= ($producto->stock_minimo * 1.5))
                                    <span class="badge bg-warning">
                                        {{ $producto->inventario->stock_actual ?? 0 }}
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        {{ $producto->inventario->stock_actual ?? 0 }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($producto->activo)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('productos.show', $producto) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('productos.edit', $producto) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('productos.destroy', $producto) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este producto?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                No hay productos registrados.
                                <a href="{{ route('productos.create') }}">Crear el primer producto</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($productos->hasPages())
            <div class="d-flex justify-content-center">
                {{ $productos->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
