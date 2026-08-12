@extends('layouts.app') <!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

@section('title', 'Admin Dashboard') <!-- Page Title -->

@section('breadcrumb', 'Admin') <!-- Breadcrumb -->

@section('page-title', 'Admin Dashboard')

@section('body-class', 'special-page admin-dashboard-page')

@section('content')

<!-- ============ MAIN ============ -->
  {{-- <main class="main-area admin-dashboard">
 
    <!-- Topbar -->
    <div class="topbar">
      <div class="topbar-left">
        <button class="menu-btn" id="menuBtn" aria-label="Open menu">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
        <h1 class="page-title">Evalon Dashboard</h1>
      </div>
 
      <div class="search-wrap">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" placeholder="Search data, reports, employees..." />
      </div>
 
      <div class="topbar-right">
        <button class="icon-btn" aria-label="Notifications">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
          <span class="notif-badge">1</span>
        </button>
        <div class="user-chip">
          <img class="user-avatar" src="https://i.pravatar.cc/80?img=59" alt="Amar Maity" />
          <div class="user-text">
            <div class="user-name"><span class="name-full">Amar Maity</span><span class="name-first">Amar</span></div>
            <div class="user-role">Admin</div>
          </div>
        </div>
      </div>
    </div>
 
    <!-- Stat cards -->
    <div class="stat-grid">
      <div class="stat-card">
        <span class="stat-icon bg-slate-100 text-slate-500">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </span>
        <div>
          <div class="stat-label">Total Employees</div>
          <div class="stat-value">1,240</div>
        </div>
      </div>
 
      <div class="stat-card">
        <span class="stat-icon bg-blue-50 text-blue-500">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 10h18"/><path d="M8 2v4M16 2v4"/></svg>
        </span>
        <div>
          <div class="stat-label">Total Clients</div>
          <div class="stat-value">48</div>
        </div>
      </div>
 
      <div class="stat-card highlight">
        <span class="stat-icon bg-slate-100 text-slate-600">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 10h18"/><path d="M8 2v4M16 2v4"/></svg>
        </span>
        <div>
          <div class="stat-label">Active Cycle</div>
          <div class="stat-value text-lg">FY 2026-2027</div>
        </div>
      </div>
 
      <div class="stat-card">
        <span class="stat-icon bg-red-50 text-red-500">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h9"/><path d="M2 10h13"/><circle cx="19" cy="17" r="3"/><path d="M19 15.5V17l1 1"/></svg>
        </span>
        <div>
          <div class="stat-label">Pending Appraisals</div>
          <div class="stat-value">156</div>
        </div>
      </div>
 
      <div class="stat-card">
        <span class="stat-icon bg-emerald-50 text-emerald-500">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </span>
        <div>
          <div class="stat-label">Completed</div>
          <div class="stat-value">892</div>
        </div>
      </div>
 
      <div class="stat-card">
        <span class="stat-icon bg-amber-50 text-amber-500">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M6 21v-2a6 6 0 0 1 12 0v2"/></svg>
        </span>
        <div>
          <div class="stat-label">Probation</div>
          <div class="stat-value">42</div>
        </div>
      </div>
 
      <div class="stat-card">
        <span class="stat-icon bg-blue-50 text-blue-500">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15 14"/></svg>
        </span>
        <div>
          <div class="stat-label">Avg Attendance</div>
          <div class="stat-value">94%</div>
        </div>
      </div>
 
      <div class="stat-card">
        <span class="stat-icon bg-emerald-50 text-emerald-500">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
        </span>
        <div>
          <div class="stat-label">Appraisal %</div>
          <div class="stat-value">27%</div>
        </div>
      </div>
    </div>
 
    <!-- Work Session History -->
    <div class="card">
      <div class="card-header">
        <h2 class="card-title">Work Session History</h2>
        <span class="link-btn">View All History</span>
      </div>
      <div class="table-scroll">
        <table class="data-table">
          <thead>
            <tr>
              <th>Date</th><th>Login</th><th>Logout</th><th>Duration</th><th>Status</th><th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Oct 23, 2023</td><td>08:55 AM</td><td>06:12 PM</td><td>9h 17m</td>
              <td><span class="badge badge-neutral">Completed</span></td>
              <td><span class="view-details">View Details</span></td>
            </tr>
            <tr>
              <td>Oct 23, 2023</td><td>08:55 AM</td><td>06:12 PM</td><td>9h 17m</td>
              <td><span class="badge badge-neutral">Completed</span></td>
              <td><span class="view-details">View Details</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
 
    <!-- Attendance & Leave Data Master heading -->
    <div class="flex items-center justify-between flex-wrap gap-3">
      <h2 class="text-lg font-bold text-[#2f3648]">Attendance &amp; Leave Data Master</h2>
      <button class="btn-primary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Import Excel Sheet
      </button>
    </div>
 
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">
      <!-- Employee Leave Balance -->
      <div class="card xl:col-span-2">
        <div class="card-header">
          <h2 class="card-title">Employee Leave Balance</h2>
          <div class="mini-search">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Search employee by name, ID, depatme" />
          </div>
        </div>
        <div class="table-scroll">
          <table class="data-table">
            <thead>
              <tr><th>Employee</th><th>Dept</th><th>Total</th><th>Used</th><th>Remaining</th><th>Status</th></tr>
            </thead>
            <tbody>
              <tr>
                <td class="flex items-center gap-2.5 !border-b-0 pt-4">
                  <span class="row-avatar bg-blue-50 text-blue-500">JD</span> John Doe
                </td>
                <td>Engineering</td><td>24</td><td>08</td>
                <td class="font-bold text-blue-500">16</td>
                <td><span class="badge badge-green">On Track</span></td>
              </tr>
              <tr>
                <td class="flex items-center gap-2.5 !border-b-0 pt-4">
                  <span class="row-avatar bg-amber-50 text-amber-600">SM</span> Sarah Miller
                </td>
                <td>Marketing</td><td>24</td><td>22</td>
                <td class="font-bold text-red-500">02</td>
                <td><span class="badge badge-red">Low Balance</span></td>
              </tr>
              <tr>
                <td class="flex items-center gap-2.5 !border-b-0 pt-4">
                  <span class="row-avatar bg-violet-50 text-violet-500">KP</span> Kevin Patel
                </td>
                <td>Design</td><td>24</td><td>10</td>
                <td class="font-bold text-blue-500">14</td>
                <td><span class="badge badge-green">On Track</span></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="pagination-row">
          <span class="pg-note">Showing 1-10 of 1,240 employees</span>
          <div class="pg-controls">
            <span class="pg-btn">&lsaquo;</span>
            <span class="pg-btn active">1</span>
            <span class="pg-btn">2</span>
            <span class="pg-btn">3</span>
            <span class="pg-btn">…</span>
            <span class="pg-btn">124</span>
            <span class="pg-btn">&rsaquo;</span>
          </div>
        </div>
      </div>
 
      <!-- Excel Upload History -->
      <div class="card flex flex-col gap-4">
        <h2 class="card-title">Excel Upload History</h2>
 
        <div class="upload-item">
          <div>
            <div class="upload-name">Attendance_June</div>
            <div class="upload-meta">Date: 24 Jun, 10:15 AM</div>
            <div class="upload-meta">Records: 1,240</div>
          </div>
          <div class="flex flex-col items-end justify-between h-full gap-3">
            <span class="download-icon">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            </span>
            <div class="text-right upload-meta">
              By: <b>Admin</b><br/>Status: <b class="text-emerald-500">Success</b>
            </div>
          </div>
        </div>
 
        <div class="upload-item">
          <div>
            <div class="upload-name">Attendance_June</div>
            <div class="upload-meta">Date: 24 Jun, 10:15 AM</div>
            <div class="upload-meta">Records: 1,240</div>
          </div>
          <div class="flex flex-col items-end justify-between h-full gap-3">
            <span class="download-icon">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            </span>
            <div class="text-right upload-meta">
              By: <b>Admin</b><br/>Status: <b class="text-emerald-500">Success</b>
            </div>
          </div>
        </div>
 
        <button class="btn-secondary mt-1">View Full History</button>
      </div>
    </div>
 
    <!-- Ongoing Appraisals + Appraisal Progress -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">
      <div class="card xl:col-span-2">
        <div class="card-header">
          <h2 class="card-title">Ongoing Appraisals and employee details</h2>
          <div class="mini-search">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Search employee by name, ID, depatme" />
          </div>
        </div>
        <div class="table-scroll">
          <table class="data-table">
            <thead>
              <tr><th>Employee</th><th>Department</th><th>Manager</th><th>Status</th><th>Due Date</th><th>&nbsp;</th></tr>
            </thead>
            <tbody>
              <tr>
                <td class="flex items-center gap-2.5">
                  <span class="row-avatar bg-slate-100 text-slate-500">AS</span> Arjun Singh
                </td>
                <td>Engineering</td>
                <td>Rajesh K.</td>
                <td><span class="badge badge-blue">In Review</span></td>
                <td>15 July 2026</td>
                <td><span class="view-details">View Details</span></td>
              </tr>
              <tr>
                <td class="flex items-center gap-2.5">
                  <span class="row-avatar bg-slate-100 text-slate-500">DS</span> Sofia Patel
                </td>
                <td>Product Management</td>
                <td>Sara M.</td>
                <td><span class="badge badge-orange">Pending</span></td>
                <td>05 August 2026</td>
                <td><span class="view-details">View Details</span></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="pagination-row">
          <span class="pg-note">Showing 1-10 of 1,240 employees</span>
          <div class="pg-controls">
            <span class="pg-btn">&lsaquo;</span>
            <span class="pg-btn active">1</span>
            <span class="pg-btn">2</span>
            <span class="pg-btn">3</span>
            <span class="pg-btn">…</span>
            <span class="pg-btn">124</span>
            <span class="pg-btn">&rsaquo;</span>
          </div>
        </div>
      </div>
 
      <div class="card">
        <h2 class="card-title mb-5">Appraisal Progress</h2>
 
        <div class="progress-row">
          <div class="progress-label-row"><span class="progress-label">Eligible Employees</span><span class="progress-value">1,200</span></div>
          <div class="progress-track"><div class="progress-fill bg-blue-500" style="width:100%"></div></div>
        </div>
        <div class="progress-row">
          <div class="progress-label-row"><span class="progress-label">Completed</span><span class="progress-value">892</span></div>
          <div class="progress-track"><div class="progress-fill bg-emerald-500" style="width:74%"></div></div>
        </div>
        <div class="progress-row">
          <div class="progress-label-row"><span class="progress-label">In Review</span><span class="progress-value">156</span></div>
          <div class="progress-track"><div class="progress-fill bg-blue-400" style="width:13%"></div></div>
        </div>
        <div class="progress-row">
          <div class="progress-label-row"><span class="progress-label">Pending</span><span class="progress-value">100</span></div>
          <div class="progress-track"><div class="progress-fill bg-amber-400" style="width:8%"></div></div>
        </div>
        <div class="progress-row">
          <div class="progress-label-row"><span class="progress-label">Overdue</span><span class="progress-value text-red-500">52</span></div>
          <div class="progress-track"><div class="progress-fill bg-red-500" style="width:4%"></div></div>
        </div>
      </div>
    </div>
 
    <!-- Attendance Overview / My Leave Summary / Upcoming Events -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 items-start">
 
      <div class="card">
        <h2 class="card-title mb-5">Attendance Overview</h2>
        <div class="flex items-center gap-6">
          <div class="donut w-28 h-28" style="background: conic-gradient(#10b981 0% 94%, #e2e8f0 94% 100%);">
            <div class="donut-inner w-[76px] h-[76px]">
              <span class="text-xl font-bold text-blue-600">94%</span>
              <span class="text-[0.6rem] font-semibold text-slate-400 tracking-wide">PRESENT</span>
            </div>
          </div>
          <div class="flex-1 flex flex-col gap-2 min-w-0">
            <div class="attend-pill bg-emerald-50 text-emerald-600"><span>Present</span><span>1,180</span></div>
            <div class="attend-pill bg-red-50 text-red-500"><span>Absent</span><span>15</span></div>
            <div class="attend-pill bg-orange-50 text-orange-500"><span>Leave</span><span>45</span></div>
            <div class="attend-pill bg-blue-50 text-blue-500"><span>Late</span><span>12</span></div>
          </div>
        </div>
        <div class="mt-5 text-sm font-semibold text-blue-600">19.06.2026 - 17.07.2026</div>
      </div>
 
      <div class="card">
        <h2 class="card-title mb-5">My Leave Summary</h2>
        <div class="flex flex-col gap-5">
          <div class="flex items-center gap-4">
            <div class="donut w-16 h-16" style="background: conic-gradient(#2f3648 0% 75%, #e2e8f0 75% 100%);">
              <div class="donut-inner w-11 h-11">
                <span class="text-sm font-bold text-[#2f3648]">12/16</span>
              </div>
            </div>
            <div>
              <div class="text-sm font-bold text-[#2f3648]">Privilege Leaves</div>
              <div class="text-xs text-slate-400 mt-0.5">4 Days Used</div>
            </div>
          </div>
          <div class="flex items-center gap-4">
            <div class="donut w-16 h-16" style="background: conic-gradient(#ef4444 0% 25%, #e2e8f0 25% 100%);">
              <div class="donut-inner w-11 h-11">
                <span class="text-sm font-bold text-[#2f3648]">2/8</span>
              </div>
            </div>
            <div>
              <div class="text-sm font-bold text-[#2f3648]">Sick Leaves</div>
              <div class="text-xs text-slate-400 mt-0.5">6 Days Available</div>
            </div>
          </div>
        </div>
        <button class="btn-secondary w-full mt-6 !bg-[#eef2fb] !border-transparent !text-blue-600">Apply for Leave</button>
      </div>
 
      <div class="card">
        <div class="card-header !mb-5">
          <h2 class="card-title">Upcoming Events</h2>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-400"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 10h18"/><path d="M8 2v4M16 2v4"/></svg>
        </div>
        <div class="flex flex-col gap-4">
          <div class="event-row">
            <div class="event-date"><span class="text-[0.6rem] font-bold">OCT</span><span class="text-sm font-bold">12</span></div>
            <div><div class="event-title">Dussehra Holiday</div><div class="event-sub">All Offices Closed</div></div>
          </div>
          <div class="event-row">
            <div class="avatar-stack">
              <img src="https://i.pravatar.cc/60?img=32" alt="" />
              <img src="https://i.pravatar.cc/60?img=45" alt="" />
            </div>
            <div><div class="event-title">Birthdays (2)</div><div class="event-sub">Amelia, Rajiv</div></div>
          </div>
          <div class="event-row">
            <div class="event-date !bg-amber-50 !text-amber-500">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.4 6.6L21 11l-6.6 2.4L12 20l-2.4-6.6L3 11l6.6-2.4z"/></svg>
            </div>
            <div><div class="event-title">Work Anniversaries</div><div class="event-sub">5 Employees reaching 2 years</div></div>
          </div>
          <div class="event-row">
            <div class="event-date"><span class="text-[0.6rem] font-bold">OCT</span><span class="text-sm font-bold">12</span></div>
            <div><div class="event-title">Dussehra Holiday</div><div class="event-sub">All Offices Closed</div></div>
          </div>
        </div>
      </div>
    </div>
 
    <!-- Previous Employee Attendance Records -->
    <div class="card">
      <div class="card-header">
        <h2 class="card-title">Previous Employee Attendance Records</h2>
        <span class="link-btn">View Complete Attendance History</span>
      </div>
      <div class="mini-search max-w-[320px] mb-5">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" placeholder="Search employee..." />
      </div>
      <div class="table-scroll">
        <table class="data-table">
          <thead>
            <tr><th>Employee</th><th>Dept</th><th>Date</th><th>Check-in</th><th>Check-out</th><th>Total</th><th>Status</th><th>Action</th></tr>
          </thead>
          <tbody>
            <tr class="bg-[#f7f9fc]">
              <td class="flex items-center gap-2.5">
                <span class="row-avatar bg-slate-100 text-slate-500">AS</span> Arjun Singh
              </td>
              <td>IT</td><td>Oct 23</td><td>09:15 AM</td><td>06:30 PM</td><td>9h 15m</td>
              <td><span class="badge badge-orange">Late</span></td>
              <td><span class="view-details">View Details</span></td>
            </tr>
            <tr>
              <td class="flex items-center gap-2.5">
                <span class="row-avatar bg-slate-100 text-slate-500">AS</span> Arjun Singh
              </td>
              <td>HR</td><td>Oct 23</td><td>08:55 AM</td><td>05:45 PM</td><td>8h 50m</td>
              <td><span class="badge badge-green">Present</span></td>
              <td><span class="view-details">View Details</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
 
  </main> --}}
 
 
<!-- <script>
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebarOverlay');
  const menuBtn = document.getElementById('menuBtn');
  const closeBtn = document.getElementById('sidebarClose');
 
  function openSidebar() {
    sidebar.classList.add('open');
    overlay.classList.add('open');
  }
  function closeSidebar() {
    sidebar.classList.remove('open');
    overlay.classList.remove('open');
  }
 
  menuBtn.addEventListener('click', openSidebar);
  closeBtn.addEventListener('click', closeSidebar);
  overlay.addEventListener('click', closeSidebar);
 
  // Close drawer automatically if resized up to desktop width
  window.addEventListener('resize', () => {
    if (window.innerWidth >= 1024) closeSidebar();
  });
 
  // Close on nav item click (mobile UX)
  document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', () => {
      if (window.innerWidth < 1024) closeSidebar();
    });
  });
</script> -->
@endsection
