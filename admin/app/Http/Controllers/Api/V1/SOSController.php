<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Incident;
use App\Http\Requests\SOSRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Models\SOS;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SOSController extends Controller
{
    public function __invoke(SOSRequest $request)
    {
        $validated = $request->validated();

        $sos = SOS::updateOrCreate([
            'status' => 'pending',
            'user_id' => Auth::id(),
        ],[
            'lat' => $validated['lat'],
            'long' => $validated['long'],
        ]);

        return response()->json([
            'sos' => $sos->only(['id', 'lat', 'long', 'status']),
            'success' => 'SOS sent to CDRRMO.',
        ], 201);
    }
}
