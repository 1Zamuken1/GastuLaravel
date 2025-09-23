<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ConceptoEgreso;

class ConceptoEgresoAdminController extends Controller
{
    public function index()
    {
        $conceptosEgresos = ConceptoEgreso::all();
        return view('Admin.conceptosEgresos.index', compact('conceptosEgresos'));
    }

    public function create()
    {
        return view('Admin.conceptosEgresos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:200',
        ]);

        ConceptoEgreso::create($request->only('nombre', 'descripcion'));

        return redirect()->route('admin.conceptosEgresos.index')
            ->with('success', 'Concepto de egreso creado exitosamente.');
    }

    public function show($id)
    {
        $conceptoEgreso = ConceptoEgreso::findOrFail($id);
        return view('Admin.conceptosEgresos.show', compact('conceptoEgreso'));
    }

    public function edit($id)
    {
        $conceptoEgreso = ConceptoEgreso::findOrFail($id);
        return view('Admin.conceptosEgresos.edit', compact('conceptoEgreso'));
    }

    public function update(Request $request, $id)
    {
        $conceptoEgreso = ConceptoEgreso::findOrFail($id);
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:200',
        ]);
        $conceptoEgreso->update($request->only('nombre', 'descripcion'));
        return redirect()->route('admin.conceptosEgresos.index')
            ->with('success', 'Concepto de egreso actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $conceptoEgreso = ConceptoEgreso::findOrFail($id);
        $conceptoEgreso->delete();
        return redirect()->route('admin.conceptosEgresos.index')
            ->with('success', 'Concepto de egreso eliminado exitosamente.');
    }
}
