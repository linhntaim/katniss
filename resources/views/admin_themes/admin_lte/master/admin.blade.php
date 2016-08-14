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
    <meta name="application-name" content="{{ theme_application_name() }}">
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
{!! lib_scripts() !!}
@yield('lib_scripts')
{!! ext_scripts() !!}
@yield('extended_scripts')
{!! theme_footer() !!}
</body>
</html>