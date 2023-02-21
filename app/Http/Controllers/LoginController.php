<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'msg' => 'Login successfully.',
                'token' => $user->createToken('login-token')->plainTextToken
            ]);
        }

        return response()->json([
            'status' => false,
            'msg' => 'Th provided credentials do not match our records..'
        ]);
    }
}
