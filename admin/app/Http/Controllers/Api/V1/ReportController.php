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
    public function index()
    {
        $incidents  = SOS::all();
        return response()->json([
            'message' => 'AI Tips retrieved successfully.',
            'data' => [
                'incidents' => $incidents,
            ],
        ], 200);
    }
    public function store(ReportRequest $request)
    {
        $validated = $request->validated();

        try {
            if (isset($validated['image'])) {
                $filePath = Storage::disk('public')->put('sos_images', $validated['image']);
            }

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
