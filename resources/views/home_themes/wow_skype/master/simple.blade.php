<!DOCTYPE html>
<html lang="{{ $site_locale }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    @yield('site_meta')
    <title>{!! themeTitle() !!}</title>
    <meta name="description" content="{!! themeDescription() !!}">
    <meta name="keywords" content="{!! themeKeywords() !!}">
    <meta name="author" content="{!! themeAuthor() !!}">
    <meta name="application-name" content="{!! themeApplicationName() !!}">
    @include('fav_icons')
    {!! libStyles() !!}
    @yield('lib_styles')
    {!! extStyles() !!}
    @yield('extended_styles')
    {!! themeHeader() !!}
    <!--[if lt IE 9]>
    <script src="{{ _kExternalLink('html5shiv') }}"></script>
    <script src="{{ _kExternalLink('respond') }}"></script>
    <![endif]-->
</head>
<body>
<header>
    <div class="wrapper">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="{{ homeUrl() }}"><img class="logo" src="{{ themeImageAsset('logo.png') }}"></a>
                </div>
            </div>
        </nav>
    </div>
    <div class="bars clearfix">
        <div class="bar bg-master bar-50 pull-left"></div>
        <div class="bar bg-slave bar-50 pull-right"></div>
    </div>
    <div class="bars clearfix">
        <div class="wrapper">
            <div class="bar bg-master bar-75 pull-left"></div>
            <div class="bar bg-slave bar-25 pull-right"></div>
        </div>
    </div>
</header>
<main>
    <div class="wrapper">
        @yield('main_content')
    </div>
</main>
<footer>
    <div class="wrapper">
    </div>
</footer>
{!! libScripts() !!}
@yield('lib_scripts')
{!! extScripts() !!}
@yield('extended_scripts')
@include('admin_themes.admin_lte.master.common_modals')
@yield('modals')
{!! themeFooter() !!}
</body>
</html>