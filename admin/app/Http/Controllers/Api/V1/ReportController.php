<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ReportRequest;
use App\Models\SOS;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index() {
        $tips = SOS::all();
        return response()->json([
            'data' => $tips
        ]);
    }
    public function store(ReportRequest $request)
    {
        $validated = $request->validated();

        try {
            // Debug: log the incoming description so we can trace ai tip generation
            \Log::info('ReportController.store - description', ['description' => $validated['description'] ?? null]);

            if (isset($validated['image'])) {
                $filePath = Storage::disk('public')->put('sos_images', $validated['image']);
            }

            // Generate AI tip based on description (max 100 chars). If it fails we return null and still create the SOS.
            $aiTip = $this->generateAiTipFromDescription($validated['description']);
            $validated['ai_tips'] = $aiTip;

            $sos = SOS::create([
                'description' => $validated['description'],
                'ai_tips' => $validated['ai_tips'],
                'type' => $validated['type'],
                'image_path' => $filePath ?? null,
                'lat' => $validated['lat'],
                'long' => $validated['long'],
                'status' => 'pending',
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'message' => 'Report submitted to CDRRMO.',
                'data' => [
                    'sos' => $sos,
                ],
            ], 201);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate a concise AI tip (<= 100 chars) based on the provided description.
     * Returns string|null. Will return null if no API key or on failure.
     */
    protected function generateAiTipFromDescription(string $description): ?string
    {
        try {
            // Prefer config('services.openai.key'), fallback to env
            $apiKey = config('services.openai.key') ?? env('AI_API_KEY');

            if (empty($apiKey)) {
                \Log::warning('OpenAI API key not configured; skipping ai tip generation.');
                return null;
            }

            // Build prompts tightly to request plain text tip only
            $systemPrompt = 'You are a concise safety assistant. Respond only with one short safety tip. No JSON, no markdown, no explanations.';
            $userPrompt = "Create a single short safety tip based only on the description below. The tip must be at most 100 characters and specific to the description.\n\nDescription:\n\"\"\"\n{$description}\n\"\"\"\n\nTip:";

            // Call OpenAI Chat Completions
            $response = Http::withToken($apiKey)
                ->timeout(10)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                    'max_tokens' => 60,
                    'temperature' => 0.2,
                    'n' => 1,
                ]);

            if ($response->failed()) {
                \Log::warning('OpenAI request failed', ['status' => $response->status(), 'body' => $response->body()]);
                return null;
            }

            $data = $response->json();

            // Extract content from typical response structure: choices[0].message.content
            $content = data_get($data, 'choices.0.message.content', null);
            if (empty($content)) {
                \Log::warning('OpenAI returned empty content for ai tip', ['response' => $data]);
                return null;
            }

            // Clean and trim returned text
            $content = trim($content);
            // Remove backticks or surrounding quotes often present
            $content = trim($content, "\"'\n `");

            // Remove common code fences if any
            $content = preg_replace('/^```(?:\w+)?\s*/', '', $content);
            $content = preg_replace('/\s*```$/', '', $content);

            // Ensure the text length is <= 100 chars. Truncate at word boundary where possible.
            $maxLen = 100;
            if (mb_strlen($content) > $maxLen) {
                $truncated = mb_substr($content, 0, $maxLen);
                $lastSpace = mb_strrpos($truncated, ' ');
                if ($lastSpace !== false && $lastSpace > 0) {
                    $truncated = mb_substr($truncated, 0, $lastSpace);
                }
                $content = rtrim($truncated, " ,.;:");
            }

            return $content;
        } catch (\Exception $e) {
            \Log::error('OpenAI error generating ai_tip: ' . $e->getMessage());
            return null;
        }
    }


    public function update(ReportRequest $request, string $id)
    {
        $validated = $request->validated();

        try {
            $sos = SOS::find($id);

            if ($sos && $sos->status === 'pending') {
                $sos->update([
                    'description' => $validated['description'],
                    'type' => $validated['type'],
                    'lat' => $validated['lat'],
                    'long' => $validated['long'],
                ]);

                if (isset($validated['image'])) {
                    $filePath = Storage::disk('public')->put('sos_images', $validated['image']);

                    if ($sos->image_path && Storage::disk('public')->exists($sos->image_path)) {
                        Storage::disk('public')->delete($sos->image_path);
                    }
                }

                $sos->update([
                    'image_path' => $filePath ?? null,
                ]);

                return response()->json([
                    'message' => 'Report submitted to CDRRMO.',
                    'data' => [
                        'sos' => $sos,
                    ],
                ], 201);
            } else {
                if (isset($validated['image'])) {
                    $filePath = Storage::disk('public')->put('sos_images', $validated['image']);
                }

                $sos = SOS::create([
                    'description' => $validated['description'],
                    'type' => $validated['type'],
                    'image_path' => $filePath ?? null,
                    'lat' => $validated['lat'],
                    'long' => $validated['long'],
                    'status' => 'pending',
                    'user_id' => Auth::id(),
                ]);

                return response()->json([
                    'message' => 'Report submitted to CDRRMO.',
                    'data' => [
                        'sos' => $sos,
                    ],
                ], 201);
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
