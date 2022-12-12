<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use App\Mail\ForgetPassword;
use Illuminate\Http\Request;
use App\Http\Requests\ResetRequest;
use Illuminate\Support\Facades\Mail;

class resetController extends Controller
{
    public function forgetPassword(Request $request)
    {
        $request->validate(['email' => 'required|string|email|exists:users,email']);
        $email = User::where('email', $request->email)->where('is_verified', true)->first();
        if (!$email) {
            return response()->json(['error' => 'true', 'message' => 'invalid email'], 422);
        }
        $token = Str::random(15);
        User::where('email', $request->email)->update(['reset_token' => $token]);
        $mail = new ForgetPassword($token);
        Mail::to($email)->send($mail);
        return response()->json(['error' => 'false', 'message' => 'Check your email and follow instructions'], 200);
    }

    public function resetPassword(ResetRequest $request, $token)
    {
        $is_token = User::where('reset_token', $token)->first();
        if (!$is_token) {
            return response()->json(['error' => 'true', 'message' => 'invalid token']);
        }
        User::where('reset_token', $token)->update([
            'reset_token' => null,
            'password' => $request->password
        ]);
        return response()->json(['error' => 'false', 'message' => 'Password updated']);
    }
}
