<?php

namespace App\Http\Controllers\Admin;

use App\Models\SOS;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class AdminIncidentReport extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentYear = Carbon::now()->year;
        $monthlySOSCounts = SOS::selectRaw("
            MONTH(created_at) as month,
            SUM(CASE WHEN type = 'fire' THEN 1 ELSE 0 END) as fire_count,
            SUM(CASE WHEN type = 'flood' THEN 1 ELSE 0 END) as flood_count
            ")
                ->whereYear('created_at', $currentYear)
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->keyBy('month');

            $monthlyData = [];
            for ($month = 1; $month <= 12; $month++) {
                $monthlyData[$month] = [
                    'fire_count' => $monthlySOSCounts->get($month)->fire_count ?? 0,
                    'flood_count' => $monthlySOSCounts->get($month)->flood_count ?? 0,
                ];
            }


        $users  = User::count();
        $sosFire = SOS::where('type', 'fire')->count();
        $sosFood = SOS::where('type', 'flood')->count();
        $total = SOS::count();
        return view('admin.incident-report', [
            'monthlySOSCounts'  => $monthlyData,
            'users' => $users,
            'sosFire' => $sosFire,
            'sosFood' => $sosFood,
            'total' => $total,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
