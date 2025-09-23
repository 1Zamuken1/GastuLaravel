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
                            'content' => 'Eres un asistente financiero que realiza cálculos matemáticos exactos.

INSTRUCCIONES CRÍTICAS:
- Realiza operaciones matemáticas paso a paso con precisión absoluta
- Verifica cada cálculo antes de presentar el resultado
- Usa solo los números exactos proporcionados por el usuario
- NO inventes datos ni hagas suposiciones

FORMATO DE RESPUESTA OBLIGATORIO:
1. **Datos utilizados:** (lista los números exactos del usuario)
2. **Cálculo paso a paso:** (muestra la operación matemática completa)
3. **Resultado final:** (respuesta clara y precisa)

REGLAS MATEMÁTICAS:
- Para tiempo de ahorro: Monto objetivo ÷ Ahorro periódico = Número de períodos
- Siempre muestra la división completa con decimales
- Convierte decimales a tiempo real (ej: 9.09 meses = 9 meses y 3 días aprox.)
- Usa formato monetario colombiano: $1.000.000

EJEMPLO DE CÁLCULO CORRECTO:
Si alguien ahorra $110.000 mensuales para llegar a $1.000.000:
$1.000.000 ÷ $110.000 = 9.09 meses (NO años)

VERIFICACIÓN: Siempre confirma que tu resultado tiene sentido lógicamente.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'max_tokens' => 1000,
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
        $prompt = "Consulta del usuario: {$mensaje}\n\n";
        
        if ($contextoUsuario) {
            $prompt .= "Información financiera del usuario:\n";
            $prompt .= $contextoUsuario . "\n\n";
        }
        
        $prompt .= "Instrucciones específicas:
        - Identifica los datos numéricos en la consulta
        - Realiza el cálculo matemático exacto
        - Presenta el resultado en el formato solicitado
        - Mantén precisión en los cálculos
        - Usa formato claro y profesional";
        
        return $prompt;
    }
}