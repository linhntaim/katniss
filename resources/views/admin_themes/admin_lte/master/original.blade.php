<!DOCTYPE html>
<html lang="{{ $site_locale }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ theme_title() }}</title>
    <meta name="author" content="{{ theme_author() }}">
    <meta name="description" content="{{ theme_description() }}">
    <meta name="keywords" content="{{ theme_keywords() }}">
    @include('favicons')
    <link rel='stylesheet' href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400italic,600,600italic,700,700italic,300italic,300&subset=latin,vietnamese,latin-ext">
    <link rel="stylesheet" href="{{ libraryAsset('common_libs/css/full.min.css') }}">
    @yield('lib_styles')
    <link rel="stylesheet" href="{{ AdminTheme::cssAsset('style.min.css') }}">
    <link rel="stylesheet" href="{{ AdminTheme::cssAsset('skins/skin-blue.min.css') }}">
    <link rel="stylesheet" href="{{ AdminTheme::cssAsset('extra.css') }}">
    @yield('extended_styles')
    {!! theme_header() !!}
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    @include('admin_themes.admin_lte.master.admin_menu')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                @yield('page_title')
                <small>@yield('page_description')</small>
            </h1>
            @yield('page_breadcrumb')
        </section>
        <section class="content">
            @yield('page_content')
        </section>
    </div>
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            {{ $site_description }}
        </div>
        <strong>Copyright &copy; {{ date('Y') }} <a href="{{ $site_home_url }}">{{ $site_name }} {{ $site_version }}</a></strong>
    </footer>
    @include('admin_themes.admin_lte.master.admin_control_sidebar')
</div>
@yield('modals')
@include('admin_themes.admin_lte.master.admin_scripts')
</body>
</html>