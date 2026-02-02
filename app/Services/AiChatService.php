<?php

namespace App\Services;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AiChatService
{
    protected string $provider;
    protected string $model;
    protected string $apiKey;
    protected int $maxTokens;
    protected float $temperature;
    protected string $systemPrompt;
    protected array $handoverKeywords;

    public function __construct()
    {
        $this->provider = config('chat.ai.provider', 'openai');
        $this->model = config('chat.ai.model', 'gpt-4');
        
        // Get API key based on provider
        $this->apiKey = $this->provider === 'gemini' 
            ? config('chat.ai.gemini_api_key', '')
            : config('chat.ai.openai_api_key', '');
            
        $this->maxTokens = config('chat.ai.max_tokens', 1000);
        $this->temperature = config('chat.ai.temperature', 0.7);
        $this->systemPrompt = config('chat.ai.system_prompt', '');
        $this->handoverKeywords = config('chat.ai.handover_keywords', []);
    }

    /**
     * Check if AI chat is enabled
     */
    public function isEnabled(): bool
    {
        return config('chat.ai.enabled', true) && !empty($this->apiKey);
    }

    /**
     * Generate AI response for a message
     */
    public function generateResponse(ChatSession $session, string $userMessage): ?string
    {
        if (!$this->isEnabled()) {
            return config('chat.ai.fallback_message');
        }

        // Check for handover keywords
        if ($this->shouldHandoverToOperator($userMessage)) {
            return $this->getHandoverMessage();
        }

        try {
            // Build context with conversation history and knowledge base
            $context = $this->buildContext($session, $userMessage);
            
            // Call AI provider
            $response = match ($this->provider) {
                'openai' => $this->callOpenAI($context),
                'gemini' => $this->callGemini($context),
                default => $this->callOpenAI($context),
            };

            return $response;
        } catch (\Exception $e) {
            Log::error('AI Chat Error: ' . $e->getMessage());
            return config('chat.ai.fallback_message');
        }
    }

