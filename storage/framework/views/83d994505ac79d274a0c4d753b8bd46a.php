<style>
.app-sidebar {
    position: absolute;
    left: 15px;
}
.close{
    display: none;
}
</style>
<aside class="app-sidebar">
    <span class="close">✖</span>
    <div class="sidebar-brand">
        <link rel="stylesheet" href="<?php echo e(asset('css/adminlte.css')); ?>">
        <img src="<?php echo e(asset('images/delostyleimg.png')); ?>" class="brand-image">
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <?php
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
                ?>

                <?php if(in_array($userType, ['Super User', 'admin', 'manager', 'hr', 'users', 'client'])): ?>
                <li class="nav-item menu-open">
                    <a href="<?php echo e($dashboardLink); ?>"
                        class="nav-link <?php echo e(request()->url() === $dashboardLink ? 'active' : ''); ?>">
                        <img src="<?php echo e(asset('images/dashboard.png')); ?>" alt="dashboard icon">
                        <p>Dashboard</p>
                    </a>
                </li>
                <?php endif; ?>


                
                
                <?php if($userType === 'Super User'): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('add-user')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('add-user') ? 'active' : ''); ?>">
                        <i class="nav-icon bi bi-person-plus"></i>
                        <p>Add Users</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('create-client')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('create-client') ? 'active' : ''); ?>">
                        <i class="nav-icon bi bi-person-plus"></i>
                        <p>Add Client</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('userlist')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('userlist') ? 'active' : ''); ?>">
                        <i class="nav-icon bi bi-people"></i>
                        <p>User Management</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('client-list')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('client-list') ? 'active' : ''); ?>">
                        <i class="nav-icon bi bi-people"></i>
                        <p>Client Management</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('get-probation')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('get-probation') ? 'active' : ''); ?>">
                        <i class="nav-icon bi bi-list"></i>
                        <p>Probation period List</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('super.search')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('super.search') ? 'active' : ''); ?>">
                        <i class="nav-icon bi bi-search"></i>
                        <p>View All Review</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('appraisal-view')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('appraisal-view') ? 'active' : ''); ?>">
                        <i class="nav-icon bi bi-graph-up-arrow"></i>
                        <p>Appraisal</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('financial.view')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('financial.view') ? 'active' : ''); ?>">
                        <i class="nav-icon bi bi-calendar-range"></i>
                        <p>Financial Year</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('financial-view-tables')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('financial-view-tables') ? 'active' : ''); ?>">
                        <i class="nav-icon bi bi-bar-chart-line"></i>
                        <p>Appraisal Done</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('get-pending-apprasil')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('get-pending-apprasil') ? 'active' : ''); ?>">
                        <i class="nav-icon bi bi-list"></i>
                        <p>Pending Appraisal List</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('setting-view')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('setting-view') ? 'active' : ''); ?>">
                        <i class="nav-icon bi bi-gear"></i>
                        <p>Setting</p>
                    </a>
                </li>
                <?php endif; ?>

                <?php if($userType === 'hr'): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('hr-review')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('hr-review') ? 'active' : ''); ?>">
                        <img src="<?php echo e(asset('images/review.png')); ?>" alt="review icon">
                        <p>HR Review</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('hr-review-list')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('hr-review-list') ? 'active' : ''); ?>">
                        <img src="<?php echo e(asset('images/review-emp.png')); ?>" alt="review icon">
                        <p>View Employee Review List</p>
                    </a>
                </li>
                <?php endif; ?>

                <?php if($userType === 'manager'): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('manager-review')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('manager-review') ? 'active' : ''); ?>">
                        <img src="<?php echo e(asset('images/review.png')); ?>" alt="review icon">
                        <p>Manager Review Section</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('manager-review-list')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('manager-review-list') ? 'active' : ''); ?>">
                        <img src="<?php echo e(asset('images/review-emp.png')); ?>" alt="review icon">
                        <p>View Employee Review List</p>
                    </a>
                </li>
                <?php endif; ?>

                <?php if($userType === 'admin'): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('admin-review')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('admin-review') ? 'active' : ''); ?>">
                        <img src="<?php echo e(asset('images/review.png')); ?>" alt="review icon">
                        <p>Admin Review Section</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('admin-review-list')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('admin-review-list') ? 'active' : ''); ?>">
                        <img src="<?php echo e(asset('images/review-emp.png')); ?>" alt="review icon">
                        <p>View Employee Review List</p>
                    </a>
                </li>
                <?php endif; ?>

                <?php if($userType === 'client'): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('client-review')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('client-review') ? 'active' : ''); ?>">
                        <img src="<?php echo e(asset('images/review.png')); ?>" alt="client review icon">
                        <p>Client Review Section</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('client-review-list')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('client-review-list') ? 'active' : ''); ?>">
                        <img src="<?php echo e(asset('images/review-emp.png')); ?>" alt="review icon">
                        <p>View Employee Review List</p>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(in_array($userType, ['users', 'admin', 'hr', 'manager'])): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('input-evaluation', ['employee_id' => session('employee_id')])); ?>"
                        class="nav-link <?php echo e(request()->routeIs('input-evaluation') ? 'active' : ''); ?>">
                        <img src="<?php echo e(asset('images/review-emp.png')); ?>" alt="review icon">
                        <p>Review Yourself</p>
                    </a>
                </li>
                <?php endif; ?>

                <?php if($userType === 'users'): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('get-review-reports', ['emp_id' => Session::get('employee_id')])); ?>"
                        class="nav-link <?php echo e(request()->is('get-review-reports*') ? 'active' : ''); ?>">
                        <i class="nav-icon bi bi-file-earmark-check text-white"></i>
                        <p>View Review Report</p>
                    </a>
                </li>
                <?php endif; ?>
                <?php if(in_array($userType, ['Super User', 'users', 'admin', 'manager', 'hr', 'client'])): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('logout-users')); ?>" class="nav-link"
                        onclick="event.preventDefault(); confirmLogout();">
                        <img src="<?php echo e(asset('images/logout.png')); ?>" alt="logout icon">
                        <p>Log Out</p>
                    </a>

                    <form id="logout-form" action="<?php echo e(route('logout-users')); ?>" method="POST" class="d-none">
                        <?php echo csrf_field(); ?>
                    </form>

                    <script>
                    function confirmLogout() {
                        if (confirm("Are you sure you want to log out?")) {
                            document.getElementById('logout-form').submit();
                        }
                    }
                    </script>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php if(session()->has('logout_reload')): ?>
        <script>
        location.reload();
        </script>
        <?php endif; ?>
    </div>
</aside><?php /**PATH /home3/delose1a/evalon.delostylestudio.co.in/resources/views/headerFooter/sidebar.blade.php ENDPATH**/ ?>