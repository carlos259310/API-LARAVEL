<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Departamento;
use App\Models\Ciudad;
use App\Models\TipoDocumento;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::with(['tipoDocumento', 'ciudad', 'departamento'])
            ->paginate(15);
            
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tiposDocumento = TipoDocumento::where('activo', true)->get();
        $departamentos = Departamento::where('activo', true)->get();
        $ciudades = Ciudad::where('activo', true)->get();
        
        return view('clientes.create', compact('tiposDocumento', 'departamentos', 'ciudades'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_1' => 'required|string|max:255',
            'nombre_2' => 'nullable|string|max:255',
            'apellido_1' => 'required|string|max:255',
            'apellido_2' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:clientes,email',
            'id_tipo_documento' => 'required|exists:tipos_documentos,id',
            'numero_documento' => 'required|string|unique:clientes,numero_documento',
            'razon_social' => 'nullable|string|max:255',
            'tipo_cliente' => 'required|in:natural,juridico',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            'id_ciudad' => 'required|exists:ciudades,id',
            'id_departamento' => 'required|exists:departamentos,id',
            'activo' => 'boolean'
        ]);

        Cliente::create($validated);
        
        return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cliente = Cliente::with(['tipoDocumento', 'ciudad', 'departamento'])->findOrFail($id);
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        $tiposDocumento = TipoDocumento::where('activo', true)->get();
        $departamentos = Departamento::where('activo', true)->get();
        $ciudades = Ciudad::where('activo', true)->get();
        
        return view('clientes.edit', compact('cliente', 'tiposDocumento', 'departamentos', 'ciudades'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cliente = Cliente::findOrFail($id);
        
        $validated = $request->validate([
            'nombre_1' => 'required|string|max:255',
            'nombre_2' => 'nullable|string|max:255',
            'apellido_1' => 'required|string|max:255',
            'apellido_2' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:clientes,email,' . $cliente->getAttribute('id'),
            'id_tipo_documento' => 'required|exists:tipos_documentos,id',
            'numero_documento' => 'required|string|unique:clientes,numero_documento,' . $cliente->getAttribute('id'),
            'razon_social' => 'nullable|string|max:255',
            'tipo_cliente' => 'required|in:natural,juridico',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            'id_ciudad' => 'required|exists:ciudades,id',
            'id_departamento' => 'required|exists:departamentos,id',
            'activo' => 'boolean'
        ]);

        $cliente->update($validated);
        
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();
        
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente.');
    }
}
