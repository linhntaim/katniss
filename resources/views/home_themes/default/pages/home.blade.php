@extends('home_themes.default.master.index')
@section('extended_scripts')
    <script>
        {!! cdataOpen() !!}
        function activateConversation(sessionId) {

        }
        jQuery(document).ready(function () {
            var jActiveMessage, jFirstActiveMessage = true;
            jQuery('[data-toggle="message"]').on('click', function (e) {
                e.preventDefault();

                var jThis = jQuery(this);

                if (jActiveMessage) {
                    if (jActiveMessage.attr('data-id') == jThis.attr('data-id')) {
                        jScrollTo('#my-messages');
                        return;
                    }
                    jActiveMessage.removeClass('message-user-selected');
                }

                jActiveMessage = jThis;
                jActiveMessage.addClass('message-user-selected');

                if (!jFirstActiveMessage) {
                    jScrollTo('#my-messages');
                }
                jFirstActiveMessage = false;
                activateConversation(jActiveMessage.attr('data-id'));
            }).first().trigger('click');
        });
        {!! cdataClose() !!}
    </script>
@endsection
@section('extended_styles')
    <style>
        .message-user {
            padding: 5px 7px;
            border: 2px solid #337ab7;
            font-weight: bold
        }
        .message-user:focus,
        .message-user:hover,
        .message-user-selected {
            border-color: #23527c;
            background: #23527c;
            text-decoration: none;
            color: #fff;
        }
    </style>
@endsection
@section('extra_sections')
    <section id="social-sharing" class="even-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">Social Sharing</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <p>{{ isActivatedExtension('social_integration') ? 'Social Integration extension is activated' : 'Social Integration extension is not activated' }}</p>
                    <p>
                        {!! content_place('sharing_buttons', [currentUrl()]) !!}
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section id="facebook-comment" class="odd-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">Facebook Comments</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-1 col-sm-2 col-md-3"></div>
                <div class="col-xs-10 col-sm-8 col-md-6">
                    <p>{{ isActivatedExtension('social_integration') ? 'Social Integration extension is activated' : 'Social Integration extension is not activated' }}</p>
                    {!! content_place('facebook_comment', [currentUrl()]) !!}
                </div>
                <div class="col-xs-1 col-sm-2 col-md-3"></div>
            </div>
        </div>
    </section>
    <section id="example-widgets" class="even-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">Example Widgets</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-1"></div>
                <div class="col-xs-10">
                    {!! placeholder('default_placeholder', KATNISS_EMPTY_STRING, KATNISS_EMPTY_STRING, 'No widget is placed here') !!}
                </div>
                <div class="col-xs-1"></div>
            </div>
        </div>
    </section>
    <section id="my-settings" class="odd-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">My Settings</h1>
                    <p><strong>Country</strong><br>{{ $country }}</p>
                    <p><strong>Locale</strong><br>{{ $locale }}</p>
                    <p><strong>Timezone</strong><br>{{ $timezone }}</p>
                    <p><strong>Currency + Number Format</strong><br>{{ $price }}</p>
                    <p><strong>Long Date Time</strong><br>{{ $long_datetime }}</p>
                    <p><strong>Short Date Time</strong><br>{{ $short_datetime }}</p>
                    <p>
                        <a href="{{ homeUrl('my-settings') }}">{{ trans('form.action_go_to') }} {{ trans('pages.my_settings_title') }}</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section id="my-conversations" class="even-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">List of Users</h1>
                    <ul class="list-unstyled list-inline">
                        @foreach($user_sessions as $user_session)
                            @if($user_session->isGuest())
                                @if($user_session->ip_address != clientIp())
                                    <li>
                                        <a class="message-user" href="#" data-toggle="message"
                                           data-id="{{ $user_session->id }}">Anonymous #{{ $user_session->id }}</a>
                                    </li>
                                @endif
                            @else
                                <?php
                                $user = $user_session->user;
                                ?>
                                @if(!$is_auth || $user->id != $auth_user->id)
                                    <li>
                                        <a class="message-user" href="#" data-toggle="message"
                                           data-id="{{ $user_session->id }}">{{ $user->display_name }}</a>
                                    </li>
                                @endif
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section id="my-messages" class="odd-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">Conversation</h1>
                    <div class="message-holder">
                        <img src="{{ HomeTheme::imageAsset('loading.gif') }}">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection