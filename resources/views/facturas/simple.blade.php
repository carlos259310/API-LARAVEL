<!DOCTYPE html>
<html>
<head>
    <title>Debug Facturas</title>
</head>
<body>
    <h1>Debug - Sistema de Facturas</h1>
    
    @if(isset($error))
        <div style="background: red; color: white; padding: 10px; margin: 10px 0;">
            <strong>Error:</strong> {{ $error }}
        </div>
    @else
        <p>Vista de debug cargada correctamente.</p>
        <p>El controlador está funcionando.</p>
        <a href="{{ route('facturas.index') }}">Volver a Facturas</a>
    @endif
</body>
</html>
