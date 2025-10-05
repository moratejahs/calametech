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
            if (isset($validated['image'])) {
                $filePath = Storage::disk('public')->put('sos_images', $validated['image']);
            }
            $aiTip = $this->generateAiTipFromDescription($validated['description']);
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

    protected function generateAiTipFromDescription(string $description): ?string
    {
        try {
            // Read API key from environment or services config. Do NOT hardcode keys in source.
            $apiKey = env('AI_API_KEY');
            if (empty($apiKey)) {
                // Key not available; skipping AI tip generation
                return null;
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
                    'body' => $response->body(),
                ]);

                return null;
            }

            $data = $response->json();
            $content = data_get($data, 'choices.0.message.content', null);

            if (empty($content)) {
                return null;
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

            return $content;
        } catch (\Exception $e) {
            \Log::error('OpenAI error generating ai_tip: '.$e->getMessage());

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
