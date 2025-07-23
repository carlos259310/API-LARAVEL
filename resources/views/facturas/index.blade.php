@extends('layouts.sidebar')
@section('title', 'Facturas')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Facturas</h2>
        <a href="{{ route('facturas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Factura
        </a>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="card">
        <div class="card-body">
            @if($facturas && $facturas->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Número</th>
                                <th>Cliente</th>
                                <th>Bodega</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($facturas as $factura)
                            <tr>
                                <td>
                                    <strong>{{ $factura->getNumeroFacturaAttribute() }}</strong>
                                </td>
                                <td>
                                    @if($factura->cliente)
                                        {{ $factura->cliente->getAttribute('nombre_1') }} {{ $factura->cliente->getAttribute('apellido_1') }}
                                        @if($factura->cliente->getAttribute('razon_social'))
                                            <br><small class="text-muted">{{ $factura->cliente->getAttribute('razon_social') }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted">Cliente eliminado</span>
                                    @endif
                                </td>
                                <td>
                                    @if($factura->bodega)
                                        {{ $factura->bodega->getAttribute('nombre') }}
                                    @else
                                        <span class="text-muted">Bodega eliminada</span>
                                    @endif
                                </td>
                                <td>{{ $factura->getAttribute('fecha_factura')->format('d/m/Y') }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($factura->getAttribute('estado')) {
                                            'pagada' => 'bg-success',
                                            'anulada' => 'bg-danger',
                                            default => 'bg-warning text-dark'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ ucfirst($factura->getAttribute('estado')) }}
                                    </span>
                                </td>
                                <td>
                                    <strong>${{ number_format($factura->getAttribute('total'), 2) }}</strong>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('facturas.show', $factura) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($factura->getPuedeModificarseAttribute())
                                            <a href="{{ route('facturas.edit', $factura) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        
                                        @if($factura->getAttribute('estado') === 'pendiente')
                                            <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#pagarModal{{ $factura->getAttribute('id') }}">
                                                <i class="fas fa-dollar-sign"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#anularModal{{ $factura->getAttribute('id') }}">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div class="d-flex justify-content-center mt-3">
                        {{ $facturas->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
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

<!-- Modales para pagar facturas -->
@if($facturas && $facturas->count() > 0)
    @foreach($facturas as $factura)
        @if($factura->getAttribute('estado') === 'pendiente')
            <!-- Modal Pagar -->
            <div class="modal fade" id="pagarModal{{ $factura->getAttribute('id') }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Marcar como Pagada</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>¿Está seguro de marcar como pagada la factura <strong>{{ $factura->getNumeroFacturaAttribute() }}</strong>?</p>
                            <p><strong>Total: ${{ number_format($factura->getAttribute('total'), 2) }}</strong></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <form action="{{ route('facturas.pagar', $factura) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">Marcar como Pagada</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Anular -->
            <div class="modal fade" id="anularModal{{ $factura->getAttribute('id') }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('facturas.anular', $factura) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Anular Factura</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>¿Está seguro de anular la factura <strong>{{ $factura->getNumeroFacturaAttribute() }}</strong>?</p>
                                <p class="text-warning"><i class="fas fa-exclamation-triangle"></i> Esta acción restaurará el inventario y no se puede deshacer.</p>
                                
                                <div class="mb-3">
                                    <label for="motivo_anulacion{{ $factura->getAttribute('id') }}" class="form-label">Motivo de anulación *</label>
                                    <textarea name="motivo_anulacion" id="motivo_anulacion{{ $factura->getAttribute('id') }}" class="form-control" rows="3" required placeholder="Ingrese el motivo de la anulación..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger">Anular Factura</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endif
@endsection
