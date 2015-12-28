<div id="notification-alert-holder"></div>
<script id="notification-template" type="text/x-handlebars-template">
    <li id="notification-@{{ id }}" class="unread">
        <a href="@{{ url }}">
            <h4>@{{{ message }}}</h4>
            <p>
                <small>
                    <i class="fa fa-clock-o"></i>
                    <span class="time-ago" title="@{{ time_tz }}">
                        @{{ time }}
                    </span>
                </small>
            </p>
        </a>
    </li>
</script>
<script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button); // Resolve conflict in jQuery UI tooltip with Bootstrap tooltip
</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="{{ libraryAsset('slimScroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ libraryAsset('fastclick/fastclick.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.min.js"></script>
<script src="{{ libraryAsset('jquery.timeago-1.4.3/jquery.timeago.js') }}"></script>
<script src="{{ libraryAsset('jquery.timeago-1.4.3/locales/jquery.timeago.' . $site_locale . '.js') }}"></script>
<script src="{{ libraryAsset('ortc.js') }}"></script>
<script src="{{ AdminTheme::jsAsset('app.min.js') }}"></script>
@yield('lib_scripts')
<script>
    {!! cdataOpen() !!}
    var THEME_PATH = '{{ AdminTheme::asset() }}/';
    var AJAX_REQUEST_TOKEN = '{{ csrf_token() }}';
    var SETTINGS_NUMBER_FORMAT = '{{ Settings::getNumberFormat() }}';
    var ORTC_SERVER = '{{ appOrtcServer() }}';
    var ORTC_CLIENT_ID = '{{ appOrtcClientToken() }}';
    var ORTC_CLIENT_KEY = '{{ appOrtcClientKey() }}';
    var ORTC_CLIENT_SECRET = '{{ appOrtcClientSecret() }}';
    {!! cdataClose() !!}
</script>
<script src="{{ libraryAsset('katniss.js') }}"></script>
<script>
    {!! cdataOpen() !!}
    var pushClient = new PushClient(ORTC_SERVER, ORTC_CLIENT_ID, ORTC_CLIENT_KEY, ORTC_CLIENT_SECRET);
    var userNotification;
    jQuery(document).ready(function () {
        userNotification = new NotificationCenter(
                Handlebars.compile(jQuery("#notification-template").html()),
                new NotificationCount({{ $auth_user->notifications()->unread()->count() }}),
                8
        );
        pushClient.subscribe('{{ $auth_user->notification_channel }}', function (client, channel, message) {
            var data = jQuery.parseJSON(message);
            userNotification.render(data);
            jQuery('body').trigger(jQuery.Event('notification_raised'), [data]);
        });
    });
    {!! cdataClose() !!}
</script>
@yield('extended_scripts')
{!! theme_footer() !!}
<script>
    {!! cdataOpen() !!}
    jQuery(document).ready(function () {
        jQuery('.time-ago').timeago();
        pushClient.register();
    });
    {!! cdataClose() !!}
</script>