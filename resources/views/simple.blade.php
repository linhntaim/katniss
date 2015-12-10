<!DOCTYPE html>
<html lang="{{ $site_locale }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $site_name }}</title>
    <meta name="description" content="{{ $site_description }}">
    @include('fav_icons')
    @yield('lib_styles')
    @yield('extended_styles')
</head>
<body>
@yield('body')
@yield('lib_scripts')
@yield('extended_scripts')
</body>
</html>