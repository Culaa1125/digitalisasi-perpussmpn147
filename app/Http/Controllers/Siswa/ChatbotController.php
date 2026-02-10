<?php
// app/Http/Controllers/Siswa/ChatbotController.php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ChatHistory;
use App\Models\Konten;
use App\Models\Review;

class ChatbotController extends Controller
{
    private function getSystemContext()
    {
        try {
            // Get actual books list with explicit formatting
            $bukuList = Konten::where('tipe', 'buku')
                ->where('status', 'approved')
                ->withCount(['reviews'])
                ->withAvg('reviews', 'rating')
                ->orderBy('reviews_count', 'desc')
                ->get();
            
            $bukuFormatted = $bukuList->map(function($item) {
                return "JUDUL: {$item->judul} | TIPE: buku | DESKRIPSI: " . ($item->deskripsi ?? 'Tidak ada deskripsi') . " | RATING: " . round($item->reviews_avg_rating ?? 0, 1) . "/5 | REVIEWS: {$item->reviews_count}";
            })->join("\n");

            // Get actual videos list with explicit formatting
            $videoList = Konten::where('tipe', 'video')
                ->where('status', 'approved')
                ->withCount(['reviews'])
                ->withAvg('reviews', 'rating')
                ->orderBy('reviews_count', 'desc')
                ->get();
            
            $videoFormatted = $videoList->map(function($item) {
                return "JUDUL: {$item->judul} | TIPE: video | DESKRIPSI: " . ($item->deskripsi ?? 'Tidak ada deskripsi') . " | RATING: " . round($item->reviews_avg_rating ?? 0, 1) . "/5 | REVIEWS: {$item->reviews_count}";
            })->join("\n");

            $totalBuku = $bukuList->count();
            $totalVideo = $videoList->count();

            return "Kamu adalah AuraBot, asisten virtual cerdas dan ramah untuk perpustakaan digital SMPN 147 Jakarta. 

IDENTITAS:
- Nama: AuraBot
- Kepribadian: Ramah, membantu, informatif, dan antusias tentang pendidikan
- Gaya bicara: Santai tapi sopan, menggunakan Bahasa Indonesia yang baik

INFORMASI SEKOLAH:
- Nama: SMPN 147 Jakarta
- Jenis: Sekolah Menengah Pertama Negeri
- Lokasi: Jakarta, Indonesia

DATA PERPUSTAKAAN DIGITAL:
- Total Buku Digital: {$totalBuku} buku
- Total Video Pembelajaran: {$totalVideo} video
- Semua konten sudah disetujui dan berkualitas

=== DATABASE BUKU (HANYA INI YANG BOLEH DISEBUTKAN) ===
{$bukuFormatted}
=== AKHIR DATABASE BUKU ===

=== DATABASE VIDEO (HANYA INI YANG BOLEH DISEBUTKAN) ===
{$videoFormatted}
=== AKHIR DATABASE VIDEO ===

⚠️ PERINGATAN KERAS - BACA INI DENGAN SEKSAMA ⚠️
Kamu DILARANG KERAS mengarang, menebak, atau menyebutkan judul apapun yang TIDAK ADA di antara tanda === DATABASE === di atas!

Jika database kosong atau tidak ada yang sesuai, katakan:
\"Maaf, saat ini [kategori yang diminta] belum tersedia di perpustakaan digital kami.\"

JANGAN PERNAH menyebutkan judul seperti:
❌ \"Cerita Rakyat\"
❌ \"Echoes of Tomorrow\"
❌ \"The Wonders of Our World\"
❌ Atau judul apapun yang tidak ada di DATABASE di atas

HANYA sebutkan judul yang PERSIS ada di DATABASE dengan format:
✅ \"Dari perpustakaan digital kita, tersedia: [copas PERSIS dari DATABASE]\"

ATURAN PENTING - WAJIB DIIKUTI:
1. **MUTLAK**: Copy-paste PERSIS judul dari DATABASE, jangan ubah atau tambah apapun
2. **DILARANG MENGARANG**: Jika tidak ada di DATABASE = JANGAN disebutkan
3. **Jika diminta daftar**: Salin semua judul dari DATABASE apa adanya
4. **Jika diminta kategori spesifik**: 
   - Cek DATABASE dulu
   - Tidak ada? Katakan: \"Belum tersedia\"
   - Jangan mencoba \"membantu\" dengan mengarang judul lain
5. **Format jawaban wajib**:
   ```
   Dari perpustakaan digital SMPN 147, tersedia:
   
   BUKU:
   - [copas PERSIS dari DATABASE]
   - [copas PERSIS dari DATABASE]
   
   VIDEO:
   - [copas PERSIS dari DATABASE]
   ```
6. HANYA jawab tentang perpustakaan digital, buku, video, dan pendidikan
7. Jika ditanya di luar konteks, arahkan kembali ke topik perpustakaan
8. Ramah tapi faktual - jangan kreatif dalam menyebutkan judul
9. Gunakan emoji sesekali 📚✨

KEMAMPUAN KAMU:
1. Memberikan rekomendasi buku dan video dari daftar yang tersedia
2. Menjelaskan tentang perpustakaan digital sekolah
3. Membantu siswa menemukan materi pembelajaran yang sesuai
4. Menjawab pertanyaan seputar fitur website
5. Memberikan tips belajar yang efektif

CONTOH CARA MENJAWAB YANG BENAR:

**Contoh 1 - User minta daftar buku:**
User: \"daftar buku yang tersedia\"
❌ SALAH: \"Ada Cerita Rakyat, Dongeng Nusantara...\" (MENGARANG!)
✅ BENAR: \"Dari perpustakaan digital SMPN 147, tersedia buku:
- TESTING (Deskripsi: test, Rating: 5/5)
- TESTING2 (Deskripsi: testttqtq, Rating: 0/5)  
- upload siswa test (Deskripsi: test upload siswa, Rating: 0/5)\"

**Contoh 2 - User minta rekomendasi video:**
User: \"rekomendasikan video untuk saya\"
❌ SALAH: \"Saya rekomendasikan 'Wonders of Science'...\" (TIDAK ADA!)
✅ BENAR: \"Dari koleksi video kita ada:
- TESTING (Rating: 5/5)
- video test (Rating: 0/5)
Mau coba yang mana? 🎬\"

**Contoh 3 - User tanya kategori tidak ada:**
User: \"ada buku matematika?\"
❌ SALAH: \"Ada buku Matematika SMP Kelas 7...\" (MENGARANG!)
✅ BENAR: \"Maaf, saat ini belum ada buku khusus matematika di koleksi kami. Yang tersedia: TESTING, TESTING2, dan upload siswa test. 📚\"

**INGAT**: Lebih baik terlihat terbatas tapi JUJUR daripada mengarang dan MENYESATKAN!";
        } catch (\Exception $e) {
            Log::error('Error getting system context: ' . $e->getMessage());
            return "Kamu adalah AuraBot, asisten virtual untuk perpustakaan digital SMPN 147 Jakarta. Jawab pertanyaan dengan ramah dan informatif. PENTING: Hanya rekomendasikan konten yang benar-benar ada di database, jangan mengarang judul.";
        }
    }

