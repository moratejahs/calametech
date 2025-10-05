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
    public function store(ReportRequest $request)
    {
        $validated = $request->validated();

        try {
            // Debug: log the incoming description so we can trace ai tip generation
            \Log::info('ReportController.store - description', ['description' => $validated['description'] ?? null]);
            if (isset($validated['image'])) {
                $filePath = Storage::disk('public')->put('sos_images', $validated['image']);
            }
            $aiTip = $this->generateAiTipFromDescription($validated['description']);
            \Log::info('ReportController.store - computed aiTip', ['aiTip' => $aiTip]);
            $sos = SOS::create([
                'description' => $validated['description'],
                'ai_tips' => $aiTip,
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

    protected function generateAiTipFromDescription(?string $description): ?string
    {
        try {
            // Read API key from environment or services config. Do NOT hardcode keys in source.
            $apiKey = env('AI_API_KEY');
            \Log::info('generateAiTipFromDescription - apiKey present', ['hasKey' => !empty($apiKey)]);

            // If no API key is present, return a deterministic fallback tip immediately
            if (empty($apiKey)) {
                \Log::info('AI API key missing; using fallback tip generator');
                return $this->fallbackAiTip((string) $description);
            }

            // Updated system + user prompts for strict 100-character tips
            $systemPrompt = 'You are a concise safety assistant. Respond only with one short safety tip under 100 characters. No JSON, no explanation.';
            $userPrompt = "Generate a short safety tip based on the following description.
            The tip must be 100 characters or fewer, simple, and specific.

            Description:
            \"\"\"{$description}\"\"\"

            Tip:";

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
                ]);

            if ($response->failed()) {
                \Log::warning('OpenAI request failed', [
                    'status' => $response->status(),
                    // truncate body to avoid huge logs, but include message/code if present
                    'body_summary' => substr($response->body(), 0, 1000),
                ]);

                // Fall through to fallback tip generator
                return $this->fallbackAiTip($description);
            }

            $data = $response->json();
            $content = data_get($data, 'choices.0.message.content', null);

            if (empty($content)) {
                \Log::warning('OpenAI returned empty content for ai_tip', ['response_summary' => array_slice($data,0,3)]);
                return $this->fallbackAiTip($description);
            }

            $content = trim($content, "\"'\n ");

            // Strictly limit to 100 chars, cut cleanly at a space if possible
            if (mb_strlen($content) > 100) {
                $truncated = mb_substr($content, 0, 100);
                $lastSpace = mb_strrpos($truncated, ' ');
                if ($lastSpace !== false) {
                    $truncated = mb_substr($truncated, 0, $lastSpace);
                }
                $content = rtrim($truncated, ' ,.;:');
            }

            \Log::info('generateAiTipFromDescription - returning content', ['len' => mb_strlen($content)]);
            return $content;
        } catch (\Exception $e) {
            \Log::error('OpenAI error generating ai_tip: '.$e->getMessage());
            return $this->fallbackAiTip($description);
        }
    }

    /**
     * Deterministic fallback tip generator when AI is unavailable.
     * Keeps output under 100 characters and returns a concise safety tip.
     */
    protected function fallbackAiTip(string $description): ?string
    {
        $d = strtolower($description);
        // common heuristics
        if (str_contains($d, 'fire') || str_contains($d, 'flame') || str_contains($d, 'smoke')) {
            return 'Evacuate to a safe area; alert others and call emergency services.'; // 62 chars
        }
        if (str_contains($d, 'flood') || str_contains($d, 'water') || str_contains($d, 'inund')) {
            return 'Move to higher ground immediately and avoid floodwaters.'; // 54 chars
        }
        if (str_contains($d, 'gas') || str_contains($d, 'leak') || str_contains($d, 'chemical')) {
            return 'Leave the area, avoid inhaling fumes, and call emergency responders.'; // 66 chars
        }
        if (str_contains($d, 'injury') || str_contains($d, 'bleed') || str_contains($d, 'hurt')) {
            return 'Apply pressure to bleeding, keep the person still, and seek medical help.'; // 70 chars
        }

        // Generic fallback
        $short = trim(preg_replace('/\s+/', ' ', strip_tags($description)));
        if (empty($short)) return null;
        // return a truncated generic tip
        $tip = 'Stay safe: move away from immediate danger and call for help if needed.';
        return mb_substr($tip, 0, 100);
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