    /**
     * Check if message contains handover keywords
     */
    public function shouldHandoverToOperator(string $message): bool
    {
        $message = strtolower($message);
        
        foreach ($this->handoverKeywords as $keyword) {
            if (str_contains($message, strtolower($keyword))) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get handover message
     */
    protected function getHandoverMessage(): string
    {
        return "Baik, saya akan menghubungkan Anda dengan customer service kami. Mohon tunggu sebentar, operator kami akan segera merespons.";
    }

    /**
     * Build context for AI with conversation history and knowledge
     */
    protected function buildContext(ChatSession $session, string $currentMessage): array
    {
        $messages = [];

        // System prompt with knowledge base
        $knowledgeBase = $this->getKnowledgeBase();
        $systemMessage = $this->systemPrompt . "\n\n" . $knowledgeBase;
        
        $messages[] = [
            'role' => 'system',
            'content' => $systemMessage,
        ];

        // Add conversation history (last 10 messages)
        $history = $session->messages()
            ->whereIn('sender_type', ['visitor', 'ai', 'operator'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->reverse();

        foreach ($history as $msg) {
            $role = $msg->sender_type === 'visitor' ? 'user' : 'assistant';
            $messages[] = [
                'role' => $role,
                'content' => $msg->message,
            ];
        }

        // Add current message
        $messages[] = [
            'role' => 'user',
            'content' => $currentMessage,
        ];

        return $messages;
    }

    /**
     * Get knowledge base for AI context
     */
    protected function getKnowledgeBase(): string
    {
        return Cache::remember('ai_knowledge_base', 3600, function () {
            $knowledge = [];

            // Company info
            $knowledge[] = "=== INFORMASI PERUSAHAAN ===";
            $knowledge[] = "Nama: PT. Berkah Mandiri Globalindo";
            $knowledge[] = "Bidang: Distributor dan Supplier Besi untuk Industri, Manufaktur, dan Konstruksi";
            $knowledge[] = "Alamat: " . (setting('company_address') ?? 'Jakarta, Indonesia');
            $knowledge[] = "Telepon: " . (setting('company_phone') ?? '-');
            $knowledge[] = "Email: " . (setting('company_email') ?? 'info@berkahmandiriglobalindo.com');
            $knowledge[] = "WhatsApp: " . (setting('whatsapp_number') ?? '-');
            $knowledge[] = "";

            // Working hours
            $knowledge[] = "=== JAM OPERASIONAL ===";
            $knowledge[] = "Senin - Jumat: 08:00 - 17:00 WIB";
            $knowledge[] = "Sabtu: 08:00 - 12:00 WIB";
            $knowledge[] = "Minggu: Tutup";
            $knowledge[] = "";

            // Product categories
            $knowledge[] = "=== KATEGORI PRODUK ===";
            $categories = Category::whereNull('parent_id')
                ->where('is_active', true)
                ->with('children')
                ->get();

            foreach ($categories as $category) {
                $name = $category->translations->where('locale', 'id')->first()?->name ?? $category->slug;
                $knowledge[] = "- {$name}";
                
                foreach ($category->children as $child) {
                    $childName = $child->translations->where('locale', 'id')->first()?->name ?? $child->slug;
                    $knowledge[] = "  - {$childName}";
                }
            }
            $knowledge[] = "";

            // Featured products
            $knowledge[] = "=== PRODUK UNGGULAN ===";
            $products = Product::where('is_featured', true)
                ->where('is_active', true)
                ->with(['translations', 'category.translations'])
                ->take(10)
                ->get();

            foreach ($products as $product) {
                $name = $product->translations->where('locale', 'id')->first()?->name ?? $product->slug;
                $categoryName = $product->category?->translations->where('locale', 'id')->first()?->name ?? '-';
                $description = $product->translations->where('locale', 'id')->first()?->short_description ?? '';
                $knowledge[] = "- {$name} (Kategori: {$categoryName})";
                if ($description) {
                    $knowledge[] = "  Deskripsi: " . \Str::limit($description, 100);
                }
            }
            $knowledge[] = "";

            // FAQs
            $knowledge[] = "=== FAQ ===";
            $knowledge[] = "Q: Bagaimana cara memesan produk?";
            $knowledge[] = "A: Anda bisa memesan melalui website kami, WhatsApp, atau menghubungi tim sales langsung.";
            $knowledge[] = "";
            $knowledge[] = "Q: Apakah tersedia pengiriman ke luar kota?";
            $knowledge[] = "A: Ya, kami melayani pengiriman ke seluruh Indonesia.";
            $knowledge[] = "";
            $knowledge[] = "Q: Berapa minimum order?";
            $knowledge[] = "A: Minimum order bervariasi tergantung jenis produk. Silakan hubungi tim sales untuk informasi lebih lanjut.";
            $knowledge[] = "";
            $knowledge[] = "Q: Apakah bisa request penawaran harga?";
            $knowledge[] = "A: Ya, silakan isi form penawaran di halaman produk atau hubungi tim sales kami.";

            return implode("\n", $knowledge);
        });
    }

    /**
     * Call OpenAI API
     */
    protected function callOpenAI(array $messages): ?string
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
            'model' => $this->model,
            'messages' => $messages,
            'max_tokens' => $this->maxTokens,
            'temperature' => $this->temperature,
        ]);

        if ($response->successful()) {
            return $response->json('choices.0.message.content');
        }

        Log::error('OpenAI API Error: ' . $response->body());
        return null;
    }

    /**
     * Call Google Gemini API
     */
    protected function callGemini(array $messages): ?string
    {
        // Convert messages format for Gemini
        $contents = [];
        $systemInstruction = '';
        
        foreach ($messages as $msg) {
            if ($msg['role'] === 'system') {
                $systemInstruction = $msg['content'];
                continue;
            }
            
            $contents[] = [
                'role' => $msg['role'] === 'assistant' ? 'model' : 'user',
                'parts' => [['text' => $msg['content']]],
            ];
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->timeout(30)->post("https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}", [
            'system_instruction' => [
                'parts' => [['text' => $systemInstruction]],
            ],
            'contents' => $contents,
            'generationConfig' => [
                'maxOutputTokens' => $this->maxTokens,
                'temperature' => $this->temperature,
            ],
        ]);

        if ($response->successful()) {
            return $response->json('candidates.0.content.parts.0.text');
        }

        Log::error('Gemini API Error: ' . $response->body());
        return null;
    }

    /**
     * Clear knowledge base cache
     */
    public function clearKnowledgeCache(): void
    {
        Cache::forget('ai_knowledge_base');
    }
}
