<?php

namespace App\Http\Controllers\accessmanagement;

use App\Http\Controllers\Controller;
use App\Models\AccessModule;
use App\Models\Designation;
use Illuminate\Http\Request;

class AccessManagementController extends Controller
{
    public function index()
    {
        $designations = Designation::where('status', 1)
            ->orderBy('designation_name')
            ->get();

        $modules = AccessModule::with('children')
            ->whereNull('parent_id')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->get();

        return view('admin.accessmanagement', compact('designations', 'modules'));
    }
}
