<?php

namespace App\Http\Controllers\accessmanagement;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Models\SuperAddUser;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index()
    {
        $designations = Designation::orderBy('designation_name')->get();

        return view('admin.Designation.index', compact('designations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'designation_name' => 'required|unique:designations,designation_name',
        ]);

        Designation::create([
            'designation_name' =>  strtolower(trim($request->designation_name)),
            'status' => 1
        ]);

        return back()->with('success', 'Designation Added Successfully');
    }

    public function edit($id)
    {
        $designation = Designation::findOrFail($id);

        return response()->json($designation);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'designation_name' => 'required|unique:designations,designation_name,' . $id,
        ]);

        $designation = Designation::findOrFail($id);

        $designation->update([
            'designation_name' => strtolower(trim($request->designation_name)),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Designation Updated Successfully.'
        ]);
    }

    public function changeStatus($id)
    {
        $designation = Designation::findOrFail($id);

        $designation->status = !$designation->status;

        $designation->save();

        return response()->json([
            'success' => true,
            'status' => $designation->status
        ]);
    }

    public function destroy($id)
    {
        $designation = Designation::findOrFail($id);

        $exists = SuperAddUser::where('designation', $designation->designation_name)->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'This designation is already assigned to users.'
            ]);
        }

        $designation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Designation deleted successfully.'
        ]);
    }
}