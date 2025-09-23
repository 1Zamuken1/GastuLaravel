<?php

namespace App\Http\Controllers;

use App\Services\GroqService;
use App\Services\DatabaseQueryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class IAController extends Controller
{
    protected $groqService;
    
    public function __construct(GroqService $groqService)
    {
        $this->groqService = $groqService;
        $this->middleware('groq.auth');
    }

    /**
     * Muestra la interfaz del chat
     */
    public function index()
    {
        return view('ia.chat');
    }

    /**
     * Procesa mensaje del usuario y devuelve respuesta de IA
     */
    public function procesarMensaje(Request $request)
    {
        $request->validate([
            'mensaje' => 'required|string|max:1000'
        ]);

        try {
            $usuario = Auth::user();
            $dbService = new DatabaseQueryService($usuario->usuario_id);
            
            // Obtener contexto específico según el mensaje
            $contexto = $this->construirContextoEspecifico($dbService, $request->mensaje);
            
            // Procesar mensaje con GroqAI
            $respuesta = $this->groqService->enviarMensaje(
                $request->mensaje, 
                $contexto
            );

            return response()->json([
                'success' => true,
                'respuesta' => $respuesta,
                'timestamp' => now()->format('H:i')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error procesando mensaje: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene estadísticas generales del usuario
     */
    public function obtenerEstadisticas()
    {
        try {
            $usuario = Auth::user();
            $dbService = new DatabaseQueryService($usuario->usuario_id);
            
            $contexto = $dbService->obtenerContextoUsuario();
            
            return response()->json([
                'success' => true,
                'datos' => $contexto
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error obteniendo estadísticas'
            ], 500);
        }
    }

    /**
     * Consulta personalizada a la base de datos
     */
    public function consultaPersonalizada(Request $request)
    {
        $request->validate([
            'tabla' => 'required|string',
            'filtros' => 'array'
        ]);

        try {
            $usuario = Auth::user();
            $dbService = new DatabaseQueryService($usuario->usuario_id);
            
            $resultados = $dbService->consultarTabla(
                $request->tabla,
                'usuario_id',
                $request->filtros ?? []
            );

            return response()->json([
                'success' => true,
                'datos' => $resultados,
                'count' => $resultados->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error en consulta personalizada'
            ], 500);
        }
    }

    /**
     * Construye contexto específico según el tipo de consulta
     */
    private function construirContextoEspecifico($dbService, $mensaje)
    {
        $mensajeLower = strtolower($mensaje);
        $contextoBasico = $dbService->obtenerContextoUsuario();
        
        $texto = "INFORMACIÓN FINANCIERA DEL USUARIO:\n\n";
        
        // Información básica siempre presente
        $balance = $contextoBasico['ingresos']['total'] - $contextoBasico['egresos']['total'];
        $texto .= "RESUMEN GENERAL:\n";
        $texto .= "- Total ingresos: $" . number_format($contextoBasico['ingresos']['total'], 0, ',', '.') . "\n";
        $texto .= "- Total egresos: $" . number_format($contextoBasico['egresos']['total'], 0, ',', '.') . "\n";
        $texto .= "- Balance actual: $" . number_format($balance, 0, ',', '.') . "\n\n";

        // Contexto específico según la consulta
        if (str_contains($mensajeLower, 'gastos') || str_contains($mensajeLower, 'mayores') || str_contains($mensajeLower, 'egresos')) {
            $texto .= $this->obtenerContextoGastos($dbService);
        } 
        elseif (str_contains($mensajeLower, 'ahorros') || str_contains($mensajeLower, 'metas')) {
            $texto .= $this->obtenerContextoAhorros($dbService);
        }
        elseif (str_contains($mensajeLower, 'ingresos') || str_contains($mensajeLower, 'entradas')) {
            $texto .= $this->obtenerContextoIngresos($dbService);
        }
        elseif (str_contains($mensajeLower, 'balance') || str_contains($mensajeLower, 'estado')) {
            // Ya incluimos el balance arriba, no necesita contexto adicional
        }
        
        return $texto;
    }

    /**
     * Obtiene contexto detallado de gastos
     */
    private function obtenerContextoGastos($dbService)
    {
        // Obtener los mayores gastos por monto
        $mayoresGastos = $dbService->consultarTablaConJoin(
            'egreso',
            'concepto_egreso',
            'egreso.concepto_egreso_id',
            'concepto_egreso.concepto_egreso_id',
            ['egreso.monto', 'egreso.descripcion', 'concepto_egreso.nombre as concepto', 'egreso.fecha_registro'],
            []
        )->sortByDesc('monto')->take(10);

        // Gastos agrupados por concepto
        $gastosPorConcepto = $dbService->ejecutarConsultaPersonalizada(
            "SELECT ce.nombre as concepto, SUM(e.monto) as total_monto, COUNT(*) as cantidad
             FROM egreso e 
             LEFT JOIN concepto_egreso ce ON e.concepto_egreso_id = ce.concepto_egreso_id 
             GROUP BY ce.concepto_egreso_id, ce.nombre 
             ORDER BY total_monto DESC 
             LIMIT 10"
        );

        $texto = "DETALLE DE GASTOS:\n\n";
        
        $texto .= "MAYORES GASTOS INDIVIDUALES:\n";
        foreach ($mayoresGastos->take(5) as $index => $gasto) {
            $texto .= ($index + 1) . ". $" . number_format($gasto->monto, 0, ',', '.') . 
                     " - " . ($gasto->concepto ?? 'Sin categoría') . 
                     " (" . ($gasto->descripcion ?? 'Sin descripción') . ")\n";
        }
        
        $texto .= "\nGASTOS POR CATEGORÍA:\n";
        foreach ($gastosPorConcepto as $index => $categoria) {
            $texto .= ($index + 1) . ". " . ($categoria->concepto ?? 'Sin categoría') . 
                     ": $" . number_format($categoria->total_monto, 0, ',', '.') . 
                     " (" . $categoria->cantidad . " registros)\n";
        }

        return $texto . "\n";
    }

    /**
     * Obtiene contexto detallado de ahorros
     */
    private function obtenerContextoAhorros($dbService)
    {
        $metas = $dbService->consultarTabla('ahorro_meta')->sortByDesc('monto_meta');
        
        $texto = "DETALLE DE AHORROS:\n\n";
        
        if ($metas->count() > 0) {
            $texto .= "METAS DE AHORRO:\n";
            foreach ($metas->take(5) as $index => $meta) {
                $progreso = $meta->monto_meta > 0 ? ($meta->total_acumulado / $meta->monto_meta) * 100 : 0;
                $texto .= ($index + 1) . ". " . $meta->concepto . 
                         " - Meta: $" . number_format($meta->monto_meta, 0, ',', '.') . 
                         " - Acumulado: $" . number_format($meta->total_acumulado, 0, ',', '.') . 
                         " (" . number_format($progreso, 1) . "% completado)\n";
            }
        } else {
            $texto .= "No tienes metas de ahorro registradas.\n";
        }

        return $texto . "\n";
    }

    /**
     * Obtiene contexto detallado de ingresos
     */
    private function obtenerContextoIngresos($dbService)
    {
        // Mayores ingresos individuales
        $mayoresIngresos = $dbService->consultarTablaConJoin(
            'ingreso',
            'concepto_ingreso',
            'ingreso.concepto_ingreso_id',
            'concepto_ingreso.concepto_ingreso_id',
            ['ingreso.monto', 'ingreso.descripcion', 'concepto_ingreso.nombre as concepto'],
            []
        )->sortByDesc('monto')->take(5);

        // Ingresos por concepto
        $ingresosPorConcepto = $dbService->ejecutarConsultaPersonalizada(
            "SELECT ci.nombre as concepto, SUM(i.monto) as total_monto, COUNT(*) as cantidad
             FROM ingreso i 
             LEFT JOIN concepto_ingreso ci ON i.concepto_ingreso_id = ci.concepto_ingreso_id 
             GROUP BY ci.concepto_ingreso_id, ci.nombre 
             ORDER BY total_monto DESC 
             LIMIT 5"
        );

        $texto = "DETALLE DE INGRESOS:\n\n";
        
        $texto .= "MAYORES INGRESOS:\n";
        foreach ($mayoresIngresos as $index => $ingreso) {
            $texto .= ($index + 1) . ". $" . number_format($ingreso->monto, 0, ',', '.') . 
                     " - " . ($ingreso->concepto ?? 'Sin categoría') . "\n";
        }
        
        $texto .= "\nINGRESOS POR CATEGORÍA:\n";
        foreach ($ingresosPorConcepto as $index => $categoria) {
            $texto .= ($index + 1) . ". " . ($categoria->concepto ?? 'Sin categoría') . 
                     ": $" . number_format($categoria->total_monto, 0, ',', '.') . "\n";
        }

        return $texto . "\n";
    }
}