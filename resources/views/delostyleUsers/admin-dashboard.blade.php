{{-- @extends('layouts.app') <!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

@section('title', 'Admin Dashboard') <!-- Page Title -->

@section('breadcrumb', 'Admin') <!-- Breadcrumb -->

@section('page-title', 'Admin Dashboard') <!-- Page Title in Breadcrumb -->

@section('body-class', 'special-page') --}}
@extends('layouts.app')

@section('title', 'Evalon Dashboard')
@section('breadcrumb', 'Dashboard')
@section('page-title', 'Evalon Dashboard')
@section('body-class', 'admin-dashboard-page')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet"
        href="{{ asset('css/admin-dashboard.css') }}?v={{ file_exists(public_path('css/admin-dashboard.css')) ? filemtime(public_path('css/admin-dashboard.css')) : time() }}">
@endpush

@section('content')
    <div class="dashboard-page">

        {{-- Top header --}}
        <section class="dashboard-topbar">
            <div class="dashboard-title-area">
                <h1>Evalon Dashboard</h1>
            </div>

            <div class="dashboard-search">
                <i class="bi bi-search"></i>
                <input type="text" id="globalDashboardSearch" placeholder="Search data, reports, employees..."
                    autocomplete="off">
            </div>

            <div class="dashboard-user-area">
                <button type="button" class="topbar-icon-button">
                    <i class="bi bi-bell"></i>
                    <span class="notification-count">3</span>
                </button>

                <button type="button" class="topbar-icon-button">
                    <i class="bi bi-gear"></i>
                </button>

                <div class="dashboard-user">
                    <img src="https://ui-avatars.com/api/?name=Amar+Maity&background=244e91&color=ffffff" alt="Amar Maity">

                    <div>
                        <h6>Amar Maity</h6>
                        <span>Admin</span>
                    </div>

                    <i class="bi bi-chevron-down"></i>
                </div>
            </div>
        </section>

        {{-- Summary cards --}}
        <section class="dashboard-summary-grid">

            <div class="summary-card">
                <div class="summary-icon blue">
                    <i class="bi bi-people"></i>
                </div>

                <div class="summary-content">
                    <span>Total Employees</span>
                    <h3>1,240</h3>
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-icon dark-blue">
                    <i class="bi bi-building"></i>
                </div>

                <div class="summary-content">
                    <span>Total Clients</span>
                    <h3>48</h3>
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-icon sky">
                    <i class="bi bi-calendar3"></i>
                </div>

                <div class="summary-content">
                    <span>Active Cycle</span>
                    <h3 class="small-value">FY 2026-2027</h3>
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-icon red">
                    <i class="bi bi-calendar-x"></i>
                </div>

                <div class="summary-content">
                    <span>Pending Appraisals</span>
                    <h3>156</h3>
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-icon green">
                    <i class="bi bi-check-circle"></i>
                </div>

                <div class="summary-content">
                    <span>Completed</span>
                    <h3>892</h3>
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-icon gray">
                    <i class="bi bi-hourglass-split"></i>
                </div>

                <div class="summary-content">
                    <span>Probation</span>
                    <h3>42</h3>
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-icon purple">
                    <i class="bi bi-clock-history"></i>
                </div>

                <div class="summary-content">
                    <span>Avg Attendance</span>
                    <h3>94%</h3>
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-icon orange">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>

                <div class="summary-content">
                    <span>Appraisals</span>
                    <h3>27%</h3>
                </div>
            </div>

        </section>

        {{-- Work session history --}}
        <section class="dashboard-card session-history-card">
            <div class="card-heading">
                <h2>Work Session History</h2>

                <button type="button" class="text-button" data-message="All session history opened">
                    View All History
                </button>
            </div>

            <div class="table-responsive dashboard-table-wrapper">
                <table class="table dashboard-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Login</th>
                            <th>Logout</th>
                            <th>Duration</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>Oct 23, 2023</td>
                            <td>08:15 AM</td>
                            <td>06:12 PM</td>
                            <td>9h 57m</td>
                            <td>
                                <span class="status-badge completed">Completed</span>
                            </td>
                        </tr>

                        <tr>
                            <td>Oct 22, 2023</td>
                            <td>08:55 AM</td>
                            <td>06:01 PM</td>
                            <td>8h 17m</td>
                            <td>
                                <span class="status-badge completed">Completed</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Leave management header --}}
        <section class="section-title-row">
            <h2>Attendance &amp; Leave Data Master</h2>

            <button type="button" class="primary-dashboard-button" id="importExcelButton">
                <i class="bi bi-download"></i>
                Import Excel Sheet
            </button>

            <input type="file" id="excelFileInput" accept=".xlsx,.xls,.csv" hidden>
        </section>

        {{-- Leave balance + upload history --}}
        <section class="dashboard-two-column leave-section">

            <div class="dashboard-card">
                <div class="card-heading responsive-card-heading">
                    <h2>Employee Leave Balance</h2>

                    <div class="small-search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" id="leaveEmployeeSearch"
                            placeholder="Search employee by name, ID, department">
                    </div>
                </div>

                <div class="table-responsive dashboard-table-wrapper">
                    <table class="table dashboard-table" id="leaveBalanceTable">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Dept</th>
                                <th>Total</th>
                                <th>Used</th>
                                <th>Remaining</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    <div class="employee-cell">
                                        <img src="https://ui-avatars.com/api/?name=John+Doe" alt="John Doe">
                                        <div>
                                            <strong>John Doe</strong>
                                            <span>EMP-1001</span>
                                        </div>
                                    </div>
                                </td>
                                <td>Engineering</td>
                                <td>24</td>
                                <td>08</td>
                                <td class="remaining-leave">16</td>
                                <td>
                                    <span class="status-badge on-track">On Track</span>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="employee-cell">
                                        <img src="https://ui-avatars.com/api/?name=Sarah+Miller" alt="Sarah Miller">
                                        <div>
                                            <strong>Sarah Miller</strong>
                                            <span>EMP-1002</span>
                                        </div>
                                    </div>
                                </td>
                                <td>Marketing</td>
                                <td>24</td>
                                <td>22</td>
                                <td class="remaining-leave">02</td>
                                <td>
                                    <span class="status-badge low-balance">Low Balance</span>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="employee-cell">
                                        <img src="https://ui-avatars.com/api/?name=Kevin+Patel" alt="Kevin Patel">
                                        <div>
                                            <strong>Kevin Patel</strong>
                                            <span>EMP-1003</span>
                                        </div>
                                    </div>
                                </td>
                                <td>Design</td>
                                <td>24</td>
                                <td>10</td>
                                <td class="remaining-leave">14</td>
                                <td>
                                    <span class="status-badge on-track">On Track</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <span>Showing 1-10 of 1,240 employees</span>

                    <div class="pagination-buttons">
                        <button type="button">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button type="button" class="active">1</button>
                        <button type="button">2</button>
                        <button type="button">3</button>
                        <button type="button">...</button>
                        <button type="button">124</button>
                        <button type="button">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="dashboard-card upload-history-card">
                <div class="card-heading">
                    <h2>Excel Upload History</h2>
                </div>

                <div class="upload-history-list" id="uploadHistoryList">

                    <div class="upload-history-item">
                        <div>
                            <h5>Attendance_June</h5>
                            <p>Date: 14 Jun, 10:15 AM</p>
                            <p>Records: 1,240</p>
                        </div>

                        <div class="upload-user">
                            <i class="bi bi-download"></i>
                            <span>By Admin</span>
                            <small>Data Success</small>
                        </div>
                    </div>

                    <div class="upload-history-item">
                        <div>
                            <h5>Attendance_June</h5>
                            <p>Date: 14 Jun, 10:15 AM</p>
                            <p>Records: 1,240</p>
                        </div>

                        <div class="upload-user">
                            <i class="bi bi-download"></i>
                            <span>By Admin</span>
                            <small>Data Success</small>
                        </div>
                    </div>

                </div>

                <button type="button" class="outline-dashboard-button" data-message="Full upload history opened">
                    View Full History
                </button>
            </div>

        </section>

        {{-- Appraisal section --}}
        <section class="dashboard-two-column appraisal-section">

            <div class="dashboard-card">
                <div class="card-heading responsive-card-heading">
                    <h2>Ongoing Appraisals and Employee Details</h2>

                    <div class="small-search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" id="appraisalEmployeeSearch"
                            placeholder="Search employee by name, ID, department">
                    </div>
                </div>

                <div class="table-responsive dashboard-table-wrapper">
                    <table class="table dashboard-table" id="appraisalEmployeeTable">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Department</th>
                                <th>Reporting Manager</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    <div class="employee-cell">
                                        <img src="https://ui-avatars.com/api/?name=Arjun+Singh" alt="Arjun Singh">
                                        <div>
                                            <strong>Arjun Singh</strong>
                                            <span>EMP-1010</span>
                                        </div>
                                    </div>
                                </td>
                                <td>Engineering</td>
                                <td>Rajesh K.</td>
                                <td>
                                    <span class="status-badge in-review">In Review</span>
                                </td>
                                <td>15 July 2026</td>
                                <td>
                                    <button type="button" class="view-details-button">
                                        View Details
                                    </button>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="employee-cell">
                                        <img src="https://ui-avatars.com/api/?name=Sofia+Patel" alt="Sofia Patel">
                                        <div>
                                            <strong>Sofia Patel</strong>
                                            <span>EMP-1011</span>
                                        </div>
                                    </div>
                                </td>
                                <td>Product Management</td>
                                <td>Sara M.</td>
                                <td>
                                    <span class="status-badge pending">Pending</span>
                                </td>
                                <td>06 August 2026</td>
                                <td>
                                    <button type="button" class="view-details-button">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <span>Showing 1-10 of 1,240 employees</span>

                    <div class="pagination-buttons">
                        <button type="button">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button type="button" class="active">1</button>
                        <button type="button">2</button>
                        <button type="button">3</button>
                        <button type="button">...</button>
                        <button type="button">124</button>
                        <button type="button">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="dashboard-card appraisal-progress-card">
                <div class="card-heading">
                    <h2>Appraisal Progress</h2>
                </div>

                <div class="progress-stat">
                    <div class="progress-label">
                        <span>Not Started</span>
                        <strong>1,100</strong>
                    </div>
                    <div class="custom-progress">
                        <span style="width: 80%"></span>
                    </div>
                </div>

                <div class="progress-stat">
                    <div class="progress-label">
                        <span>Completed</span>
                        <strong>690</strong>
                    </div>
                    <div class="custom-progress green-progress">
                        <span style="width: 68%"></span>
                    </div>
                </div>

                <div class="progress-stat">
                    <div class="progress-label">
                        <span>In Review</span>
                        <strong>154</strong>
                    </div>
                    <div class="custom-progress purple-progress">
                        <span style="width: 43%"></span>
                    </div>
                </div>

                <div class="progress-stat">
                    <div class="progress-label">
                        <span>Pending</span>
                        <strong>100</strong>
                    </div>
                    <div class="custom-progress orange-progress">
                        <span style="width: 28%"></span>
                    </div>
                </div>

                <div class="progress-stat">
                    <div class="progress-label">
                        <span>Overdue</span>
                        <strong>52</strong>
                    </div>
                    <div class="custom-progress red-progress">
                        <span style="width: 15%"></span>
                    </div>
                </div>
            </div>

        </section>

        {{-- Attendance overview --}}
        <section class="dashboard-three-column">

            <div class="dashboard-card attendance-overview-card">
                <div class="card-heading">
                    <h2>Attendance Overview</h2>
                </div>

                <div class="attendance-overview-content">
                    <div class="attendance-circle">
                        <div>
                            <strong>94%</strong>
                            <span>Present</span>
                        </div>
                    </div>

                    <div class="attendance-stat-list">
                        <div class="attendance-stat present">
                            <span>Present</span>
                            <strong>1,160</strong>
                        </div>

                        <div class="attendance-stat absent">
                            <span>Absent</span>
                            <strong>15</strong>
                        </div>

                        <div class="attendance-stat leave">
                            <span>Leave</span>
                            <strong>45</strong>
                        </div>

                        <div class="attendance-stat late">
                            <span>Late</span>
                            <strong>12</strong>
                        </div>
                    </div>
                </div>

                <div class="attendance-date">
                    19.06.2026 - 17.07.2026
                </div>
            </div>

            <div class="dashboard-card leave-summary-card">
                <div class="card-heading">
                    <h2>My Leave Summary</h2>
                </div>

                <div class="leave-summary-item">
                    <div class="leave-circle privilege-circle">
                        <strong>12/16</strong>
                    </div>

                    <div>
                        <h5>Privilege Leaves</h5>
                        <p>4 Days Used</p>
                    </div>
                </div>

                <div class="leave-summary-item">
                    <div class="leave-circle sick-circle">
                        <strong>2/8</strong>
                    </div>

                    <div>
                        <h5>Sick Leaves</h5>
                        <p>6 Days Available</p>
                    </div>
                </div>

                <button type="button" class="apply-leave-button" id="applyLeaveButton">
                    Apply for Leave
                </button>
            </div>

            <div class="dashboard-card upcoming-events-card">
                <div class="card-heading">
                    <h2>Upcoming Events</h2>
                    <i class="bi bi-calendar3"></i>
                </div>

                <div class="event-item">
                    <div class="event-date blue-date">
                        <span>JUN</span>
                        <strong>12</strong>
                    </div>

                    <div>
                        <h5>Dussehra Holiday</h5>
                        <p>All Offices Closed</p>
                    </div>
                </div>

                <div class="event-item">
                    <div class="event-avatar">
                        <img src="https://ui-avatars.com/api/?name=Birthday+Team" alt="">
                    </div>

                    <div>
                        <h5>Birthdays (2)</h5>
                        <p>Amar, Ritu</p>
                    </div>
                </div>

                <div class="event-item">
                    <div class="event-icon orange-event">
                        <i class="bi bi-briefcase"></i>
                    </div>

                    <div>
                        <h5>Work Anniversaries</h5>
                        <p>5 Employees reaching 2 years</p>
                    </div>
                </div>

                <div class="event-item">
                    <div class="event-date blue-date">
                        <span>OCT</span>
                        <strong>12</strong>
                    </div>

                    <div>
                        <h5>Dussehra Holiday</h5>
                        <p>All Offices Closed</p>
                    </div>
                </div>
            </div>

        </section>

        {{-- Previous attendance records --}}
        <section class="dashboard-card attendance-record-card">

            <div class="card-heading responsive-card-heading">
                <div>
                    <h2>Previous Employee Attendance Records</h2>

                    <div class="small-search-box attendance-search">
                        <i class="bi bi-search"></i>
                        <input type="text" id="attendanceRecordSearch" placeholder="Search employee">
                    </div>
                </div>

                <button type="button" class="text-button" data-message="Complete attendance history opened">
                    View Complete Attendance History
                </button>
            </div>

            <div class="table-responsive dashboard-table-wrapper">
                <table class="table dashboard-table" id="attendanceRecordTable">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Dept</th>
                            <th>Date</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>
                                <div class="employee-cell">
                                    <img src="https://ui-avatars.com/api/?name=Arjun+Singh" alt="Arjun Singh">
                                    <div>
                                        <strong>Arjun Singh</strong>
                                        <span>EMP-1010</span>
                                    </div>
                                </div>
                            </td>
                            <td>IT</td>
                            <td>Oct 23</td>
                            <td>09:15 AM</td>
                            <td>05:30 PM</td>
                            <td>8h 15m</td>
                            <td>
                                <span class="status-badge late-status">Late</span>
                            </td>
                            <td>
                                <button type="button" class="view-details-button">
                                    View Details
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="employee-cell">
                                    <img src="https://ui-avatars.com/api/?name=Arjun+Singh" alt="Arjun Singh">
                                    <div>
                                        <strong>Arjun Singh</strong>
                                        <span>EMP-1010</span>
                                    </div>
                                </div>
                            </td>
                            <td>HR</td>
                            <td>Oct 23</td>
                            <td>08:55 AM</td>
                            <td>05:45 PM</td>
                            <td>8h 50m</td>
                            <td>
                                <span class="status-badge present-status">Present</span>
                            </td>
                            <td>
                                <button type="button" class="view-details-button">
                                    View Details
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    </div>

    {{-- Toast message --}}
    <div class="dashboard-toast" id="dashboardToast">
        <i class="bi bi-check-circle-fill"></i>
        <span id="dashboardToastMessage">Action completed</span>
    </div>
@endsection

@push('scripts')
    <script
        src="{{ asset('js/admin-dashboard.js') }}?v={{ file_exists(public_path('js/admin-dashboard.js')) ? filemtime(public_path('js/admin-dashboard.js')) : time() }}">
    </script>
@endpush
