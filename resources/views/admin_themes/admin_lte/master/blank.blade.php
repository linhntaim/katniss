<!DOCTYPE html>
<html lang="{{ $site_locale }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('site_meta')
    <title>{!! theme_title() !!}</title>
    <meta name="description" content="{!! theme_description() !!}">
    <meta name="author" content="{{ theme_author() }}">
    <meta name="keywords" content="{{ theme_keywords() }}">
    @include('fav_icons')
    {!! lib_styles() !!}
    @yield('lib_styles')
    {!! ext_styles() !!}
    @yield('extended_styles')
    {!! theme_header() !!}
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition lockscreen">
<div class="lockscreen-wrapper">
    <div class="lockscreen-logo">
        <a href="{{ homeUrl() }}"><strong>{{ $site_name }}</strong></a>
    </div>
    <div class="lockscreen-name">@yield('page_name')</div>
    <div class="lockscreen-item">
        @yield('page_content')
    </div>
</div>
{!! lib_scripts() !!}
@yield('lib_scripts')
{!! ext_scripts() !!}
@yield('extended_scripts')
@yield('modals')
{!! theme_footer() !!}
</body>
</html>
