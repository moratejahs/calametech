<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\SOS;
use App\Http\Requests\SOSRequest;
use App\Http\Resources\SOSResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SOSController extends Controller
{
    public function index()
    {
        $sosReports = SOS::query()
            ->with('user')
            ->where('status', 'resolved')
            ->latest()
            ->get();

        return response()->json(SOSResource::collection($sosReports));
    }

    public function indexFeatured()
    {
        $sosReports = SOS::query()
            ->with('user')
            ->where('status', 'resolved')
            ->latest()
            ->limit(5)
            ->get();

        return response()->json(SOSResource::collection($sosReports));
    }

    public function indexReco()
    {
        $sosReports = SOS::query()
            ->with('user')
            ->where('status', 'resolved')
            ->inRandomOrder()
            ->limit(10)
            ->get();

        return response()->json(SOSResource::collection($sosReports));
    }

    public function store(SOSRequest $request)
    {
        $validated = $request->validated();

        $sos = SOS::updateOrCreate([
            'status' => 'pending',
            'user_id' => Auth::id(),
        ], [
            'lat' => $validated['lat'],
            'long' => $validated['long'],
        ]);

        return response()->json([
            'sos' => $sos->only(['id', 'lat', 'long', 'status']),
            'success' => 'SOS sent to CDRRMO.',
        ], 201);
    }
}
