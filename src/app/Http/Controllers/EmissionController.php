<?php

namespace App\Http\Controllers;

use App\Models\CetesbEmission;
use Illuminate\Http\Request;

class EmissionController extends Controller
{
    public function getHourlyAverage(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date'   => 'required|date',
        ]);

        $data = CetesbEmission::selectRaw('
                EXTRACT(HOUR FROM created_at) as hour,
                ROUND(AVG(mp10), 2) as avg_mp10,
                ROUND(AVG(mp25), 2) as avg_mp25,
                ROUND(AVG(co), 2) as avg_co,
                ROUND(AVG(nox), 2) as avg_nox
            ')
            ->whereDate('created_at', '>=', $validated['start_date'])
            ->whereDate('created_at', '<=', $validated['end_date'])
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return response()->json($data);
    }

    public function getWeeklyAverage(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date'   => 'required|date',
        ]);

        $data = CetesbEmission::selectRaw('
                DAYOFWEEK(created_at) as day_idx, 
                ROUND(AVG(mp10), 2) as avg_mp10,
                ROUND(AVG(mp25), 2) as avg_mp25,
                ROUND(AVG(co), 2) as avg_co,
                ROUND(AVG(nox), 2) as avg_nox
            ')
            ->whereDate('created_at', '>=', $validated['start_date'])
            ->whereDate('created_at', '<=', $validated['end_date'])
            ->groupBy('day_idx')
            ->orderBy('day_idx')
            ->get();

        $daysMap = [
            1 => 'Dom',
            2 => 'Seg',
            3 => 'Ter',
            4 => 'Qua',
            5 => 'Qui',
            6 => 'Sex',
            7 => 'Sáb',
        ];

        $formattedData = $data->map(function ($item) use ($daysMap) {
            $item->day = $daysMap[$item->day_idx] ?? 'N/A';
            return $item;
        });

        return response()->json($formattedData);
    }

    public function getMonthlyAverage(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date'   => 'required|date',
        ]);

        // 1. Busca os dados agrupados pelo mês (1 = Janeiro, 12 = Dezembro)
        $data = CetesbEmission::selectRaw('
                MONTH(created_at) as month_idx,
                ROUND(AVG(mp10), 2) as avg_mp10,
                ROUND(AVG(mp25), 2) as avg_mp25,
                ROUND(AVG(co), 2) as avg_co,
                ROUND(AVG(nox), 2) as avg_nox
            ')
            ->whereDate('created_at', '>=', $validated['start_date'])
            ->whereDate('created_at', '<=', $validated['end_date'])
            ->groupBy('month_idx')
            ->orderBy('month_idx')
            ->get();

        // 2. Mapa para converter número em nome do mês
        $monthsMap = [
            1 => 'Jan',
            2 => 'Fev',
            3 => 'Mar',
            4 => 'Abr',
            5 => 'Mai',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Ago',
            9 => 'Set',
            10 => 'Out',
            11 => 'Nov',
            12 => 'Dez',
        ];
        
        $formattedData = $data->map(function ($item) use ($monthsMap) {
            $item->month = $monthsMap[$item->month_idx] ?? 'N/A';
            return $item;
        });

        return response()->json($formattedData);
    }
}