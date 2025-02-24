<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Models\Incident;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function __invoke(ReportRequest $request)
    {
        $validated = $request->validated();

        try {
            \DB::transaction(function () use ($validated) {
                $filePath = Storage::disk('local')->put('/reports/images', $validated['image']);

                Incident::create([
                    'description' => $validated['description'],
                    'image' => $filePath,
                    'status' => $validated['status'],
                    'lat' => $validated['lat'],
                    'long' => $validated['long'],
                    'user_id' => auth()->id(),
                    'barangay_id' => $validated['barangay_id'],
                ]);
            });

            return response()->json([
                'success' => 'Incident submitted successfully.'
            ], 201);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());

            return response()->json([
                'message' => 'Failed to submit incident.'
            ], 500);
        }

    }
}
