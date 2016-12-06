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
    <section id="intro" class="odd-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">{{ $site_name }}</h1>
                    @if(!$is_auth)
                        <p>
                            <a href="{{ homeUrl('auth/login') }}">{{ trans('form.action_login') }}</a>
                        </p>
                    @else
                        <p>
                            {{ trans('label._hi', ['name' => $auth_user->display_name]) }}
                        </p>
                        @if($auth_user->can('access-admin'))
                            <p>
                                <a href="{{ adminUrl() }}">{{ trans('form.action_go_to') }} {{ trans('pages.admin_title') }}</a>
                            </p>
                        @endif
                        <p>
                            <a href="{{ homeUrl('auth/logout') }}">{{ trans('form.action_logout') }}</a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <section id="my-conversations" class="even-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">{{ trans('form.list_of', ['name' => trans_choice('label.user_lc', 2)]) }}</h1>
                    <ul class="list-unstyled list-inline">
                        @foreach($user_sessions as $user_session)
                            @if($user_session->isGuest())
                                @if($user_session->ip_address != clientIp())
                                    <li>
                                        <a class="message-user" href="#" data-toggle="message"
                                           data-id="{{ $user_session->id }}">{{ trans('label.guest') }} #{{ $user_session->id }}</a>
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
                    <h1 class="text-uppercase">{{ trans('label.conversation') }}</h1>
                    <div class="message-holder">
                        <img src="{{ HomeTheme::imageAsset('loading.gif') }}">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection