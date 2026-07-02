<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;



class CheckRole
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('Session Data:', Session::all());
        $user = Session::get('user_type');
        Log::info('Retrieved User Type: ' . ($user ?? 'null'));

        if (!$user || !in_array($user, ['admin', 'hr', 'manager', 'users', 'client', 'Super User'])) {
            Log::warning("User logged out due to invalid user type: " . ($user ?? 'null'));
            Auth::logout();
            Session::invalidate();
            return redirect()->route('all-user-login')->with('error', 'Unauthorized access. You have been logged out.');
        }

        $roleRoutes = [
            'admin' => ['admin-dashboard', 'admin-review', 'input-evaluation', 'admin-review-section', 'admin-review-list', 'user-admin-details', 'user-report-view-evaluation'],

            'hr' => ['hr-dashboard', 'hr-review', 'input-evaluation', 'hr-review-list', 'user-hr-details', 'user-report-view-evaluation'],
            'manager' => ['manager-dashboard', 'manager-review', 'input-evaluation', 'manager-review-list', 'user-manager-details', 'manager-review-list', 'user-manager-details', 'user-report-view-evaluation'],
            'users' => ['users-dashboard', 'input-evaluation', 'log-out-users', 'get-review-reports'],
            'client' => ['client-dashboard', 'client-review', 'client-dashboard', 'user-client-details', 'user-report-view-evaluation'],

            'Super User' => [
                'super-user-dashboard',
                'super-user-review',
                'add-user',
                'userlist',
                'super.search',
                'appraisal-view',
                'financial.view',
                'logout-users',
                'user-search',
                'super-admin-search',
                'super-user-search-bar',
                'active-user',
                'financial-view',
                'super-admin-view',
                'financial-view-tables',
                'employee.details',
                'evaluation.details',
                'hr.review.details',
                'manager.review.details',
                'admin.review.details',
                'setting-view',
                'create-client',
                'client-list',
                'edit-client',
                'update-client',
                'get-probation',
                'financial-view-tables',
                'get-pending-apprasil',
                'setting-view',
                'edit-user',
                'access-management',
                'access.permission.save',
                'access.get.users',
                'access.get.user.permission'
            ]
        ];

        $permissionRoutes = [
            // Add Users
            'add-user' => 2,
            'save-user' => 2,
            'get.managers' => [2, 4],
            'get.manager' => [2, 4],
            'get.admins' => [2, 4],
            'get.hrs' => [2, 4],
            'get.clients' => [2, 4],

            // Add Client
            'create-client' => 3,
            'new-client' => 3,

            // User Management
            'userlist' => 4,
            'active-user' => 4,
            'edit-user' => 4,
            'update-user' => 4,
            'toggle-user-status' => 4,

            // Client Management
            'client-list' => 5,
            'edit-client' => 5,
            'update-client' => 5,
            'client.toggle.status' => 5,

            // Probation period List
            'get-probation' => 6,
            'employees.filter-financial-year' => 6,
            'employee.update-status' => 6,
            'employee.update-probation-date' => 6,

            // View All Review
            'super.search' => 7,
            'super-user-search-bar' => 7,
            'employee.details' => 7,
            'evaluation.details' => 7,
            'hr.review.details' => 7,
            'manager.review.details' => 7,
            'admin.review.details' => 7,
            'client.review.details' => 7,
            'employees-filter-financial-year-employee-review' => 7,

            // Appraisal
            'appraisal-view' => 8,
            'apprisal.data' => 8,
            'appraisal.filter.by.year' => 8,
            'employee.review-score-super-user' => 8,

            // Financial Year
            'financial.view' => 9,
            'financial.data' => 9,
            'financial-data-store' => 9,

            // Appraisal Done
            'financial-view-tables' => 10,
            'super.user.search.bar' => 10,
            'financial.filter-financial-year' => 10,

            // Pending Appraisal List
            'get-pending-apprasil' => 11,

            // Setting
            'setting-view' => 12,
            'submit-apprisal-all' => 12,
            'update-financial-year' => 12,
        ];

        $currentRoute = $request->route()->getName();
        Log::info('Current Route: ' . ($currentRoute ?? 'null'));

        $isRoleRoute = isset($roleRoutes[$user]) && in_array($currentRoute, $roleRoutes[$user], true);
        $isPermissionRoute = false;

        if (isset($permissionRoutes[$currentRoute])) {
            foreach ((array) $permissionRoutes[$currentRoute] as $moduleId) {
                if (canAccess($moduleId)) {
                    $isPermissionRoute = true;
                    break;
                }
            }
        }

        if (!$isRoleRoute && !$isPermissionRoute) {
            Auth::logout();
            Session::invalidate();
            return redirect()->route('all-user-login')
                ->with('error', 'Unauthorized access. You have been logged out.');
        }
        return $next($request);
    }
  
}