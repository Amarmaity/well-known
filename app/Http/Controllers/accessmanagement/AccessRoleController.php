<?php

namespace App\Http\Controllers\accessmanagement;

use App\Http\Controllers\Controller;
use App\Models\AccessModule;
use App\Models\AccessRole;
use App\Models\SuperAddUser;
use Illuminate\Http\Request;

class AccessRoleController extends Controller
{
    public function index()
    {
        $roles = AccessRole::where('status', 1)
            ->orderBy('role_name')
            ->get();

            // dd($roles);

        $modules = AccessModule::with('children')
            ->whereNull('parent_id')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->get();

        return view('admin.Designation.index', compact('roles', 'modules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required|unique:access_roles,role_name',
        ]);

        AccessRole::create([
            'role_name' => strtolower(trim($request->role_name)),
            'status' => 1
        ]);

        return back()->with('success', 'Role Added Successfully');
    }

    public function edit($id)
    {
        $role = AccessRole::findOrFail($id);

        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'role_name' => 'required|unique:access_roles,role_name,' . $id,
        ]);

        $role = AccessRole::findOrFail($id);

        $role->update([
            'role_name' => strtolower(trim($request->role_name)),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Role Updated Successfully.'
        ]);
    }

    public function changeStatus($id)
    {
        $role = AccessRole::findOrFail($id);

        $role->status = !$role->status;

        $role->save();

        return response()->json([
            'success' => true,
            'status' => $role->status
        ]);
    }

    public function destroy($id)
    {
        $role = AccessRole::findOrFail($id);

        // যদি super_add_users table-এ role_name/user_type হিসেবে store করো
        $exists = SuperAddUser::where('user_type', $role->role_name)->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'This role is already assigned to users.'
            ]);
        }

        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully.'
        ]);
    }
}