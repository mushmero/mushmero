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

    <!-- The Stylesheet -->
    <link rel="stylesheet" href="{!! asset('vendor/adminlte/dist/css/adminlte.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('welcome/welcome.css') !!}">
    <link rel="stylesheet" href="{!! asset('vendor/fontawesome-free/css/all.min.css') !!}">

    {{-- @yield ('additional_header') --}}
</head>
<body>
    @yield('content')
    @yield('footer')

</body>
</html>