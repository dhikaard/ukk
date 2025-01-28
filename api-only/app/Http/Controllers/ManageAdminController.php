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

    public function editUserAdmin(EditAdmin $request)
    {
        $user = Auth::user();
        $hasPermission = DB::table('permissions')
            ->where('role_id', $user->role_id)
            ->where('permission_name', 'manageAdmin')
            ->exists();

        if (!$hasPermission) {
            return response()->json(['error' => 'Anda tidak memiliki izin untuk mengelola admin'], 403);
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
            ->select('A.*', 'B.role_name', 'B.role_id')
            ->orderBy('B.authority')
            ->orderBy('A.name')
            ->get();

        return response()->json(['users' => $users], 200);
    }

    public function getRolePermission(Request $request)
    {
        $roles = DB::table('roles as A')
            ->leftJoin('users as C', 'A.role_id', '=', 'C.role_id')
            ->where('A.role_name', '!=', 'Pengguna')
            ->select(
                'A.*',
                'A.role_name',
                DB::raw("
                    COALESCE((
                        SELECT GROUP_CONCAT(
                            CASE
                                WHEN B.permission_name = 'manageAdmin' THEN 'Kelola Admin'
                                WHEN B.permission_name = 'manageProduct' THEN 'Kelola Barang'
                                ELSE B.permission_name
                            END
                        , ', ')
                        FROM permissions as B
                        WHERE B.role_id = A.role_id
                    ), '') as permission_name
                "),
                DB::raw("COUNT(DISTINCT C.user_id) as user_count")
            )
            ->groupBy('A.role_id', 'A.role_name')
            ->orderBy('A.authority')
            ->orderBy('A.role_name')
            ->get();

        return response()->json(['roles' => $roles], 200);
    }


    public function getUserForAddAdmin(Request $request)
    {
        $users = DB::table('users as A')
            ->join('roles as B', 'A.role_id', '=', 'B.role_id')
            ->where('B.role_name', '=', 'Pengguna')
            ->select('A.*')
            ->orderBy('B.authority')
            ->orderBy('A.name')
            ->get();

        return response()->json(['users' => $users], 200);
    }
}
