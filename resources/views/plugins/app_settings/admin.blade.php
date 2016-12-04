@section('lib_styles')
    <link rel="stylesheet" href="{{ libraryAsset('iCheck/square/blue.css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ libraryAsset('iCheck/icheck.min.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        {!! cdataOpen() !!}
        jQuery(document).ready(function () {
            jQuery('[type=checkbox]').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
        {!! cdataClose() !!}
    </script>
@endsection

<div class="box">
    <div class="box-body">
        <div class="form-group">
            <div class="checkbox icheck">
                <label for="inputRegisterEnable">
                    <input id="inputRegisterEnable" type="checkbox" name="register_enable"
                           value="1"{{ $register_enable ? ' checked' : '' }}>
                    &nbsp; {{ trans('app_settings.register_enable') }}
                </label>
            </div>
        </div>
    </div>
</div>