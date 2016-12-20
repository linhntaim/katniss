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
    <link rel="stylesheet" href="{{ _kExternalLink('bootstrap-css') }}">
    <link rel="stylesheet" href="{{ libraryAsset('katniss.conversation.css') }}">
    <!--[if lt IE 9]>
    <script src="{{ _kExternalLink('html5shiv') }}"></script>
    <script src="{{ _kExternalLink('respond') }}"></script>
    <![endif]-->
</head>
<body>
<header>
    <div class="wrapper">
        <div id="user-boxes">
            <div class="scrolling-box">
                <div class="users clearfix">
                    @foreach($conversation_devices as $device)
                        <div class="square-box device" style="background:#{{ $device->pivot->color }}" title="{{ trans('label.anonymous') }}-{{ $device->id }}"></div>
                    @endforeach
                </div>
            </div>
            <button class="btn btn-default btn-block no-border no-border-radius no-focus-outline
            scrolling-control pull-left hide">&laquo;</button>
            <button class="btn btn-default btn-block no-border no-border-radius no-focus-outline
            scrolling-control pull-right hide">&raquo;</button>
        </div>
        <hr>
    </div>
</header>
<main>
    <div class="wrapper">
        <div id="messages">
            <div id="clearfix-0" class="clearfix"></div>
        </div>
    </div>
</main>
<footer>
    <div class="wrapper">
        <hr>
        <div id="message">
                <textarea id="inputMessage" class="form-control no-border no-border-radius no-resize no-focus-outline"
                          name="inputMessage" placeholder="Type a message (maximum of 140 characters)..." rows="1" cols="10"
                          maxlength="255" required></textarea>
        </div>
        <div id="actions">
            <label for="inputEnter" class="sr-only">{{ trans_choice('label.message', 1) }}</label>
            <input id="inputEnter" name="inputEnter" type="checkbox">
            <button type="button" id="buttonSend"
                    class="btn btn-default btn-block no-border no-border-radius no-focus-outline">
                {{ trans('form.action_send') }}
            </button>
        </div>
        <div class="clearfix"></div>
    </div>
</footer>
<script src="{{ _kExternalLink('jquery') }}"></script>
<script src="{{ _kExternalLink('bootstrap') }}"></script>
<script src="{{ _kExternalLink('realtime-co-ortc') }}"></script>
{!! extScripts() !!}
<script>
    var ORTC_SERVER = '{{ env('ORTC_SERVER') }}';
    var ORTC_CLIENT_ID = '{{ session()->getId() }}';
    var ORTC_CLIENT_KEY = '{{ env('ORTC_CLIENT_KEY') }}';
    var ORTC_CLIENT_SECRET = '{{ env('ORTC_CLIENT_SECRET') }}';

    var CONVERSATION_ID = '{{ $conversation->id }}';
    var CONVERSATION_CHANNEL = '{{ $conversation->channel->code }}';
    var CURRENT_DEVICE_ID = '{{ deviceId() }}';
</script>
{!! $js_queue !!}
<script src="{{ libraryAsset('katniss.conversation.js') }}"></script>
</body>
</html>