<?php

namespace App\Http\Controllers;

use App\Models\Insurance;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class InsuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $insurance = Insurance::with("user", "car")->get();
        if(!$insurance){
            return response()->json(
                ['data'=>null
                    ,'message'=> 'The insurance is empty or not found'
                ],
                401);
        }
        return response()->json([
            'data'=> $insurance,
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
            'car_id' =>'required|numeric|exists:cars,id',
            'typeinsurance'=>'required|string|max:50',
            'startdate' => 'required|date',
            'enddate' => 'required|date|after_or_equal:startdate',
            'status'=>'required|string|max:50',
            'insuranceprice' => 'nullable|numeric|min:5000',
        ],[
            'user_id.exists' => 'The specific user does\'t exist',
            'insuranceprice.min' => 'Insurance price must be superior 5000',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'Error',
                'Message' => $validate->errors()
            ], 422);
        }

        try {
            $insurances = Insurance::create([
                'user_id' =>$request->user_id,
                'car_id' =>$request->car_id,
                'typeinsurance'=>$request->typeinsurance,
                'startdate' => $request->startdate,
                'enddate' => $request->enddate,
                'status'=> $request->status,
                'insuranceprice' => $request->insuranceprice,
            ]);
            return response()->json([
                'data' => $insurances,
                'message'=> 'A insurance is added successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'Error' => $e->getMessage(),
                    'Message' => 'Error while inserting insurance',
                ], 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $insurances = Insurance::find($id);

        if(!$insurances){
            return response()->json([
                'Message' => 'Sorry, the insurance is not found'
            ], 401);
        }
        return response()->json([
            'data' => $insurances,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Insurance $insurance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $insurance = Insurance::find($id);
        if(!$insurance){
            return response()->json(['Message' => 'Sorry, the specific insurance is not found'], 404);
        }
        $validate = Validator::make($request->all(), [
            'user_id' =>'required|numeric|exists:users,id',
            'car_id' =>'required|numeric|exists:cars,id',
            'typeinsurance'=>'nullable|string|max:50',
            'startdate' => 'nullable|date',
            'enddate' => 'nullable|date|after_or_equal:startdate',
            'status'=>'nullable|string|max:50',
            'insuranceprice' => 'nullable|numeric|min:5000',
        ],[
            'user_id.exists' => 'The specific user does\'t exist',
            'car_id.exists' => 'The specific car does\'t exist',
            'insuranceprice.min' => 'Insurance price must be superior 5000',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'Error',
                'Message' => $validate->errors()
            ], 422);
        }

        try {
            $valideData = $validate->validated();

            $insurance->update($valideData);

            return response()->json([
                'data' => $insurance,
                'Message'=> 'The existing insurance is updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'Error' => $e->getMessage(),
                'Message' => 'Error while updating insurance'
            ], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $insurance = Insurance::find($id);
        if(!$insurance){
            return response()->json(['Message' => 'Sorry, the specific insurance is not found'], 404);
        }
        try {

            $insurance->delete();

            return response()->json([
                'success' => true,
                'message' => 'The insurance has been deleted successfully'
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error while deleting the insurance',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
