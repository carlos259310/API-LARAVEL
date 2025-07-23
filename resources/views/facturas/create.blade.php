@extends('layouts.sidebar')
@section('title', 'Nueva Factura')
@section('content')
<div class="container-fluid">
    <h2>Nueva Factura</h2>
    <form action="{{ route('facturas.store') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="cliente_id" class="form-label">Cliente</label>
                <select name="cliente_id" id="cliente_id" class="form-select" required>
                    <option value="">Seleccione...</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->getAttribute('id') }}">
                            {{ $cliente->getAttribute('nombre_1') }} {{ $cliente->getAttribute('nombre_2') }} {{ $cliente->getAttribute('apellido_1') }} {{ $cliente->getAttribute('apellido_2') }}
                            @if($cliente->getAttribute('razon_social'))
                                - {{ $cliente->getAttribute('razon_social') }}
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="id_bodega" class="form-label">Bodega</label>
                <select name="id_bodega" id="id_bodega" class="form-select" required>
                    <option value="">Seleccione...</option>
                    @foreach($bodegas as $bodega)
                        <option value="{{ $bodega->getAttribute('id') }}">{{ $bodega->getAttribute('nombre') }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <h5>Productos</h5>
        <table class="table table-bordered" id="productos-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select name="productos[0][producto_id]" class="form-select producto-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->getAttribute('id') }}" data-precio="{{ $producto->getAttribute('precio_venta') }}">{{ $producto->getAttribute('nombre') }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="productos[0][cantidad]" class="form-control cantidad-input" min="1" value="1" required></td>
                    <td><input type="text" class="form-control precio-input" value="0" readonly></td>
                    <td><input type="text" class="form-control subtotal-input" value="0" readonly></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-secondary mb-3" id="add-producto">Agregar Producto</button>
        <div class="mb-3">
            <strong>Total: $<span id="total-factura">0.00</span></strong>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Factura</button>
    </form>
</div>
@push('scripts')
<script>
let rowIdx = 1;
function updateRow(row) {
    const select = row.querySelector('.producto-select');
    const precio = select.options[select.selectedIndex]?.getAttribute('data-precio') || 0;
    row.querySelector('.precio-input').value = precio;
    const cantidad = row.querySelector('.cantidad-input').value || 1;
    row.querySelector('.subtotal-input').value = (precio * cantidad).toFixed(2);
}
function updateTotal() {
    let total = 0;
    document.querySelectorAll('.subtotal-input').forEach(input => {
        total += parseFloat(input.value) || 0;
    });
    document.getElementById('total-factura').innerText = total.toFixed(2);
}
document.getElementById('add-producto').onclick = function() {
    const table = document.getElementById('productos-table').querySelector('tbody');
    const newRow = table.rows[0].cloneNode(true);
    newRow.querySelectorAll('input,select').forEach(input => {
        if(input.type === 'number') input.value = 1;
        if(input.classList.contains('precio-input') || input.classList.contains('subtotal-input')) input.value = 0;
    });
    newRow.querySelectorAll('select, input').forEach(el => {
        const name = el.getAttribute('name');
        if(name) el.setAttribute('name', name.replace(/\d+/, rowIdx));
    });
    newRow.querySelector('.producto-select').selectedIndex = 0;
    newRow.querySelector('td:last-child').innerHTML = '<button type="button" class="btn btn-danger btn-sm remove-producto">Eliminar</button>';
    table.appendChild(newRow);
    rowIdx++;
};
document.addEventListener('change', function(e) {
    if(e.target.classList.contains('producto-select') || e.target.classList.contains('cantidad-input')) {
        const row = e.target.closest('tr');
        updateRow(row);
        updateTotal();
    }
});
document.addEventListener('click', function(e) {
    if(e.target.classList.contains('remove-producto')) {
        e.target.closest('tr').remove();
        updateTotal();
    }
});
document.querySelectorAll('.producto-select, .cantidad-input').forEach(el => {
    el.addEventListener('change', function() {
        const row = el.closest('tr');
        updateRow(row);
        updateTotal();
    });
});
</script>
@endpush
