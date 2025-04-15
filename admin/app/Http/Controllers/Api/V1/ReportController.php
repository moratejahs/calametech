<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Models\SOS;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function __invoke(ReportRequest $request)
    {
        $validated = $request->validated();

        try {
            $filePath = Storage::disk('public')->put('sos_images', $validated['image']);

            $sos = SOS::findOrFail($validated['sos_id']);

            if ($sos->image_path && Storage::disk('public')->exists($sos->image_path)) {
                Storage::disk('public')->delete($sos->image_path);
            }

            $sos->update([
                'description' => $validated['description'],
                'image_path' => $filePath,
                'type' => $validated['type'],
            ]);

            return response()->json([
                'success' => 'Report submitted successfully.'
            ], 201);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());

            return response()->json([
                'error' => 'Failed to submit report.'
            ], 500);
        }
    }
}
