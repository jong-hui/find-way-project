<?php

namespace App\Http\Controllers;

use App\places;
use Illuminate\Support\Facades\Request as Re;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class placesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \App\users::cur();
        $data = places::orderBy("name", "asc")->get();

        if ($user) {
            $result = $data->keyBy("id");
            $history = \App\histories::orderBy("count", "desc")->whereUserId($user->id)->get();
            $except = collect($result)->except($history->pluck("place_id"));

            $only = $history->sortBy("name")->map(function($a, $b) use ($result) {
                return $result[$a->place_id];
            });

            $result = $only->merge($except);

            return response($result, 200);
        } else {
            if (Re::get("token")) {
                return response(["message" => "Unauthorized user"], 401);
            }

            return response($data, 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        return response(\App\users::test());
        $all = $request->only(["name", "latitude", "longitude", "image", "description", "x", "y"]);
        $val = Validator::make($all, [
            'name' => "required|string",
            'latitude' => "required|integer|between:20,100",
            'longitude' => "required|integer|between:20,100",
            'image' => "required|file|image",
            "description" => "nullable|string",
            "x" => "nullable|integer",
            "y" => "nullable|integer"
        ]);

        if ($val->fails()) {
            return response([
                "message" => "Data cannot be processed"
            ], 422);
        }

        $image = $request->file("image");
        $name = $image->getClientOriginalName();
        $image->move(ROOT."/uploads", $name);

        unset($all['image']);
        $all['image_path'] = $name;
        if (empty($all['x'])) {
            $all['x'] = ($all['longitude'] - 54.2522) / 0.0003218671875;
        }
        if (empty($all['y'])) {
            $all['y'] = ($all['latitude'] - 24.59895) / (-0.000305825);
        }

        places::insert($all);

        return response([
            "message" => "create success"
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\places  $places
     * @return \Illuminate\Http\Response
     */
    public function show($place_id)
    {
        $place = places::whereId($place_id)->first();
        if ($place) {
            return response($place, 200);
        } else {
            return response([
                "message" => "Data cannot be processed"
            ], 422);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\places  $places
     * @return \Illuminate\Http\Response
     */
    public function edit(places $places)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\places  $places
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $place_id)
    {
        $place = places::find($place_id);
        if (!$place) {
            return response(["message" => "Data cannot be updated"], 400);
        }
        $all = $request->only(["name", "latitude", "longitude", "image", "description"]);
        $val = Validator::make($all, [
            'name' => "nullable|string",
            'latitude' => "nullable",
            'longitude' => "nullable",
            'image' => "nullable|file|image",
            "description" => "nullable|string"
        ]);

        if ($val->fails()) {
            return response([
                "message" => "Data cannot be updated"
            ], 400);
        }

        if (isset($all['image'])) {
            $image = $request->file("image");
            $name = $image->getClientOriginalName();
            $image->move(ROOT."/uploads", $name);
            unset($all['image']);
            $all['image_path'] = $name;
        }
        if (isset($all['longitude'])) {
            $all['x'] = ($all['longitude'] - 54.2522) / 0.0003218671875;
        }
        if (isset($all['latitude'])) {
            $all['y'] = ($all['latitude'] - 24.59895) / (-0.000305825);
        }

        $place->update($all);

        return response([
            "message" => "update success"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\places  $places
     * @return \Illuminate\Http\Response
     */
    public function destroy($place_id)
    {
        $place = places::find($place_id);
        if ($place) {
            $place->delete();
            return response([
                "message" => "delete success"
            ]);
        } 

        return response([
            "message" => "Data cannot be deleted",
        ], 400);
    }
}
