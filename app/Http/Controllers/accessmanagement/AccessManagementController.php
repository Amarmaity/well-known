<?php

namespace App\Http\Controllers\accessmanagement;

use App\Http\Controllers\Controller;
use App\Models\AccessModule;
use App\Models\SuperAddUser;
use Illuminate\Http\Request;
use App\Models\AccessPermission;
use Illuminate\Support\Facades\DB;


class AccessManagementController extends Controller
{
    public function index()
    {
        $roles = SuperAddUser::where('status', 1)
            ->whereNotNull('user_type')
            ->where('user_type', '!=', 'users')
            ->where('user_type', '!=', 'client')
            ->select('user_type')
            ->distinct()
            ->orderBy('user_type')
            ->get();
        // dd($roles);

        $modules = AccessModule::with(['children' => function ($query) {
                $query->where('status', 1)
                    ->whereRaw('LOWER(module_key) != ?', ['dashboard'])
                    ->whereRaw('LOWER(module_name) != ?', ['dashboard']);
            }])
            ->whereNull('parent_id')
            ->where('status', 1)
            ->whereRaw('LOWER(module_key) != ?', ['dashboard'])
            ->whereRaw('LOWER(module_name) != ?', ['dashboard'])
            ->orderBy('sort_order')
            ->get();

        return view('admin.accessmanagement', compact('roles', 'modules'));
    }


    public function getUsersByRole($role)
    {
        $users = SuperAddUser::where('status', 1)
            ->whereRaw('LOWER(user_type) = ?', [strtolower($role)])
            ->get()
            ->map(function ($user) {

                return [

                    'id' => $user->id,

                    'text' => $user->fname . ' ' . $user->lname .
                        ' (' . $user->employee_id . ') - ' . $user->email,

                ];
            });

        return response()->json($users);
    }
    public function getUserPermission($userId)
    {
        $user = SuperAddUser::findOrFail($userId);

        $permissions = AccessPermission::join(
            'access_modules',
            'access_permissions.module_id',
            '=',
            'access_modules.id'
        )
            ->where('access_permissions.user_id', $userId)
            ->whereRaw('LOWER(access_modules.module_key) != ?', ['dashboard'])
            ->whereRaw('LOWER(access_modules.module_name) != ?', ['dashboard'])
            ->select(
                'access_modules.id',
                'access_modules.module_name as name'
            )
            ->orderBy('access_modules.module_name')
            ->get();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->fname . ' ' . $user->lname,
                'role' => ucfirst($user->user_type),
            ],
            'permissions' => $permissions,
        ]);
    }

    // public function savePermission(Request $request)
    // {
    //     $request->validate([
    //         'users' => 'required|array',
    //         'modules' => 'required|array'
    //     ]);

    //     DB::beginTransaction();

    //     try {

    //         foreach ($request->users as $userId) {

    //             AccessPermission::where('user_id', $userId)->delete();

    //             $insertData = [];

    //             foreach ($request->modules as $moduleId) {

    //                 $insertData[] = [
    //                     'user_id' => $userId,
    //                     'module_id' => $moduleId,
    //                     'created_at' => now(),
    //                     'updated_at' => now(),
    //                 ];
    //             }

    //             AccessPermission::insert($insertData);
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Permission Saved Successfully.'
    //         ]);

    //     } catch (\Exception $e) {

    //         DB::rollBack();

    //         return response()->json([
    //             'status' => false,
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }
    public function savePermission(Request $request)
    {
        $request->validate([
            'users' => 'required|array|min:1',
            'modules' => 'nullable|array'
        ]);

        DB::beginTransaction();

        try {

            foreach ($request->users as $userId) {

                // Remove all existing permissions
                AccessPermission::where('user_id', $userId)->delete();

                // If no modules selected, permission is revoked
                if (!empty($request->modules)) {

                    $insertData = [];

                    foreach ($request->modules as $moduleId) {

                        $insertData[] = [
                            'user_id' => $userId,
                            'module_id' => $moduleId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    AccessPermission::insert($insertData);
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => empty($request->modules)
                    ? 'Permission revoked successfully.'
                    : 'Permission updated successfully.'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
