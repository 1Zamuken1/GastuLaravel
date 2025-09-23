<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ConceptoIngreso;
use Illuminate\Support\Facades\Validator;
use App\Models\ProyeccionIngreso;

class ConceptoIngresoAdminController extends Controller
{
    public function index()
    {
        $conceptoIngresos = ConceptoIngreso::all();
        return view('Admin.conceptoIngresos.index', compact('conceptoIngresos'));
    }

    public function create()
    {
        return view('Admin.conceptoIngresos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:200',
        ]);

        ConceptoIngreso::create($request->only('nombre', 'descripcion'));

        return redirect()->route('conceptoIngresos.index')
            ->with('success', 'Concepto de ingreso creado exitosamente.');
    }

    public function show($id)
    {
        $conceptoIngreso = ConceptoIngreso::findOrFail($id);
        return view('Admin.conceptoIngresos.show', compact('conceptoIngreso'));
    }

    public function edit($id)
    {
        $conceptoIngreso = ConceptoIngreso::findOrFail($id);
        return view('Admin.conceptoIngresos.edit', compact('conceptoIngreso'));
    }

    public function update(Request $request, $id)
    {
        $conceptoIngreso = ConceptoIngreso::findOrFail($id);
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:200',
        ]);
        $conceptoIngreso->update($request->only('nombre', 'descripcion'));
        return redirect()->route('conceptoIngresos.index')
            ->with('success', 'Concepto de ingreso actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $conceptoIngreso = ConceptoIngreso::findOrFail($id);
        $conceptoIngreso->delete();
        return redirect()->route('conceptoIngresos.index')
            ->with('success', 'Concepto de ingreso eliminado exitosamente.');
    }


    public function updatePartial(Request $request, $id){
        $conceptoIngreso = ConceptoIngreso::find($id);

        if(!$conceptoIngreso){
            $data = [
                'message' => 'Concepto de ingreso no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'nullable|string|max:100',
            'descripcion' => 'nullable|string|max:200',
            'usuario_id' => 'nullable|integer',
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        if($request->has('nombre')){
            $conceptoIngreso->nombre = $request->nombre;
        }
        if($request->has('descripcion')){
            $conceptoIngreso->descripcion = $request->descripcion;
        }
        if($request->has('usuario_id')){
           $conceptoIngreso->usuario_id = $request->usuario_id;
        }

        $conceptoIngreso->save();

        $data = [
            'message' => 'Concepto de ingreso actualizado parcialmente exitosamente',
            'conceptoIngreso' => $conceptoIngreso,
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}
