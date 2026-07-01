<?php

namespace App\Http\Controllers\accessmanagement;

use App\Http\Controllers\Controller;
use App\Models\AccessModule;
use App\Models\SuperAddUser;



class AccessManagementController extends Controller
{
    public function index()
    {
        $designations = SuperAddUser::where('status', 1)
            ->whereNotNull('user_type')
            ->select('user_type')
            ->distinct()
            ->orderBy('user_type')
            ->get();

        // dd($designations);

        $modules = AccessModule::with('children')
            ->whereNull('parent_id')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->get();

        return view('admin.accessmanagement', compact('designations', 'modules'));
    }
}
