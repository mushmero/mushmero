<!DOCTYPE html>
<html lang="en">
<head>
    <title>
        @yield('title', config('adminlte.title', 'AdminLTE 3'))
    </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $description }}" />
    <meta name="author" content="{{ $author }}" />

    <link rel="shortcut icon" href="{!! asset('favicon.ico') !!}">

    <!-- Google Fonts for Modern Full-Stack Tech Aesthetic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- The Stylesheet -->
    <link rel="stylesheet" href="{!! asset('vendor/adminlte/dist/css/adminlte.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('vendor/fontawesome-free/css/all.min.css') !!}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{!! asset('vendor/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('welcome/welcome.css') . '?v=' . filemtime(public_path('welcome/welcome.css')) !!}">

    {{-- @yield ('additional_header') --}}
</head>
<body class="tech-dark-body">
    @yield('content')
    @yield('footer')
    <script src="{!! asset('vendor/jquery/jquery.min.js') !!}"></script>
    <script src="{!! asset('vendor/moment/moment-with-locales.min.js') !!}"></script>
    <script src="{!! asset('vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') !!}"></script>
    <script src="{!! asset('vendor/bootstrap/js/bootstrap.bundle.min.js') !!}"></script>
    <script src="{!! asset('vendor/sweetalert2/sweetalert2.all.min.js') !!}"></script>
    <script src="{!! asset('welcome/welcome.js') . '?v=' . filemtime(public_path('welcome/welcome.js')) !!}"></script>

</body>
</html>
