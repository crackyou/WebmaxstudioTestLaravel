<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function login(Request $request) {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Error'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
        ]);
    }
}
