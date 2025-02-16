<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = auth('api')->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'new_password' => 'nullable|min:8|confirmed',
            'current_password' => 'required_with:new_password',
        ]);

        // Check current password if trying to change password
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'error' => ['current_password' => ['Password saat ini tidak sesuai']]
                ], 422);
            }
        }

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ];

        if ($request->filled('new_password')) {
            $updateData['password'] = Hash::make($validated['new_password']);
        }

        $user->update($updateData);

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'user' => $user
        ]);
    }
}