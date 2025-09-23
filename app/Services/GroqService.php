<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GroqService
{
    protected $client;
    protected $apiKey;
    protected $apiUrl;
    protected $model;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('GROQ_API_KEY');
        $this->apiUrl = env('GROQ_API_URL');
        $this->model = env('GROQ_MODEL', 'llama-3.1-8b-instant');
    }

    public function enviarMensaje($mensaje, $contextoUsuario = null)
    {
        try {
            $prompt = $this->construirPrompt($mensaje, $contextoUsuario);
            
            $response = $this->client->post($this->apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Eres un asistente financiero profesional que puede responder consultas generales y analizar datos financieros específicos del usuario.

TIPOS DE CONSULTA:
1. **CONSULTAS GENERALES** (definiciones, conceptos, consejos)
2. **ANÁLISIS DE DATOS** (usar información financiera específica del usuario)
3. **CÁLCULOS MATEMÁTICOS** (con datos numéricos específicos)

PARA CONSULTAS GENERALES:
- Responde de forma clara y directa
- Usa **texto** solo para resaltar lo más importante
- Mantén respuestas concisas y útiles
- Enfócate en información práctica para Colombia

PARA ANÁLISIS DE DATOS FINANCIEROS:
- Usa SOLO los datos proporcionados en el contexto del usuario
- Analiza y presenta la información de forma clara
- Identifica patrones y tendencias importantes
- Proporciona insights útiles basados en los datos reales

PARA CÁLCULOS MATEMÁTICOS:
- Realiza operaciones con precisión absoluta
- Usa solo los números exactos proporcionados
- NO inventes datos ni hagas suposiciones
- Formato monetario colombiano: $1.000.000

ESTRUCTURA DE RESPUESTA:
1. **Respuesta directa** al inicio
2. Análisis o cálculo relevante
3. Consejo práctico

EJEMPLOS:

Consulta general:
"¿Qué es un datacredito?" → "Un **datacredito** es tu historial crediticio en Colombia..."

Análisis de datos:
"¿Cuáles son mis mayores gastos?" → "Tus **mayores gastos** son: [análisis basado en datos reales del usuario]"

Cálculo:
"¿En cuánto tiempo ahorro $1.000.000?" → "Necesitarás **9 meses** para alcanzar tu meta..."

REGLAS:
- Si hay datos específicos del usuario, úsalos para dar respuestas personalizadas
- Para consultas sobre gastos/ingresos/ahorros, analiza los datos proporcionados
- Mantén respuestas profesionales y útiles
- No inventes información que no esté en los datos'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'max_tokens' => 800,
                    'temperature' => 0.1
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return $data['choices'][0]['message']['content'] ?? 'No se pudo procesar la respuesta';

        } catch (RequestException $e) {
            return 'Error al comunicarse con GroqAI: ' . $e->getMessage();
        }
    }

    private function construirPrompt($mensaje, $contextoUsuario)
    {
        $prompt = "Pregunta: {$mensaje}\n\n";
        
        if ($contextoUsuario) {
            $prompt .= "Datos financieros del usuario:\n";
            $prompt .= $contextoUsuario . "\n\n";
        }
        
        $prompt .= "Instrucciones:
        - Si la pregunta contiene números específicos, realiza el cálculo exacto
        - Si es una consulta general (definiciones, conceptos), explica de forma clara y práctica
        - Responde según el contexto financiero colombiano cuando sea relevante
        - Sé directo y útil en tu respuesta";
        
        return $prompt;
    }
}