<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InterventionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $interventions = Intervention::with("user", "car")->get();
        if(!$interventions){
            return response()->json([
                'success' => false,
                'message' => 'The specific interventions have not been found',
            ],404);
        }
        return response()->json([
            'data' => $interventions,
            'Message'=>'loading'
        ],200);
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
        $validate = Validator::make($request->all(),[
           'user_id'=>'required|numeric|exists:users,id',
           'car_id'=>'required|numeric|exists:cars,id',
           'typeintervention'=>'required|string|max:40',
           'datedemand'=>'required|date',
           'status'=>'required|string|max:40',
            'description'=>'nullable|string',
            'localisation' => 'nullable|string',
        ]);
        if($validate->fails()){
            return response()->json([
                'success' => false,
                'message' => $validate->errors(),
            ],401);
        }
        try {
            $intervention = Intervention::create([
                'user_id' =>$request->user_id,
                'car_id'=>$request->car_id,
                'typeintervention' => $request->typeintervention,
                'datedemand' => $request->datedemand,
                'status' => $request->status,
                'description' => $request->description,
                'localisation' => $request->localisation,
            ]);
            return response()->json([
                'data' => $intervention,
                'message'=> 'Intervention is added successfully'
            ], 200);
        }catch (\Exception $e){
            return response()->json([
               'success' => false,
               'message' => $e->getMessage(),
            ],401);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $intervention = Intervention::find($id);
        if(!$intervention){
            return response()->json([
                'success' => false,
                'message' => 'The specific interventions have not been found',
            ]);
        }
        return response()->json([
           'data' => $intervention,
           'Message'=>'success'
        ],200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Intervention $intervention)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $intervention = Intervention::find($id);
        if(!$intervention){
            return response()->json([
                'success' => false,
                'message' => 'The specific interventions have not been found',
            ],401);
        }
        $validate = Validator::make($request->all(),[
            'user_id'=>'required|numeric|exists:users,id',
            'car_id'=>'required|numeric|exists:cars,id',
//            'typeintervention'=>'required|string|max:40',
//            'datedemand'=>'required|date',
            'status'=>'required|string|max:40',
            'description'=>'nullable|string',
        ],[
            'user_id.exists' => 'The selected user does not exist',
            'car_id.exists' => 'The selected cars does not exist',
        ]);
        if($validate->fails()){
            return response()->json([
                'success' => false,
                'message' => $validate->errors(),
            ],401);
        }
        try {
            $validateData = $validate->validated();
            $intervention->update($validateData);
            return response()->json([
                'data' => $intervention,
                'message'=> 'Intervention is updated successfully'
            ],200);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ],401);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $intervention = Intervention::find($id);
        if(!$intervention){
            return response()->json([
                'success' => false,
                'message' => 'The specific interventions have not been found',
            ],401);
        }
        try {
            $intervention->delete();
            return response()->json([
                'success' => true,
                'message' => 'Intervention is deleted successfully'
            ],200);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ],401);
        }
    }
    public function localisationProgress()
    {
        $interventions = Intervention::where('status', 'in progress')->get();
        if(!$interventions){
            return response()->json([
                'success' => false,
            ]);
        }
        return response()->json([
            'data' => $interventions,
            'Message'=>'loading'
        ], 200);
    }

}
