@section('lib_styles')
    <link rel="stylesheet" href="{{ libraryAsset('iCheck/square/blue.css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ libraryAsset('iCheck/icheck.min.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            $('[type=checkbox]').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
@endsection

<div class="box">
    <div class="box-body">
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <h5>Custom chatbox service</h5>
                <div class="form-group">
                    <div class="checkbox icheck">
                        <label for="inputChatboxEnable">
                            <input id="inputchatEnable" type="checkbox" name="chatbox_enable" value="1"{{ $chatbox_enable ? ' checked' : '' }}>
                            &nbsp; {{ trans('chatbox_services.enable') }}
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="chatbox_id">Chatbox ID</label>
                    <input id="chatbox_id" type="text" class="form-control" name="chatbox_id" value="{{ $chatbox_id }}" placeholder="{{ trans('chatbox_services.tawkto_chatbox_id') }}">
                </div>

            </div>
            <div class="col-xs-12 col-md-4">
                <h5>Custom Chatbox Script</h5>
                <div class="form-group">
                    <textarea class="form-control" rows="4" id="custom_chatbox" name="custom_chatbox" placeholder="{{ trans('chatbox_services.place_holder_custom_chatbox_area') }}">{{ $custom_chatbox }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="checkbox icheck">
        <label for="inputCacheEnable">
            <input id="inputCacheEnable" type="checkbox" name="cache_enable" value="1"{{ $cache_enable ? ' checked' : '' }}>
            &nbsp; {{ trans('chatbox_services.cache_enable') }}
        </label>
    </div>
    <div class="help-block">{{ trans('chatbox_services.cache_enable_help') }}</div>
</div>
