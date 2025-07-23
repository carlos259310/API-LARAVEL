@extends('layouts.sidebar')
@section('title', 'Facturas')
@section('content')
<div class="container-fluid">
    <h2>Sistema de Facturas</h2>
    
    <div class="card">
        <div class="card-body">
            <p>Página de facturas cargada correctamente</p>
            <p>Número de facturas: {{ $facturas->count() }}</p>
            
            @if($facturas->count() > 0)
                <p>Hay {{ $facturas->count() }} facturas</p>
            @else
                <p>No hay facturas registradas</p>
                <a href="{{ route('facturas.create') }}" class="btn btn-primary">Nueva Factura</a>
            @endif
        </div>
    </div>
</div>
@endsection
