<style>
    .app-sidebar {
        position: absolute;
        left: 15px;
    }

    .close {
        display: none;
    }
</style>
<aside class="app-sidebar">
    <span class="close">✖</span>
    <div class="sidebar-brand">
        <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}">
        <img src="{{ asset('images/delostyleimg.png') }}" class="brand-image">
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                @php
                $userType = Session::get('user_type');

                // Using if conditions to handle multiple user types for admin and manager
                if ($userType == 'Super User') {
                $dashboardLink = route('super-admin-view');
                } elseif ($userType == 'admin' || $userType == 3) {
                $dashboardLink = route('admin-dashboard');
                } elseif ($userType == 'manager' || $userType == 2) {
                $dashboardLink = route('manager-dashboard');
                } elseif ($userType == 'hr' || $userType == 4) {
                $dashboardLink = route('hr-dashboard');
                } elseif ($userType == 'users' || $userType == 5) {
                $dashboardLink = route('users-dashboard');
                } elseif ($userType == 'client') {
                $dashboardLink = route('client-dashboard');
                } else {
                $dashboardLink = '#'; // Default case
                }
                @endphp

                @php

                use App\Models\AccessPermission;

                $userId = session('user_id');

                $permissions = [];

                if(session('user_type') != 'Super User'){
                $permissions = AccessPermission::where('user_id',$userId)
                ->pluck('module_id')
                ->toArray();
                }

                function canAccess($moduleId,$permissions){

                if(session('user_type') == 'Super User'){
                return true;
                }

                return in_array($moduleId,$permissions);
                }

                @endphp

                @if(in_array($userType, ['Super User', 'admin', 'manager', 'hr', 'users', 'client']))
                <li class="nav-item menu-open">
                    <a href="{{ $dashboardLink }}"
                        class="nav-link {{ request()->url() === $dashboardLink ? 'active' : '' }}">
                        <img src="{{ asset('images/dashboard.png') }}" alt="dashboard icon">
                        <p>Dashboard</p>
                    </a>
                </li>
                @endif

                @if($userType === 'Super User')
                @if(canAccess(2,$permissions))
                <li class="nav-item">
                    <a href="{{route('add-user')}}"
                        class="nav-link {{ request()->routeIs('add-user') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-plus"></i>
                        <p>Add Users</p>
                    </a>
                </li>
                @endif
                @if(canAccess(3,$permissions))
                <li class="nav-item">
                    <a href="{{route('create-client')}}"
                        class="nav-link {{ request()->routeIs('create-client') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-plus"></i>
                        <p>Add Client</p>
                    </a>
                </li>
                @endif
                @if(canAccess(4,$permissions))
                <li class="nav-item">
                    <a href="{{route('userlist')}}"
                        class="nav-link {{ request()->routeIs('userlist') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-people"></i>
                        <p>User Management</p>
                    </a>
                </li>
                @endif
                @if(canAccess(5,$permissions))
                <li class="nav-item">
                    <a href="{{route('client-list')}}"
                        class="nav-link {{ request()->routeIs('client-list') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-people"></i>
                        <p>Client Management</p>
                    </a>
                </li>
                @endif
                @if(canAccess(6,$permissions))
                <li class="nav-item">
                    <a href="{{route('get-probation')}}"
                        class="nav-link {{ request()->routeIs('get-probation') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-list"></i>
                        <p>Probation period List</p>
                    </a>
                </li>
                @endif
                @if(canAccess(7,$permissions))
                <li class="nav-item">
                    <a href="{{route('super.search')}}"
                        class="nav-link {{ request()->routeIs('super.search') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-search"></i>
                        <p>View All Review</p>
                    </a>
                </li>
                @endif
                @if(canAccess(8,$permissions))
                <li class="nav-item">
                    <a href="{{route('appraisal-view')}}"
                        class="nav-link {{ request()->routeIs('appraisal-view') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-graph-up-arrow"></i>
                        <p>Appraisal</p>
                    </a>
                </li>
                @endif
                @if(canAccess(9,$permissions))
                <li class="nav-item">
                    <a href="{{route('financial.view')}}"
                        class="nav-link {{ request()->routeIs('financial.view') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-calendar-range"></i>
                        <p>Financial Year</p>
                    </a>
                </li>
                @endif
                @if(canAccess(10,$permissions))
                <li class="nav-item">
                    <a href="{{route('financial-view-tables')}}"
                        class="nav-link {{ request()->routeIs('financial-view-tables') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-bar-chart-line"></i>
                        <p>Appraisal Done</p>
                    </a>
                </li>
                @endif
                @if(canAccess(11,$permissions))
                <li class="nav-item">
                    <a href="{{route('get-pending-apprasil')}}"
                        class="nav-link {{ request()->routeIs('get-pending-apprasil') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-list"></i>
                        <p>Pending Appraisal List</p>
                    </a>
                </li>
                @endif
                @if(canAccess(12,$permissions))
                <li class="nav-item">
                    <a href="{{route('setting-view')}}"
                        class="nav-link {{ request()->routeIs('setting-view') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-gear"></i>
                        <p>Setting</p>
                    </a>
                </li>
                @endif
                @endif

                @if($userType === 'hr')
                <li class="nav-item">
                    <a href="{{route('hr-review')}}"
                        class="nav-link {{ request()->routeIs('hr-review') ? 'active' : '' }}">
                        <img src="{{ asset('images/review.png') }}" alt="review icon">
                        <p>HR Review</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('hr-review-list')}}"
                        class="nav-link {{ request()->routeIs('hr-review-list') ? 'active' : '' }}">
                        <img src="{{ asset('images/review-emp.png') }}" alt="review icon">
                        <p>View Employee Review List</p>
                    </a>
                </li>
                @endif

                @if($userType === 'manager')
                <li class="nav-item">
                    <a href="{{route('manager-review')}}"
                        class="nav-link {{ request()->routeIs('manager-review') ? 'active' : '' }}">
                        <img src="{{ asset('images/review.png') }}" alt="review icon">
                        <p>Manager Review Section</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('manager-review-list')}}"
                        class="nav-link {{ request()->routeIs('manager-review-list') ? 'active' : '' }}">
                        <img src="{{ asset('images/review-emp.png') }}" alt="review icon">
                        <p>View Employee Review List</p>
                    </a>
                </li>
                @endif

                @if($userType === 'admin')
                <li class="nav-item">
                    <a href="{{route('admin-review')}}"
                        class="nav-link {{ request()->routeIs('admin-review') ? 'active' : '' }}">
                        <img src="{{ asset('images/review.png') }}" alt="review icon">
                        <p>Admin Review Section</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('admin-review-list')}}"
                        class="nav-link {{ request()->routeIs('admin-review-list') ? 'active' : '' }}">
                        <img src="{{ asset('images/review-emp.png') }}" alt="review icon">
                        <p>View Employee Review List</p>
                    </a>
                </li>
                @endif

                @if($userType === 'client')
                <li class="nav-item">
                    <a href="{{route('client-review')}}"
                        class="nav-link {{ request()->routeIs('client-review') ? 'active' : '' }}">
                        <img src="{{ asset('images/review.png') }}" alt="client review icon">
                        <p>Client Review Section</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('client-review-list')}}"
                        class="nav-link {{ request()->routeIs('client-review-list') ? 'active' : '' }}">
                        <img src="{{ asset('images/review-emp.png') }}" alt="review icon">
                        <p>View Employee Review List</p>
                    </a>
                </li>
                @endif

                @php
                $employee = \App\Models\SuperAddUser::where('employee_id', session('employee_id'))->first();
                @endphp

                @if(in_array($userType, ['users', 'admin', 'hr', 'manager']) && optional($employee)->employee_status ===
                'Employee')
                <li class="nav-item">
                    <a href="{{route('input-evaluation', ['employee_id' => session('employee_id')])}}"
                        class="nav-link {{ request()->routeIs('input-evaluation') ? 'active' : '' }}">
                        <img src="{{ asset('images/review-emp.png') }}" alt="review icon">
                        <p>Review Yourself</p>
                    </a>
                </li>
                @endif

                @if($userType === 'users')
                <li class="nav-item">
                    <a href="{{ route('get-review-reports', ['emp_id' => Session::get('employee_id')]) }}"
                        class="nav-link {{ request()->is('get-review-reports*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-file-earmark-check text-white"></i>
                        <p>View Review Report</p>
                    </a>
                </li>
                @endif
                
                @if(in_array($userType, ['Super User', 'users', 'admin', 'manager', 'hr', 'client']))
                <li class="nav-item">
                    <a href="{{ route('logout-users') }}" class="nav-link"
                        onclick="event.preventDefault(); confirmLogout();">
                        <img src="{{ asset('images/logout.png') }}" alt="logout icon">
                        <p>Log Out</p>
                    </a>

                    <form id="logout-form" action="{{ route('logout-users') }}" method="POST" class="d-none">
                        @csrf
                    </form>

                    <script>
                        function confirmLogout() {
                        if (confirm("Are you sure you want to log out?")) {
                            document.getElementById('logout-form').submit();
                        }
                    }
                    </script>
                </li>
                @endif
            </ul>
        </nav>
        @if(session()->has('logout_reload'))
        <script>
            location.reload();
        </script>
        @endif
    </div>
</aside>