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

                


                
                
                
                
                
                <?php if($userType === 'Super User'): ?>
                    
                    <li class="nav-item">
                        <a href="<?php echo e($dashboardLink); ?>"
                            class="nav-link <?php echo e(request()->routeIs('super-admin-view') ? 'active' : ''); ?>">
                            <i class="nav-icon bi bi-speedometer2"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    
                    <li
                        class="nav-item has-treeview <?php echo e(request()->routeIs('add-user', 'userlist') ? 'menu-open' : ''); ?>">
                        <a href="#"
                            class="nav-link <?php echo e(request()->routeIs('add-user', 'userlist') ? 'active' : ''); ?>">
                            <i class="nav-icon bi bi-people"></i>
                            <p>
                                User Management
                                <i class="right bi bi-chevron-down"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="<?php echo e(route('add-user')); ?>"
                                    class="nav-link <?php echo e(request()->routeIs('add-user') ? 'active' : ''); ?>">
                                    <p>Add Users</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('userlist')); ?>"
                                    class="nav-link <?php echo e(request()->routeIs('userlist') ? 'active' : ''); ?>">
                                    <p>User Management</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    
                    <li
                        class="nav-item has-treeview <?php echo e(request()->routeIs('create-client', 'client-list') ? 'menu-open' : ''); ?>">
                        <a href="#"
                            class="nav-link <?php echo e(request()->routeIs('create-client', 'client-list') ? 'active' : ''); ?>">
                            <i class="nav-icon bi bi-building"></i>
                            <p>
                                Client Management
                                <i class="right bi bi-chevron-down"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="<?php echo e(route('create-client')); ?>"
                                    class="nav-link <?php echo e(request()->routeIs('create-client') ? 'active' : ''); ?>">
                                    <p>Add Client</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('client-list')); ?>"
                                    class="nav-link <?php echo e(request()->routeIs('client-list') ? 'active' : ''); ?>">
                                    <p>Client Management</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    
                    <li
                        class="nav-item has-treeview <?php echo e(request()->routeIs(
                            'get-probation',
                            'super.search',
                            'appraisal-view',
                            'financial-view-tables',
                            'get-pending-apprasil',
                        )
                            ? 'menu-open'
                            : ''); ?>">

                        <a href="#"
                            class="nav-link <?php echo e(request()->routeIs(
                                'get-probation',
                                'super.search',
                                'appraisal-view',
                                'financial-view-tables',
                                'get-pending-apprasil',
                            )
                                ? 'active'
                                : ''); ?>">

                            <i class="nav-icon bi bi-graph-up"></i>

                            <p>
                                Performance
                                <i class="right bi bi-chevron-down"></i>
                            </p>

                        </a>

                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="<?php echo e(route('get-probation')); ?>"
                                    class="nav-link <?php echo e(request()->routeIs('get-probation') ? 'active' : ''); ?>">
                                    <p>Probation Period</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('super.search')); ?>"
                                    class="nav-link <?php echo e(request()->routeIs('super.search') ? 'active' : ''); ?>">
                                    <p>View All Review</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('appraisal-view')); ?>"
                                    class="nav-link <?php echo e(request()->routeIs('appraisal-view') ? 'active' : ''); ?>">
                                    <p>Appraisal</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('financial-view-tables')); ?>"
                                    class="nav-link <?php echo e(request()->routeIs('financial-view-tables') ? 'active' : ''); ?>">
                                    <p>Appraisal Done</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('get-pending-apprasil')); ?>"
                                    class="nav-link <?php echo e(request()->routeIs('get-pending-apprasil') ? 'active' : ''); ?>">
                                    <p>Pending Appraisal</p>
                                </a>
                            </li>

                        </ul>

                    </li>

                    
                    <li
                        class="nav-item has-treeview <?php echo e(request()->routeIs('financial.view', 'setting-view') ? 'menu-open' : ''); ?>">

                        <a href="#"
                            class="nav-link <?php echo e(request()->routeIs('financial.view', 'setting-view') ? 'active' : ''); ?>">

                            <i class="nav-icon bi bi-gear"></i>

                            <p>
                                Administration
                                <i class="right bi bi-chevron-down"></i>
                            </p>

                        </a>

                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="<?php echo e(route('financial.view')); ?>"
                                    class="nav-link <?php echo e(request()->routeIs('financial.view') ? 'active' : ''); ?>">
                                    <p>Financial Year</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('setting-view')); ?>"
                                    class="nav-link <?php echo e(request()->routeIs('setting-view') ? 'active' : ''); ?>">
                                    <p>Settings</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('designation-index')); ?>" class="nav-link">
                                    <p>Designation Management</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('access-management')); ?>" class="nav-link">
                                    <p>Access Management</p>
                                </a>
                            </li>

                        </ul>

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
</aside>
<?php /**PATH /opt/lampp/htdocs/well-known/resources/views/headerFooter/sidebar.blade.php ENDPATH**/ ?>