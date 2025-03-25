<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Insurance;
use App\Models\Intervention;
use App\Models\User;

class StatController extends Controller
{
    //
    public function totalUser(){
        $users = User::where('role', 'user')->count();
        $cars = Car::count();
        $interventions = Intervention::count();
        $insurances = Insurance::count();

        return response()->json(compact('users', 'cars', 'interventions', 'insurances'));
    }
    public function interventionByMonth(){
        $interventions = Intervention::selectRaw("TO_CHAR(datedemand, 'YYYY-MM') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return response()->json($interventions);
    }
    public function insurancesByMonth(){
        $insurances = Insurance::selectRaw("TO_CHAR(created_at, 'YYYY-MM') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return response()->json($insurances);
    }
}
