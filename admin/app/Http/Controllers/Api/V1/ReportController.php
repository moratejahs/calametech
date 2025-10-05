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
        $apiKey = env('AI_API_KEY');
        \Log::info('generateAiTipFromDescription - apiKey present', ['hasKey' => !empty($apiKey)]);

        if (empty($apiKey)) {
            return $this->fallbackAiTip((string) $description);
        }

        $systemPrompt = 'You are a safety assistant. Respond with one practical safety tip under 100 words. No JSON, no formatting.';
        $userPrompt = "Generate a 100-word safety tip based on this emergency description:
        \"\"\"{$description}\"\"\"

        Tip:";

        $response = Http::withToken($apiKey)
            ->timeout(20)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
                'max_tokens' => 250, // enough for ~100 words
                'temperature' => 0.4,
            ]);

        if ($response->failed()) {
            \Log::warning('OpenAI request failed', [
                'status' => $response->status(),
                'body_summary' => substr($response->body(), 0, 1000),
            ]);
            return $this->fallbackAiTip($description);
        }

        $data = $response->json();
        $content = data_get($data, 'choices.0.message.content', null);

        if (empty($content)) {
            return $this->fallbackAiTip($description);
        }

        return trim($content);
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
