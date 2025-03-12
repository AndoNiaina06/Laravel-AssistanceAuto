<?php

namespace App\Http\Controllers;

use App\Models\Maintenance_service;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Validator;

class MaintenanceServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $maintenance_service = Maintenance_service::all();
        if(!$maintenance_service){
            return response()->json(
                ['data'=>null
                    ,'message'=> 'The service is empty or not found'
                ],
                401);
        }
        return response()->json([
            'data'=> $maintenance_service,
            'message' => 'loading'
        ],
            200);
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
        $validate = Validator::make($request->all(), [
            'car_id' =>'required|numeric|exists:cars,id',
            'typeservice'=>'required|string|max:50',
            'dateservice' => 'required|date',
            'cout' => 'required|numeric|min:5000',
            'society' => 'nullable|string',
        ],[
            'car_id.exists' => 'The specific car does\'t exist',
            'cout.min' => 'Service price must be superior 5000',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'Error',
                'Message' => $validate->errors()
            ], 422);
        }

        try {
            $maintenance_service = Maintenance_service::create([
                'car_id' =>$request->car_id,
                'typeservice'=>$request->typeservice,
                'dateservice' => $request->dateservice,
                'cout' => $request->cout,
                'society' => $request->society,
            ]);
            return response()->json([
                'data' => $maintenance_service,
                'message'=> 'A Service is added successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'Error' => $e->getMessage(),
                    'Message' => 'Error while inserting Service',
                ], 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $maintenance_service = Maintenance_service::find($id);

        if(!$maintenance_service){
            return response()->json([
                'Message' => 'Sorry, the service is not found'
            ], 401);
        }
        return response()->json([
            'data' => $maintenance_service,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Maintenance_service $maintenance_service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $maintenance_service = Maintenance_service::find($id);
        if(!$maintenance_service){
            return response()->json(['Message' => 'Sorry, the specific service is not found'], 404);
        }
        $validate = Validator::make($request->all(), [
            'car_id' =>'required|numeric|exists:cars,id',
            'typeservice'=>'required|string|max:50',
            'dateservice' => 'required|date',
            'cout' => 'required|numeric|min:5000',
            'society' => 'nullable|string',
        ],[
            'car_id.exists' => 'The specific car does\'t exist',
            'cout.min' => 'Service price must be superior 5000',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'Error',
                'Message' => $validate->errors()
            ], 422);
        }

        try {
            $valideData = $validate->validated();

            $maintenance_service->update($valideData);

            return response()->json([
                'data' => $maintenance_service,
                'Message'=> 'The existing service is updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'Error' => $e->getMessage(),
                'Message' => 'Error while updating service'
            ], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maintenance_service $maintenance_service)
    {
        $maintenance_service = Maintenance_service::find($id);
        if(!$maintenance_service){
            return response()->json(['Message' => 'Sorry, the specific service is not found'], 404);
        }
        try {

            $maintenance_service->delete();

            return response()->json([
                'success' => true,
                'message' => 'The service has been deleted successfully'
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error while deleting the service',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
