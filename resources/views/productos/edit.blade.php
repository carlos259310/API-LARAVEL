@extends('layouts.app')

@section('title', 'Editar Producto')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Editar Producto</h1>
    <div class="btn-group">
        <a href="{{ route('productos.show', $producto) }}" class="btn btn-outline-info">
            <i class="bi bi-eye"></i> Ver
        </a>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('productos.update', $producto) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="codigo" class="form-label">Código *</label>
                            <input type="text" class="form-control @error('codigo') is-invalid @enderror" 
                                   id="codigo" name="codigo" value="{{ old('codigo', $producto->codigo) }}" required>
                            @error('codigo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre *</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                  id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $producto->descripcion) }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="categoria_id" class="form-label">Categoría *</label>
                            <select class="form-select @error('categoria_id') is-invalid @enderror" 
                                    id="categoria_id" name="categoria_id" required>
                                <option value="">Seleccionar categoría</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" 
                                            {{ old('categoria_id', $producto->id_categoria) == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categoria_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="marca_id" class="form-label">Marca *</label>
                            <select class="form-select @error('marca_id') is-invalid @enderror" 
                                    id="marca_id" name="marca_id" required>
                                <option value="">Seleccionar marca</option>
                                @foreach($marcas as $marca)
                                    <option value="{{ $marca->id }}" 
                                            {{ old('marca_id', $producto->id_marca) == $marca->id ? 'selected' : '' }}>
                                        {{ $marca->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('marca_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="proveedor_id" class="form-label">Proveedor *</label>
                            <select class="form-select @error('proveedor_id') is-invalid @enderror" 
                                    id="proveedor_id" name="proveedor_id" required>
                                <option value="">Seleccionar proveedor</option>
                                @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}" 
                                            {{ old('proveedor_id', $producto->id_proveedor) == $proveedor->id ? 'selected' : '' }}>
                                        {{ $proveedor->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('proveedor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="precio" class="form-label">Precio *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control @error('precio') is-invalid @enderror" 
                                       id="precio" name="precio" value="{{ old('precio', $producto->precio_venta) }}" 
                                       min="0" step="0.01" required>
                            </div>
                            @error('precio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="stock_minimo" class="form-label">Stock Mínimo *</label>
                            <input type="number" class="form-control @error('stock_minimo') is-invalid @enderror" 
                                   id="stock_minimo" name="stock_minimo" value="{{ old('stock_minimo', $producto->stock_minimo) }}" 
                                   min="0" required>
                            @error('stock_minimo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   id="activo" name="activo" value="1" 
                                   {{ old('activo', $producto->activo) ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo">
                                Producto activo
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Actualizar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
