<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $description }}">
    <meta name="author" content="{{ $author }}">
	<title>
        @yield('title_prefix', config('adminlte.title_prefix', ''))
        @yield('title', config('adminlte.title', 'AdminLTE 3'))
        @yield('title_postfix', config('adminlte.title_postfix', ''))
    </title>
    {{-- Default CSS --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="{!! asset('vendor/fontawesome-free/css/all.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('assets/template/base.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('assets/template/colors.css') !!}">
    {{-- Custom CSS from system --}}
    @if (isset($template->custom_css))
        <style>
            {{ $template->custom_css }}
        </style>
    @endif
</head>
<body>
	<div class="wrapper">
        {!! $content !!}
	</div>
<!-- jQuery -->
<script src="{!! asset('vendor/jquery/jquery.min.js') !!}"></script>
<!-- Bootstrap 4 -->
<script src="{!! asset('vendor/bootstrap/js/bootstrap.bundle.min.js') !!}"></script>
</body></html> 