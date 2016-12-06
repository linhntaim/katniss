<!DOCTYPE html>
<html lang="{{ $site_locale }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    @yield('site_meta')
    <title>{!! theme_title() !!}</title>
    <meta name="description" content="{!! theme_description() !!}">
    <meta name="author" content="{{ theme_author() }}">
    <meta name="keywords" content="{{ theme_keywords() }}">
    @include('fav_icons')
    {!! lib_styles() !!}
    @yield('lib_styles')
    {!! ext_styles() !!}
    <link rel="stylesheet" href="{{ libraryAsset('iCheck/square/blue.css') }}">
    @yield('extended_styles')
    {!! theme_header() !!}
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition @yield('auth_type')-page">
<div class="@yield('auth_type')-box">
    <div class="@yield('auth_type')-logo">
        <a href="{{ homeUrl() }}"><strong>{{ $site_name }}</strong></a>
    </div>
    <div class="@yield('auth_type')-box-body">
        <p class="@yield('auth_type')-box-msg">@yield('box_message')</p>
        @yield('auth_form')
    </div>
</div>
{!! lib_scripts() !!}
<script src="{{ libraryAsset('iCheck/icheck.min.js') }}"></script>
@yield('lib_scripts')
<script>
    {!! cdataOpen() !!}
    $(function () {
        $('[type=checkbox]').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
    {!! cdataClose() !!}
</script>
{!! ext_scripts() !!}
@yield('extended_scripts')
@include('admin_themes.admin_lte.master.common_modals')
@yield('modals')
{!! theme_footer() !!}
</body>
</html>
