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
        $this->model = env('GROQ_MODEL', 'llama3-8b-8192');
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
                            'content' => 'Eres un asistente financiero especializado. Tienes acceso a la información financiera del usuario y puedes ayudarle con análisis, consejos y consultas sobre sus ingresos, egresos y ahorros.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'max_tokens' => 1000,
                    'temperature' => 0.7
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
        $prompt = "Usuario: {$mensaje}\n\n";
        
        if ($contextoUsuario) {
            $prompt .= "Contexto financiero del usuario:\n";
            $prompt .= $contextoUsuario . "\n\n";
        }
        
        $prompt .= "Por favor, proporciona una respuesta útil y personalizada basada en la información financiera disponible.";
        
        return $prompt;
    }
}