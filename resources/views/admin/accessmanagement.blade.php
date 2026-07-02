@extends('layouts.app')

@section('title', 'Access Management')
@section('breadcrumb', 'Access Management')
@section('page-title', 'Access Management')
@section('body-class', 'special-page access-management-page')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

    <link href="{{ asset('css/access-management.css') }}?v={{ filemtime(public_path('css/access-management.css')) }}" rel="stylesheet">

    @php
        $permissionCount = $modules->sum(function ($module) {
            return $module->children->count() ?: 1;
        });
    @endphp

    <div class="access-shell">
        <div class="access-toolbar">
            <div>
                <a href="{{ route('setting-view') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    <span>Back to Settings</span>
                </a>
                <div class="access-title-block mt-3">
                    <h1>Access Management</h1>
                    <p>Assign sidebar modules and their related routes to admins, HRs, and managers.</p>
                </div>
            </div>

            <button class="access-save-btn" id="savePermission" type="button">
                <i class="bi bi-check2-circle"></i>
                <span>Save Permissions</span>
            </button>
        </div>

        <div class="access-layout">
            <section class="access-panel">
                <div class="access-panel-header">
                    <div>
                        <h2 class="access-panel-title">Permission Assignment</h2>
                        <p class="access-panel-subtitle">Choose a role, select one or more users, then assign allowed modules.</p>
                    </div>
                </div>

                <div class="access-panel-body">
                    <div class="access-form-grid">
                        <div class="access-field">
                            <label for="role">Role</label>
                            <select id="role" class="form-select">
                                <option value="">Select role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->user_type }}">
                                        {{ ucfirst($role->user_type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="access-field">
                            <label for="selected_users">Users</label>
                            <select id="selected_users" class="form-control" multiple></select>
                        </div>
                    </div>

                    <div id="permissionNotice" class="access-notice" role="status" aria-live="polite"></div>

                    <div class="access-divider"></div>

                    <div class="permission-actions">
                        <label class="select-all-box" for="selectAll">
                            <input type="checkbox" id="selectAll" class="form-check-input">
                            <span>Select all permissions</span>
                        </label>
                        <span class="permission-count-note">{{ $permissionCount }} permission{{ $permissionCount === 1 ? '' : 's' }} available</span>
                    </div>

                    <div class="permission-list" id="permissionAccordion">
                        @foreach ($modules as $parent)
                            @php
                                $hasChildren = $parent->children->count() > 0;
                                $itemCount = $hasChildren ? $parent->children->count() : 1;
                            @endphp

                            <div class="permission-module">
                                <div class="permission-module-header">
                                    @if ($hasChildren)
                                        <input type="checkbox" class="form-check-input parent-checkbox" data-parent="{{ $parent->id }}" aria-label="Select {{ $parent->module_name }} permissions">
                                    @else
                                        <input class="form-check-input single-checkbox" value="{{ $parent->id }}" id="module_{{ $parent->id }}" type="checkbox" aria-label="Select {{ $parent->module_name }}">
                                    @endif

                                    <div>
                                        <div class="permission-module-name">{{ $parent->module_name }}</div>
                                        <div class="permission-module-meta">{{ $itemCount }} permission{{ $itemCount === 1 ? '' : 's' }}</div>
                                    </div>

                                    @if ($hasChildren)
                                        <button class="permission-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#module{{ $parent->id }}" aria-expanded="false" aria-controls="module{{ $parent->id }}">
                                            <i class="bi bi-chevron-down"></i>
                                        </button>
                                    @endif
                                </div>

                                @if ($hasChildren)
                                    <div id="module{{ $parent->id }}" class="collapse">
                                        <div class="permission-module-body">
                                            <div class="permission-options-grid">
                                                @foreach ($parent->children as $child)
                                                    <div class="permission-option form-check">
                                                        <input class="form-check-input child-checkbox" type="checkbox" value="{{ $child->id }}" id="module_{{ $child->id }}" data-parent="{{ $parent->id }}">
                                                        <label class="form-check-label" for="module_{{ $child->id }}">{{ $child->module_name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <aside class="access-panel assignment-panel">
                <div class="access-panel-header">
                    <div>
                        <h2 class="access-panel-title">Assignment Summary</h2>
                        <p class="access-panel-subtitle">Review the current selection before saving.</p>
                    </div>
                </div>

                <div class="access-panel-body">
                    <div class="summary-row">
                        <span class="summary-label">Selected user</span>
                        <h3 class="summary-value" id="summaryUser">--</h3>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Role</span>
                        <h3 class="summary-value" id="summaryRole">--</h3>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Assigned access</span>
                        <div id="permissionSummary" class="permission-summary-list">
                            <div class="empty-summary">Select one user</div>
                        </div>
                    </div>

                    <div class="summary-total">
                        <span>Total permissions</span>
                        <span class="badge bg-success" id="permissionCount">0</span>
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        window.ACCESS_PERMISSION = {
            saveUrl: "{{ route('access.permission.save') }}",
            getUsersUrl: "{{ url('/access-management/get-users') }}",
            getPermissionUrl: "{{ url('/access-management/user-permission') }}",
            csrfToken: "{{ csrf_token() }}"
        };
    </script>

    <script src="{{ asset('js/access-management.js') }}?v={{ filemtime(public_path('js/access-management.js')) }}"></script>
@endsection