<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SOS;
use Carbon\Carbon;

class PredictionController extends Controller
{
    // Return historical monthly counts for the current year and a simple 3-month moving average projection
    public function monthlyProjection(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);

        $monthly = SOS::selectRaw("MONTH(created_at) as month,
                SUM(CASE WHEN type = 'fire' THEN 1 ELSE 0 END) as fire_count,
                SUM(CASE WHEN type = 'flood' THEN 1 ELSE 0 END) as flood_count")
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $fire = array_fill(0, 12, 0);
        $flood = array_fill(0, 12, 0);
        for ($m = 1; $m <= 12; $m++) {
            $fire[$m-1] = $monthly->get($m)->fire_count ?? 0;
            $flood[$m-1] = $monthly->get($m)->flood_count ?? 0;
        }

        // simple 3-month moving average
        $moving = function($arr) {
            $res = [];
            $n = count($arr);
            for ($i = 0; $i < $n; $i++) {
                $start = max(0, $i-2);
                $slice = array_slice($arr, $start, $i - $start + 1);
                $res[] = round(array_sum($slice)/count($slice),2);
            }
            return $res;
        };

        $fireTrend = $moving($fire);
        $floodTrend = $moving($flood);

        return response()->json([
            'year' => $year,
            'fire' => $fire,
            'flood' => $flood,
            'fire_trend' => $fireTrend,
            'flood_trend' => $floodTrend,
        ]);
    }
}