    public function chat(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string|max:1000',
                'session_id' => 'required|string'
            ]);

            $userMessage = $request->message;
            $sessionId = $request->session_id;
            $nis = Auth::guard('siswa')->user()->nis;

            // Get recent chat history (last 10 messages) for context
            $recentChats = ChatHistory::where('nis', $nis)
                ->where('session_id', $sessionId)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->reverse();

            // Get API Key
            $apiKey = config('services.gemini.api_key');
            
            if (empty($apiKey)) {
                throw new \Exception('Gemini API key not configured');
            }

            // Prepare system instruction
            $systemInstruction = $this->getSystemContext();

            // Build conversation history in correct format
            $conversationHistory = [];
            
            // Add system instruction as first message (only if no history exists)
            if ($recentChats->isEmpty()) {
                $conversationHistory[] = [
                    'role' => 'user',
                    'parts' => [['text' => $systemInstruction]]
                ];
                $conversationHistory[] = [
                    'role' => 'model',
                    'parts' => [['text' => 'Baik, saya mengerti. Saya adalah AuraBot, asisten virtual untuk perpustakaan digital SMPN 147 Jakarta. Saya siap membantu Anda! 📚✨']]
                ];
            }
            
            foreach($recentChats as $chat) {
                // Add user message
                $conversationHistory[] = [
                    'role' => 'user',
                    'parts' => [['text' => $chat->message]]
                ];
                // Add bot response
                $conversationHistory[] = [
                    'role' => 'model',
                    'parts' => [['text' => $chat->response]]
                ];
            }

            // Add current message
            $conversationHistory[] = [
                'role' => 'user',
                'parts' => [['text' => $userMessage]]
            ];

            // Call Gemini API with retry logic (using Gemini 2.5 Flash - more intelligent)
            $url = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key={$apiKey}";
            
            $maxRetries = 2;
            $retryCount = 0;
            $response = null;
            
            while ($retryCount <= $maxRetries) {
                $response = Http::timeout(30)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                    ])
                    ->post($url, [
                        'contents' => $conversationHistory,
                        'generationConfig' => [
                            'temperature' => 0.7,
                            'topK' => 40,
                            'topP' => 0.95,
                            'maxOutputTokens' => 1024,
                        ],
                        'safetySettings' => [
                            [
                                'category' => 'HARM_CATEGORY_HARASSMENT',
                                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                            ],
                            [
                                'category' => 'HARM_CATEGORY_HATE_SPEECH',
                                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                            ],
                            [
                                'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                            ],
                            [
                                'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                            ],
                        ]
                    ]);
                
                // If successful or non-retryable error, break
                if ($response->successful() || $response->status() !== 429) {
                    break;
                }
                
                // If rate limited and retries remaining, wait and retry
                $retryCount++;
                if ($retryCount <= $maxRetries) {
                    Log::warning("Rate limited, retrying in 2 seconds... (Attempt {$retryCount}/{$maxRetries})");
                    sleep(2); // Wait 2 seconds before retry
                }
            }

            // Log response for debugging
            Log::info('Gemini API Response Status: ' . $response->status());
            
            if (!$response->successful()) {
                Log::error('Gemini API Error: ' . $response->body());
                
                // Provide more detailed error message
                $errorData = $response->json();
                $errorMessage = $errorData['error']['message'] ?? 'Unknown error';
                
                throw new \Exception('Gemini API Error: ' . $errorMessage);
            }

            $data = $response->json();
            
            // Check if response has content
            if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                Log::error('Unexpected Gemini response structure: ' . json_encode($data));
                throw new \Exception('Invalid response from Gemini API');
            }
            
            // Extract bot response
            $botResponse = $data['candidates'][0]['content']['parts'][0]['text'];

            // Save to database
            ChatHistory::create([
                'nis' => $nis,
                'session_id' => $sessionId,
                'message' => $userMessage,
                'response' => $botResponse
            ]);

            return response()->json([
                'success' => true,
                'response' => $botResponse
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'response' => 'Pesan tidak valid. ' . $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Chatbot Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'response' => 'Maaf, terjadi kesalahan sistem. Silakan coba lagi nanti.'
            ], 500);
        }
    }

    public function getHistory(Request $request)
    {
        try {
            $request->validate([
                'session_id' => 'required|string'
            ]);

            $sessionId = $request->session_id;
            $nis = Auth::guard('siswa')->user()->nis;

            $history = ChatHistory::where('nis', $nis)
                ->where('session_id', $sessionId)
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function($chat) {
                    return [
                        [
                            'type' => 'user',
                            'message' => $chat->message,
                            'time' => $chat->created_at->format('H:i')
                        ],
                        [
                            'type' => 'bot',
                            'message' => $chat->response,
                            'time' => $chat->created_at->format('H:i')
                        ]
                    ];
                })
                ->flatten(1);

            return response()->json([
                'success' => true,
                'history' => $history
            ]);

        } catch (\Exception $e) {
            Log::error('Get History Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'history' => []
            ], 500);
        }
    }

    public function clearHistory(Request $request)
    {
        try {
            $request->validate([
                'session_id' => 'required|string'
            ]);

            $sessionId = $request->session_id;
            $nis = Auth::guard('siswa')->user()->nis;

            ChatHistory::where('nis', $nis)
                ->where('session_id', $sessionId)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Riwayat chat berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            Log::error('Clear History Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus riwayat chat'
            ], 500);
        }
    }
}