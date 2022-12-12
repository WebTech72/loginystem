<?php

namespace App\Http\Controllers;

use App\Models\User;
use Nette\Utils\Random;
use App\Mail\VerifyMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\RegistrationRequest;

class signUp extends Controller
{
    public function signUp(RegistrationRequest $request)
    {
        $token = Str::random(15);
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request['password'],
            'verify_token' => $token
        ]);
        if ($user) {
            $email = new VerifyMail($user, $token);
            Mail::to($user->email)->send($email);
            $response = response()->json(['error' => 'false', 'message' => 'Please verify from your email', 'data' => $user]);
        } else {
            $response = response()->json(['error' => 'false', 'message' => 'registration failed'], 422);
        }
        return $response;
    }

    public function verifyEmail(Request $request, $token)
    {
        $is_verify = User::where('verify_token', $token)->update([
            'verify_token' => null,
            'is_verified' => true,
            'email_verified_at' => now()
        ]);
        if ($is_verify) {
            return response()->json(['error' => 'false', 'message' => 'Email verified']);
        } else {
            return response()->json(['error' => 'false', 'message' => 'Verification failed'], 422);
        }
    }
}
