<aside class="control-sidebar control-sidebar-dark">
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
        <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
        <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="control-sidebar-home-tab">
            <h3 class="control-sidebar-heading"></h3>
            <ul class="control-sidebar-menu">
                <li>
                </li>
            </ul>
        </div>
        <div class="tab-pane" id="control-sidebar-settings-tab">
            <form method="post">
                <h3 class="control-sidebar-heading">{{ trans('label.settings') }}</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="{{ meUrl('settings') }}">{{ trans('pages.my_settings_title') }}</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
</aside>
<div class="control-sidebar-bg"></div>