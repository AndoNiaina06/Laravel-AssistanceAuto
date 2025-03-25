<?php

namespace App\Http\Controllers;

use App\Models\Intervention_history;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InterventionHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $Intervention_history = Intervention_history::all();
        if (!$Intervention_history) {
            return response()->json([
                'success' => false,
                'message' => 'No intervention history found',
            ]);
        }
        return response()->json([
            'data' => $Intervention_history,
            'success' => true,
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
        $validate = Validator::make($request->all(), [
            'intervention_id' =>'required|exists:interventions,id',
            'dateintervention' =>'nullable|date',
            'society' =>'nullable|string',
            'details' =>'nullable|string',
        ],[
            'intervention_id.exists' => 'Intervention history not found',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors(),
            ],401);
        }
        try {
            $history = Intervention_history::create([
                'intervention_id' =>$request->intervention_id,
                'dateintervention'=>$request->dateintervention,
                'society'=>$request->society,
                'details'=>$request->details,
            ]);
            return response()->json([
                'data' => $history,
                'message' => 'Intervention history created successfully',
            ],200);
        }catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $history = Intervention_history::find($id);
        if (!$history) {
            return response()->json([
                'success' => false,
                'message' => 'Intervention history not found',
            ]);
        }
        return response()->json([
            'data' => $history,
            'success' => true,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Intervention_history $intervention_history)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $history = Intervention_history::find($id);
        $validate = Validator::make($request->all(), [
            'intervention_id' =>'required|exists:interventions,id',
            'dateintervention' =>'nullable|date',
            'society' =>'nullable|string',
            'details' =>'nullable|string',
        ],[
            'intervention_id.exists' => 'Intervention history not found',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors(),
            ],401);
        }
        try {
            $validateData = $validate->validated();
            $history->update($validateData);
            return response()->json([
                'data' => $history,
                'message' => 'Intervention history updated successfully',
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $history = Intervention_history::find($id);
        if (!$history) {
            return response()->json([
                'success' => false,
                'message' => 'Intervention history not found',
            ],401);
        }
        try {
            $history->delete();
            return response()->json([
                'success' => true,
                'message' => 'Intervention history deleted successfully',
            ],200);
        }catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
