<!DOCTYPE html>
<html lang="en">

<head>

    @include('headerFooter.header')

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Dashboard')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />


    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- App CSS -->
    <link href="{{ asset('css/evaluation-form.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}?v={{ time() }}" rel="stylesheet">

    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">

</head>

<body class="@yield('body-class') layout-fixed sidebar-expand-lg bg-body-tertiary">

    <div class="app-wrapper">

        @include('headerFooter.sidebar')

        <main class="app-main">

            <div class="app-content-header">

                <div class="container">

                    <div class="hamburger__wrapper">

                        <div class="hamburger">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>

                    </div>

                    <div class="d-flex app-main__div">

                        <ol class="breadcrumb float-start" style="--bs-breadcrumb-divider:'>';">
                            <li class="breadcrumb-item">
                                <u>{{ isset($title) ? trim(explode('|',$title)[1]) : 'Dashboard' }}</u>
                            </li>

                            <li class="breadcrumb-item active">
                                @yield('breadcrumb')
                            </li>

                        </ol>

                    </div>

                </div>

            </div>

            <div class="container">

                @yield('content')

            </div>

        </main>

        @include('headerFooter.footer')

    </div>

    <!-- ========================= -->
    <!-- JS -->
    <!-- ========================= -->

    <!-- jQuery FIRST -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Check -->
    <script>
        console.log("jQuery :", typeof jQuery);
console.log("$ :", typeof $);
console.log("Select2 :", $.fn.select2);
    </script>

    {{-- Page Specific Script --}}
    @yield('script')

    <script>
        document.addEventListener("DOMContentLoaded", function () {

    var isGuest = {{ session()->has('user_email') ? 'false' : 'true' }};

    if (isGuest) {

        history.pushState(null, null, location.href);

        window.onpopstate = function () {
            history.pushState(null, null, location.href);
        };

        window.onpageshow = function (event) {

            if (event.persisted) {
                window.location.replace('/');
            }

        };

    }

});
    </script>

</body>

</html>