<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Administrator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validate = $request->validate([
                "email" => "required|string",
                "password" => "required|string",
            ]);

            $user = User::with('warehouse')->where('email', $request['email'])->first();
            if (!$user) {
                $admins = Administrator::where('email', $validate['email'])->first();
                if (!$admins) {
                    return response()->json([
                        "message" => "Invalid email or password."
                    ], 401);
                }

                if (!Hash::check($validate['password'], $admins->password)) {
                    return response()->json([
                        "message" => "Invalid email or password."
                    ], 401);
                }

                $token = $admins->createToken('auth')->plainTextToken;

                return response()->json([
                    "message" => "Login successful.",
                    "data" => [
                        "user" => [
                            $admins,
                            "role" => "admin"
                        ],
                        "token" => $token,
                        "created_at" => $admins->created_at
                    ]
                ], 200);
            };

            $token = $user->createToken('auth')->plainTextToken;

            return response()->json([
                "message" => "Login successful.",
                "data" => [
                    "user" => $user,
                    "token" => $token,
                    "created_at" => $user->created_at
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => "error",
                "errors" => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $session = $request->user();

            $session->currentAccessToken()->delete();
            return response()->json([
                "message" => "Logout successfull"
            ],  200);
        } catch (Exception $e) {
            return response()->json([
                "message" => "error",
                "errors" => $e->getMessage()
            ], 500);
        }
    }
}
