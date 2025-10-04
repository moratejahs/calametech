<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class AiTipsController extends Controller
{
    // Fetch real-time weather-based safety tips using Open-Meteo
    public function getTips(Request $request)
    {
        $lat = $request->input('lat', 9.078408);
        $lon = $request->input('lon', 126.199289);
        $response = Http::get('https://api.open-meteo.com/v1/forecast', [
            'latitude' => $lat,
            'longitude' => $lon,
            'current' => 'temperature_2m,weather_code,wind_speed_10m,precipitation',
            'timezone' => 'auto',
        ]);
        $data = $response->json();
        $tips = [];
        if (isset($data['current'])) {
            $temp = $data['current']['temperature_2m'] ?? null;
            $wind = $data['current']['wind_speed_10m'] ?? null;
            $precip = $data['current']['precipitation'] ?? null;
            $wcode = $data['current']['weather_code'] ?? null;
            if ($temp !== null && $temp > 32) {
                $tips[] = 'High temperature detected. Stay hydrated and avoid outdoor activities during peak heat.';
            }
            if ($wind !== null && $wind > 10) {
                $tips[] = 'Strong winds detected. Secure loose objects and avoid open areas.';
            }
            if ($precip !== null && $precip > 5) {
                $tips[] = 'Heavy rain detected. Watch out for possible flooding in low-lying areas.';
            }
            if (in_array($wcode, [80,81,82,95,96,99])) {
                $tips[] = 'Thunderstorm risk detected. Stay indoors and avoid using electrical appliances.';
            }
        }
        if (empty($tips)) {
            $tips[] = 'Weather is normal. Stay alert and follow local advisories.';
        }
        return response()->json(['tips' => $tips]);
    }
}
