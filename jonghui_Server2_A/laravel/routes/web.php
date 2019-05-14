<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use \Illuminate\Support\Facades\Hash;

Route::get('/', function () {

	// $place = csv(ROOT."/data/place.csv");
	// $schedule = csv(ROOT."/data/schedule.csv");

	// \App\places::insert($place);
	// \App\schedule::insert($schedule);


	// \App\users::insert([
	// 	[
	// 		"username" => "admin",
	// 		"password" => Hash::make("adminpass"),
	// 		"remember_token" => md5("admin"),
	// 		"token" => ""
	// 	],
	// 	[
	// 		"username" => "user1",
	// 		"password" => Hash::make("user1pass"),
	// 		"remember_token" => md5("user1"),
	// 		"token" => ""
	// 	],
	// 	[
	// 		"username" => "user2",
	// 		"password" => Hash::make("user2pass"),
	// 		"remember_token" => md5("user2"),
	// 		"token" => ""
	// 	]
	// ]);

    return view('welcome');
});
