<?php

namespace App\Http\Controllers;

use App\schedules;
use Illuminate\support\Facades\Validator;
use Illuminate\Http\Request;

class schedulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    { 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $all = $req->only(["type", "line", "from_place_id", "to_place_id", "departure_time", "arrival_time", "distance", "speed", "status"]);
        $val = Validator::make($all, [
            "type" => "required|in:TRAIN,BUS",
            "line" => "required|integer",
            "from_place_id" => "required|integer|exists:places,id|different:to_place_id",
            "to_place_id" => "required|integer|exists:places,id|different:from_place_id",
            "departure_time" => "required|date_format:H:i:s",
            "arrival_time" => "required|date_format:H:i:s|after:departure_time",
            "distance" => "required|integer",
            "spped" => "required|integer",
            "status" => "required|in:AVAILABLE,UNAVAILABLE",
        ]);
        if($val->fails()) return response(["message" => "Data cannot be processed"], 422);

        schedules::insert($all);

        return response(["message" => "create success"], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\schedules  $schedules
     * @return \Illuminate\Http\Response
     */
    public function show(schedules $schedules)
    {
        //
    }

    /**







    
     * Show the form for editing the specified resource.
     *
     * @param  \App\schedules  $schedules
     * @return \Illuminate\Http\Response
     */
    public function edit(schedules $schedules)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\schedules  $schedules
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, schedules $schedules)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\schedules  $schedules
     * @return \Illuminate\Http\Response
     */
    public function destroy($schedule_id)
    {
        $schedule = schedules::find($schedule_id);

        if (!$schedule) {
            return response([
                "message" => "Data cannot be deleted"
            ], 400);
        }

        $schedule->delete();
        return response([
            "message" => "delete success"
        ], 200);
    }
}
