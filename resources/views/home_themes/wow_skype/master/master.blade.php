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
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-menu">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="{{ homeUrl() }}"><img class="logo" src="{{ themeImageAsset('logo.png') }}"></a>
                </div>
                <div class="collapse navbar-collapse" id="main-menu">
                    {{ $main_menu }}
                    <div class="navbar-right">
                        <div class="switch-locale text-right">
                            @foreach(allSupportedLocaleCodes() as $localeCode)
                                <a class="btn {{ $localeCode == $site_locale ? 'btn-success' : 'btn-default' }}" href="{{ currentUrl($localeCode) }}">
                                    {{ $localeCode }}
                                </a>
                            @endforeach
                        </div>
                        @if($is_auth)
                            <div class="user-action margin-top-10 margin-bottom-10 clearfix">
                                <div class="dropdown pull-right">
                                    <a class="dropdown-toggle" data-toggle="dropdown">
                                        <img class="img-circle width-40" src="{{ $auth_user->url_avatar_thumb }}">
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                        @if($auth_user->can('access-admin'))
                                            <li><a href="{{ adminUrl() }}">{{ trans('form.action_go_to') }} {{ trans('pages.admin_title') }}</a></li>
                                        @endif
                                        <li><a href="{{ homeUrl('profile/account-information') }}">{{ trans('label.my_profile') }}</a></li>
                                        @if($auth_user->hasRole(['teacher', 'student', 'supporter']))
                                            <li><a href="{{ homeUrl('opening-classrooms') }}">{{ trans('label.my_classrooms') }}</a></li>
                                        @endif
                                        <li role="separator" class="divider"></li>
                                        <li><a href="{{ homeUrl('auth/logout') }}">{{ trans('form.action_logout') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                        @else
                            <div class="login-action">
                                <a class="btn btn-primary btn-block"
                                   href="{{ homeUrl('auth/login') }}">{{ trans('form.action_login') }}</a>
                            </div>
                            <div class="sign-up-action text-center">
                                <a href="{{ homeUrl('user/sign-up') }}">{!! trans('label.or_sign_up_here') !!}</a>
                            </div>
                        @endif
                    </div>
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
@include('home_themes.wow_skype.master.common_modals')
@yield('modals')
{!! themeFooter() !!}
</body>
</html>