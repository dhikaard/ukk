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
    public function addUserAdmin(AddAdmin $request)
    {
        $adminRoleId = DB::table('roles')->where('role_name', 'Administrator')->value('role_id');

        $newUser = DB::table('users')->insertGetId([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $adminRoleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['user_id' => $newUser], 201);
    }

    public function editUserAdmin(EditAdmin $request)
    {
        $user = Auth::user();
        $ownerRoleId = DB::table('roles')->where('role_name', 'Owner')->value('role_id');
        if ($user->role_id !== $ownerRoleId) {
            return response()->json(['error' => 'Anda bukan Owner'], 403);
        }

        DB::table('users')
            ->where('user_id', $request->user_id)
            ->update(['role_id' => $request->role_id, 'updated_at' => now()]);

        $existingUser = DB::table('users')->where('user_id', $request->user_id)->first();

        return response()->json($existingUser, 200);
    }

    public function getUserAdmin(Request $request)
    {
        $keyword = $request->input('keyword', '');

        $users = DB::table('users as A')
            ->join('roles as B', 'A.role_id', '=', 'B.role_id')
            ->where('B.role_name', '!=', 'Pengguna')
            ->where(function ($query) use ($keyword) {
                $query->where('A.email', 'like', "%$keyword%")
                    ->orWhere('A.name', 'like', "%$keyword%");
            })
            ->select('A.*', 'B.role_name')
            ->get();

        return response()->json(['users' => $users], 200);
    }

    public function getRolePermission(Request $request)
    {
        $roles = DB::table('roles as A')
            ->join('permissions as B', 'A.role_id', '=', 'B.role_id')
            ->leftJoin('users as C', 'A.role_id', '=', 'C.role_id')
            ->select(
                'A.*',
                'A.role_name',
                DB::raw("
                CASE
                    WHEN B.permission_name = '' THEN ''
                    ELSE COALESCE(GROUP_CONCAT(B.permission_name, ', '), '')
                END as permission_name
            "),
                DB::raw("COUNT(C.user_id) as user_count")
            )
            ->groupBy('A.role_id', 'A.role_name')
            ->get();

        return response()->json(['roles' => $roles], 200);
    }

    public function getUserForAddAdmin(Request $request)
    {
        $users = DB::table('users as A')
            ->join('roles as B', 'A.role_id', '=', 'B.role_id')
            ->where('B.role_name', '=', 'Pengguna')
            ->select('A.*')
            ->get();

        return response()->json(['users' => $users], 200);
    }
}
