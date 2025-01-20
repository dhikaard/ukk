<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddAdmin;
use App\Http\Requests\EditAdmin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class ManageAdminController extends Controller
{
    public function add(AddAdmin $request)
    {

        $adminRoleId = DB::table('roles')->where('role_name', 'ADMIN')->value('role_id');

        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $adminRoleId,
        ]);

        return response()->json($newUser, 201);
    }

    public function edit(EditAdmin $request)
    {
        $user = Auth::user();
        $ownerRoleId = DB::table('roles')->where('role_name', 'OWNER')->value('role_id');
        if ($user->role_id !== $ownerRoleId) {
            return response()->json(['error' => 'Anda bukan OWNER'], 403); // Forbidden
        }

        $existingUser = User::findOrFail($request->user_id);

        $existingUser->update([
            'role_id' => $request->role_id,
        ]);

        return response()->json($existingUser, 200);
    }
}
