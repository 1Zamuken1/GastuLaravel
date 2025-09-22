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
            
            // Obtener contexto del usuario
            $contexto = $this->construirContexto($dbService);
            
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

    private function construirContexto($dbService)
    {
        $contexto = $dbService->obtenerContextoUsuario();
        
        $texto = "INFORMACIÓN FINANCIERA DEL USUARIO:\n\n";
        
        $texto .= "INGRESOS:\n";
        $texto .= "- Total de ingresos: $" . number_format($contexto['ingresos']['total'], 2) . "\n";
        $texto .= "- Cantidad de registros: " . $contexto['ingresos']['count'] . "\n\n";
        
        $texto .= "EGRESOS:\n";
        $texto .= "- Total de egresos: $" . number_format($contexto['egresos']['total'], 2) . "\n";
        $texto .= "- Cantidad de registros: " . $contexto['egresos']['count'] . "\n\n";
        
        $texto .= "AHORROS:\n";
        $texto .= "- Cantidad de metas: " . $contexto['ahorros']['count'] . "\n\n";
        
        // Balance
        $balance = $contexto['ingresos']['total'] - $contexto['egresos']['total'];
        $texto .= "BALANCE ACTUAL: $" . number_format($balance, 2) . "\n";
        
        return $texto;
    }
}