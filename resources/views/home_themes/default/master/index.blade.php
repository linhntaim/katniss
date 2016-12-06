<!DOCTYPE html>
<html lang="{{ $site_locale }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{!! theme_title() !!}</title>
    <meta name="author" content="{{ theme_author() }}">
    <meta name="description" content="{!! theme_description() !!}}">
    <meta name="keywords" content="{!! theme_keywords() !!}">
    <meta name="application-name" content="{!! theme_application_name() !!}">
    @include('fav_icons')
    {!! lib_styles() !!}
    @yield('lib_styles')
    {!! ext_styles() !!}
    @yield('extended_styles')
    {!! theme_header() !!}
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">{{ trans('label.toggle_navigation') }}</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand page-scroll" href="{{ homeUrl() }}">{{ $site_name }}</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            {{ $main_menu }}
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>
@yield('extra_sections')
<footer>
    <p><a href="{{ homeUrl() }}">{{ $site_name }}</a> &copy; {{ date('Y') }}. Based on <a href="https://laravel.com/" rel="nofollow">Laravel</a>.</p>
</footer>
@yield('modals')
{!! lib_scripts() !!}
@yield('lib_scripts')
{!! ext_scripts() !!}
@yield('extended_scripts')
{!! theme_footer() !!}
</body>
</html>