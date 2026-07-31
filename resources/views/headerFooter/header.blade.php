<meta charset="UTF-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge">

<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="author" content="Delostyle Studio">

<meta name="description" content="Evalon HR Management System">

<meta name="keywords" content="HRMS, Employee Management, Evaluation, Dashboard">

<meta name="theme-color" content="#2563eb">

<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

<link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">



{{-- Bootstrap Icons --}}

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">



{{-- Overlay Scrollbars CSS (keep if sidebar uses it later) --}}

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars/styles/overlayscrollbars.min.css">



{{-- Global Project Styles --}}

<link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
