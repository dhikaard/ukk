<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(RegisterRequest $request)
    {
        $role = DB::table('roles')->where('role_name', 'Pengguna')->first();
        if (!$role) {
            return response()->json(['message' => 'Peran tidak ditemukan'], 404);
        }

        $userId = DB::table('users')->insertGetId([
            'user_code' => Str::random(20),
            'role_id' => $role->role_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'phone' => $request->phone,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $userWithRole = DB::table('users as A')
            ->join('roles as B', 'A.role_id', '=', 'B.role_id')
            ->where('A.user_id', $userId)
            ->first();

        return response()->json(['user' => [$userWithRole]]);
    }

    /**
     * Login a user.
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['message' => 'Gagal Login'], 401);
        }

        $userWithRole = DB::table('users as A')
            ->join('roles as B', 'A.role_id', '=', 'B.role_id')
            ->where('A.user_id', auth()->id())
            ->first();

        $permissions = DB::table('permissions')
            ->where('role_id', $userWithRole->role_id)
            ->pluck('permission_name')
            ->toArray();

        return response()->json([
            'access_token' => $token,
            'user' => [$userWithRole],
            'permissions' => $permissions,
        ]);
    }
}
