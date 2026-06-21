<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CardScannerController extends Controller
{
    public function processImage(Request $request)
    {
        $request->validate([
            'image' => 'required|string'
        ]);

        $base64Image = $request->input('image');
        
        $imageParts = explode(',', $base64Image);
        $cleanBase64 = isset($imageParts[1]) ? $imageParts[1] : $base64Image;

        $prompt = "Você é um especialista em Pokémon TCG. Analise a imagem desta carta. " . 
                  "Retorne EXATAMENTE um JSON válido. " .
                  "O JSON deve conter as chaves: " .
                  "'nome' (o nome do personagem/carta), " .
                  "'numero' (o número da coleção, ex: 170/165) e " .
                  "'raridade' (identifique se é Full Art, Holo, Reverse Holo, etc, se possível).";

        try {
            $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . env('GEMINI_API_KEY');

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->timeout(30)
            ->withoutVerifying()
            ->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            [
                                'inlineData' => [
                                    'mimeType' => 'image/jpeg',
                                    'data' => $cleanBase64
                                ]
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json',
                ]
            ]);

            if ($response->failed()) {
                Log::error('Erro na API Gemini: ' . $response->body());
                return response()->json(['error' => 'Falha ao processar a imagem na IA.'], 500);
            }

            $aiResponseText = $response->json('candidates.0.content.parts.0.text');
            $cardData = json_decode($aiResponseText, true);

            return response()->json([
                'message' => 'Carta identificada com sucesso!',
                'data' => $cardData
            ]);

        } catch (\Exception $e) {
            Log::error('Erro interno no Controller: ' . $e->getMessage());
            return response()->json(['error' => 'Ocorreu um erro interno no servidor.'], 500);
        }
    }
}