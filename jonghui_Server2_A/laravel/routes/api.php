<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix("v1")->group(function() {

	Route::post("auth/login", "usersController@login");
	Route::resource("place", "placesController")->only(["index", "show"]);

	Route::middleware("auth.login")->group(function() {
		Route::get("route/search/{from_place_id}/{to_place_id}/{time?}", "routeController@search");
		Route::get("auth/logout", "usersController@logout");

		Route::middleware("auth.admin")->group(function() {	
			Route::resource("schedule", "schedulesController")->only(["store", "destroy"]);
			Route::resource("place", "placesController")->only(["store", "update", "destroy"]);
		});
	});

});