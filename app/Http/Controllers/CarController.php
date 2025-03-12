<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = Car::all();
        if(!$cars){
            return response()->json(
                ['data'=>null
                    ,'message'=> 'The car is empty or not found'
                ],
                401);
        }
        return response()->json([
            'data'=> $cars,
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
            'user_id' =>'required|numeric|exists:users,id',
            'carname'=>'required|string|max:50',
            'immatriculation' => 'required|string|max:30',
            'marque' => 'required|string|max:30',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'Error',
                'Message' => $validate->errors()
            ], 422);
        }

        try {
            $cars = Car::create([
                'user_id' =>$request->user_id,
                'carname'=>$request->carname,
                'immatriculation' => $request->immatriculation,
                'marque' => $request->marque,
            ]);
            return response()->json([
                'data' => $cars,
                'message'=> 'A car is added successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'Error' => $e->getMessage(),
                    'Message' => 'Error while inserting car',
                ], 401);
        }
    }

    public function show($id)
    {
        $cars = Car::find($id);

        if(!$cars){
            return response()->json([
                'Message' => 'Sorry, the car is not found'
            ], 401);
        }
        return response()->json([
            'data' => $cars,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $car = Car::find($id);
        if(!$car){
            return response()->json(['Message' => 'Sorry, the specific car is not found'], 404);
        }
        $validate = Validator::make($request->all(), [
            'user_id' =>'required|numeric|exists:users,id',
            'carname'=>'required|string|max:50',
            'immatriculation' => 'required|string|max:30',
            'marque' => 'required|string|max:30',
        ],[
            'user_id.exists' => 'Sorry, the user doesn\'t exist'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'Error',
                'Message' => $validate->errors()
            ], 422);
        }

        try {

            $valideData = $validate->validated();
            $car->update($valideData);

            return response()->json([
                'data' => $car,
                'Message'=> 'The existing car is updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'Error' => $e->getMessage(),
                'Message' => 'Error while updating car'
            ], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $car = Car::find($id);
        if(!$car){
            return response()->json(['Message' => 'Sorry, the specific car is not found'], 404);
        }
        try {

            $car->delete();

            return response()->json([
                'success' => true,
                'message' => 'The car has been deleted successfully'
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error while deleting the car',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
