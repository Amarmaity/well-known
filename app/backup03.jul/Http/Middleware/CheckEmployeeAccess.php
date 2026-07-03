<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\SuperAddUser;

class CheckEmployeeAccess
{
    public function handle(Request $request, Closure $next)
    {
        $userType = Session::get('user_type');
        $loginEmployeeId = Session::get('employee_id');
        $loginUserId = Session::get('user_id');
        $clientId = Session::get('client_id');

        $employeeId = $request->route('employee_id')
            ?? $request->route('emp_id');

        if (!$employeeId) {
            return $next($request);
        }

        if (!$userType) {
            Session::flush();
            return redirect()->route('all-user-login')
                ->with('error', 'Please log in to continue.');
        }

        $employee = SuperAddUser::where('employee_id', $employeeId)->first();

        if (!$employee) {
            Session::flush();
            return redirect()->route('all-user-login')
                ->with('error', 'Unauthorized access. Please log in again.');
        }

        switch ($userType) {
            case 'Super User':
            case 'admin':
            case 'hr':
                return $next($request);

            case 'users':
                if ($loginEmployeeId == $employee->employee_id) {
                    return $next($request);
                }
                break;

            case 'manager':
                 if ($loginEmployeeId == $employee->employee_id) {
        return $next($request);
    }

    // Manager can review employees assigned to him
    if ($employee->manager_id == $loginUserId) {
        return $next($request);
    }

    break;

            case 'client':
                $assignedClientIds = $employee->client_id;

                if (is_string($assignedClientIds)) {
                    $assignedClientIds = json_decode($assignedClientIds, true);
                }

                if (!is_array($assignedClientIds)) {
                    $assignedClientIds = [];
                }

                $assignedClientIds = array_map('strval', $assignedClientIds);

                if (in_array((string) $clientId, $assignedClientIds, true)) {
                    return $next($request);
                }
                break;
        }

        Session::flush();
        return redirect()->route('all-user-login')
            ->with('error', 'Unauthorized access. Please log in again.');
    }
}
