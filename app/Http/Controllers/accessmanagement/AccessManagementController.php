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

        $modules = AccessModule::with('children')
            ->whereNull('parent_id')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->get();

        return view('admin.accessmanagement', compact('roles', 'modules'));
    }


    public function getUsersByRole($role)
    {
        // $users = SuperAddUser::where('status', 1)
        //     ->where('user_type', $role)
        //     ->select('id', 'fname', 'lname', 'email', 'employee_id')
        //     ->orderBy('fname')
        //     ->get()
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

    public function savePermission(Request $request)
    {
        $request->validate([
            'users'   => 'required|array',
            'modules' => 'required|array',
        ]);

        DB::beginTransaction();

        try {

            foreach ($request->users as $userId) {

                // পুরনো permission delete
                AccessPermission::where('user_id', $userId)->delete();

                // নতুন permission insert
                foreach ($request->modules as $moduleId) {

                    AccessPermission::create([
                        'user_id'   => $userId,
                        'module_id' => $moduleId,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Permission Saved Successfully.'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
