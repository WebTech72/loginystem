<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where(['email' => $request->email, 'password' => $request->password, 'is_verified' => true])->first();
        if ($user) {
            $token = Str::random(15);
            User::where('id', $user->id)->update(['remember_token' => $token]);
            return response()->json(['error' => 'false', 'message' => 'user loggedin successfully', 'token' => $token]);
        } else {
            return response()->json(['error' => 'true', 'message' => 'invalid credentials']);
        }
    }

    public function userData(Request $request)
    {
        return User::where('email', $request->email)->first();
    }

    public function logout(Request $request)
    {
        $request->validate(['token' => 'required']);
        if (User::where('remember_token', $request->token)->update(['remember_token' => null])) {
            return response()->json(['error' => 'false', 'message' => 'logged out']);
        } else {
            return response()->json(['error' => 'true', 'message' => 'invalid token']);
        }
    }
}
