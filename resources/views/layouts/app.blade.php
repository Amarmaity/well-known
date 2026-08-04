<!DOCTYPE html>
<html lang="en">

<head>

    @include('headerFooter.header')

    <title>@yield('title', 'Evalon Dashboard')</title>

    <!-- Bootstrap 5.3.7 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

    <!-- Project CSS -->
    <link href="{{ asset('css/evaluation-form.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}?v={{ time() }}" rel="stylesheet">

    <!-- Cache -->
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="Sat, 01 Jan 2000 00:00:00 GMT">

    {{-- Page Specific CSS --}}
    @stack('styles')

</head>

<body class="@yield('body-class') layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        {{-- Sidebar --}}
        @include('headerFooter.sidebar')
        <main class="app-main">
            {{-- Header --}}
            <div class="app-content-header">
                <div class="container">
                    <div class="hamburger__wrapper">
                        <div class="hamburger">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <div class="topbar">
                        <div class="topbar-left">
                            <div class="user-chip">
                                <img class="user-avatar"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(($loggedInUser->first_name ?? 'User') . ' ' . ($loggedInUser->last_name ?? '')) }}&background=2563eb&color=ffffff&bold=true&size=80"
                                    alt="{{ $loggedInUser->first_name ?? 'User' }}">

                                <div class="user-text">
                                    <div class="user-name">
                                        <span class="name-first">{{ $loggedInUser->first_name ?? 'User' }}</span>

                                        @if (!empty($loggedInUser->last_name))
                                            <span class="name-last">{{ $loggedInUser->last_name }}</span>
                                        @endif
                                    </div>

                                    <div class="user-role">
                                        {{ ucfirst($loggedInUser->user_type ?? 'User') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="topbar-right">
                            <button class="icon-btn" aria-label="Logout" title="Logout"
                                onclick="event.preventDefault(); confirmLogout();">

                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">

                                    <path d="M12 2v8" />
                                    <path d="M7.05 5.05A9 9 0 1 0 16.95 5.05" />

                                </svg>

                            </button>

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
                        </div>
                    </div>
                </div>
            </div>
            {{-- Page Content --}}
            <div class="container">
                @yield('content')
            </div>
        </main>
        {{-- Footer --}}
        @include('headerFooter.footer')

    </div>

    {{-- Bootstrap Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars/browser/overlayscrollbars.browser.es6.min.js"></script>

    <script>
        const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
        document.addEventListener("DOMContentLoaded", () => {
            const sidebar = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (
                sidebar &&
                typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== "undefined"
            ) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebar, {
                    scrollbars: {
                        theme: "os-theme-light",
                        autoHide: "leave",
                        clickScroll: true
                    }
                });
            }
        });
    </script>
    {{-- Page Scripts --}}
    @yield('script')

    @stack('scripts')

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const isGuest = {{ session()->has('user_email') ? 'false' : 'true' }};

            if (isGuest) {

                history.pushState(null, null, location.href);

                window.onpopstate = function () {

                    history.pushState(null, null, location.href);

                };

                window.onpageshow = function (event) {

                    if (
                        event.persisted ||
                        (
                            window.performance &&
                            window.performance.navigation.type === 2
                        )
                    ) {

                        window.location.href = '/';

                    } else {

                        window.location.replace('/');

                    }

                };

            }

        });
    </script>

</body>

</html>