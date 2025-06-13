<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Laravel HTTP Client
use Illuminate\Support\Facades\Log;   // Untuk logging error

class ChatbotController extends Controller
{
    /**
     * Menampilkan halaman chat.
     */
    public function index()
    {
        return view('chatbot.index'); // Kita akan buat view ini
    }

    /**
     * Mengirim pesan ke Groq API dan mengembalikan respons.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000', // Batasi panjang pesan
            'history' => 'nullable|array' // Untuk mengirim riwayat percakapan sebelumnya
        ]);

        $userMessage = $request->input('message');
        $conversationHistory = $request->input('history', []); // Ambil riwayat atau array kosong

        // Format pesan untuk Groq API (mirip OpenAI)
        $messages = [];

        // Tambahkan riwayat percakapan sebelumnya ke messages
        foreach ($conversationHistory as $entry) {
            if (isset($entry['role']) && isset($entry['content'])) {
                 $messages[] = [
                    'role' => $entry['role'],
                    'content' => $entry['content']
                ];
            }
        }
        // Tambahkan pesan pengguna saat ini
        $messages[] = ['role' => 'user', 'content' => $userMessage];


        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('groq.api_key'),
                'Content-Type' => 'application/json',
            ])->post(config('groq.api_url'), [
                'model' => config('groq.default_model'), // Misalnya 'llama3-8b-8192' atau 'mixtral-8x7b-32768'
                'messages' => $messages,
                'temperature' => 0.7, // Sesuaikan kreativitas (0.0 - 1.0)
                'max_tokens' => 1024,  // Batas token untuk respons
                // 'stream' => false, // Untuk respons non-streaming saat ini
            ]);

            if ($response->successful()) {
                $botResponse = $response->json()['choices'][0]['message']['content'];
                return response()->json(['reply' => $botResponse]);
            } else {
                Log::error('Groq API Error: ' . $response->body());
                return response()->json(['error' => 'Maaf, terjadi kesalahan saat menghubungi chatbot. Respons: ' . $response->status()], 500);
            }
        } catch (\Exception $e) {
            Log::error('Groq API Exception: ' . $e->getMessage());
            return response()->json(['error' => 'Maaf, terjadi kesalahan internal.'], 500);
        }
    }
}