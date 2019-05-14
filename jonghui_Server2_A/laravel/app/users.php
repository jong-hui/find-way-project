<?php

namespace App;

use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Model;

class users extends Model
{
	protected $fillable = ["token"];
	protected $guardned = [];
	public $timestamps = false;

	public static function cur()
	{
		$token = Request::get("token") ?? "";
		return $token == "" ? null : self::whereToken($token)->first();
	}
}
