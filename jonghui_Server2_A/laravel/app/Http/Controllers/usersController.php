<?php

namespace App\Http\Controllers;

use App\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class usersController extends Controller
{
    public function login(Request $req)
    {
        $all = $req->only(["username", "password"]);
        $user = users::where(["username" => $all['username']])->first();

        if ($user && Hash::check($all['password'], $user->password)) {
            $user->update(['token' => md5($user->username)]);

            return response([
                'token' => md5($user->username),
                'role' => $user->username == "admin" ? "ADMIN" : "USER"
            ], 200);
        }

        return response([
            'message' => "invalid login",
        ], 401);
    }

    public function logout()
    {
        $user = \App\users::cur();
        $user->update(["token" => ""]);

        return response([
            "message" => "logout success"
        ], 200);
    }
}
