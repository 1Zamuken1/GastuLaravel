<?php

namespace App\Http\Controllers;

use App\Models\AhorroMeta;
use App\Models\AporteAhorro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AhorroMetaController extends Controller
{
    // Mostrar todos los ahorros en la vista principal
    public function index()
    {
        $userId = 2; // auth()->id();
        $ahorros = AhorroMeta::where('usuario_id', $userId)->get();

        // Calcular porcentaje de avance dinámico
        foreach ($ahorros as $ahorro) {
            $ahorro->porcentaje_avance = $this->calcularPorcentaje($ahorro);
        }

        return view('ahorros.ahorros', compact('ahorros'));
    }

    // Mostrar el modal con detalle de un ahorro
    public function show($id)
    {
        $userId = 2;
        $ahorro = AhorroMeta::where('usuario_id', $userId)->findOrFail($id);

        $ahorro->porcentaje_avance = $this->calcularPorcentaje($ahorro);

        return view('ahorros.partials.showModal', compact('ahorro'));
    }

    // Crear un ahorro (desde el modal create)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'concepto' => 'required|string|max:60',
            'descripcion' => 'nullable|string|max:100',
            'monto_meta' => 'required|numeric|min:1',
            'frecuencia' => 'required|string|max:30',
            'fecha_meta' => 'required|date|after:today',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $ahorro = AhorroMeta::create([
            'usuario_id' => 2, // auth()->id()
            'concepto' => $request->concepto,
            'descripcion' => $request->descripcion,
            'monto_meta' => $request->monto_meta,
            'frecuencia' => $request->frecuencia,
            'fecha_meta' => $request->fecha_meta,
            'estado' => 'Activo',
            'total_acumulado' => 0,
            'cantidad_cuotas' => $request->cantidad_cuotas ?? null,
        ]);

        return redirect()->route('ahorros.index')->with('success', 'Ahorro creado correctamente.');
    }

    // Editar un ahorro (desde el modal edit)
    public function update(Request $request, $id)
    {
        $userId = 2;
        $ahorro = AhorroMeta::where('usuario_id', $userId)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'concepto' => 'required|string|max:60',
            'descripcion' => 'nullable|string|max:100',
            'monto_meta' => 'required|numeric|min:1',
            'frecuencia' => 'required|string|max:30',
            'fecha_meta' => 'required|date|after:today',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $ahorro->update($request->only(['concepto','descripcion','monto_meta','frecuencia','fecha_meta']));

        return redirect()->route('ahorros.index')->with('success', 'Ahorro actualizado correctamente.');
    }

    // Eliminar un ahorro
    public function destroy($id)
    {
        $userId = 2;
        $ahorro = AhorroMeta::where('usuario_id', $userId)->findOrFail($id);

        $ahorro->delete();

        return redirect()->route('ahorros.index')->with('success', 'Ahorro eliminado correctamente.');
    }

    // 📌 Función auxiliar para calcular porcentaje
    private function calcularPorcentaje($ahorro)
    {
        if ($ahorro->monto_meta > 0) {
            $avance = ($ahorro->total_acumulado / $ahorro->monto_meta) * 100;
            return number_format(min($avance, 100), 0) . '%';
        }
        return "0%";
    }
}
