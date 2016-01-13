<script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button); // Resolve conflict in jQuery UI tooltip with Bootstrap tooltip
</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="{{ libraryAsset('slimScroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ libraryAsset('fastclick/fastclick.min.js') }}"></script>
<script src="{{ AdminTheme::jsAsset('app.min.js') }}"></script>
@yield('lib_scripts')
<script>
    var THEME_PATH = '{{ AdminTheme::asset() }}/';
    var AJAX_REQUEST_TOKEN = '{{ csrf_token() }}';
    var SETTINGS_NUMBER_FORMAT = '{{ Settings::getNumberFormat() }}';
</script>
<script src="{{ libraryAsset('katniss.js') }}"></script>
@yield('extended_scripts')
{!! theme_footer() !!}