<header class="main-header">
    <a href="{{ homeURL() }}" class="logo">
        <span class="logo-mini">{{ $site_short_name }}</span>
        <span class="logo-lg">{{ $site_name }}</span>
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning hidden" id="notification-count">0</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            {!! $notification_menu !!}
                        </li>
                        <li class="footer">
                            <a href="{{ homeUrl('notification') }}">
                                {{ trans('form.action_view_all') }}
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ $auth_user->url_avatar_thumb }}" class="user-image" alt="{{ trans('label.profile_picture') }}">
                        <span class="hidden-xs">{{ $auth_user->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="{{ $auth_user->url_avatar_thumb }}" class="img-circle" alt="{{ trans('label.profile_picture') }}">
                            <p>
                                {{ $auth_user->name }}
                                <small>{{ trans('label._member_since', ['time' => $auth_user->memberSince]) }}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-right">
                                <a href="{{ homeUrl('auth/logout') }}" class="btn btn-default btn-flat">
                                    {{ trans('form.action_logout') }}
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            {!! $admin_menu !!}
        </ul>
    </section>
</aside>