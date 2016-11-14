<div class="modal fade modal-primary" id="x-confirm-modal" tabindex="-1" role="dialog"
     aria-labelledby="x-confirm-modal-title">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="x-confirm-modal-title"></h4>
            </div>
            <div id="x-confirm-modal-content" class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">
                    {{ trans('form.action_cancel') }}
                </button>
                <button id="x-confirm-modal-button" type="button" class="btn btn-outline" data-dismiss="modal">
                    {{ trans('form.action_confirm') }}
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-danger" id="x-alert-modal" tabindex="-1" role="dialog"
     aria-labelledby="x-alert-modal-title">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="x-alert-modal-title">{{ trans('label.alert') }}</h4>
            </div>
            <div id="x-alert-modal-content" class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-dismiss="modal">
                    {{ trans('form.action_close') }}
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade lockscreen" id="x-lock-modal" tabindex="-1" role="dialog" aria-labelledby="x-lock-modal-title">
    <div class="modal-dialog" role="document">
        <div class="modal-content no-shadow bg-transparent">
            <div id="x-lock-modal-content" class="modal-body">
                <div class="lockscreen-wrapper">
                    <div class="lockscreen-logo">
                        <a href="{{ homeUrl() }}"><strong>{{ $site_name }}</strong></a>
                    </div>
                    <div class="lockscreen-name">{{ isAuth() ? $auth_user->display_name : '' }}</div>
                    <div class="lockscreen-item">
                        <div class="lockscreen-image">
                            <img src="{{ isAuth() ? $auth_user->url_avatar : asset('avatar.png') }}" alt="User Image">
                        </div>
                        <form class="lockscreen-credentials" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" value="{{ isAuth() ? $auth_user->id : '' }}">
                            <div class="input-group">
                                <input type="password" name="password" class="form-control"
                                       placeholder="{{ trans('label.password') }}">
                                <div class="input-group-btn">
                                    <button type="button" class="btn">
                                        <i class="fa fa-arrow-right text-muted"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="help-block text-center">
                        Enter your password to retrieve your session
                    </div>
                    <div class="text-center">
                        <a href="{{ homeUrl('auth/login') }}">Or sign in as a different user</a>
                    </div>
                    <div class="lockscreen-footer text-center">
                        <strong>
                            Copyright &copy; {{ date('Y') }}
                            <a href="{{ $site_home_url }}">{{ $site_name }} {{ $site_version }}</a>
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    {!! cdataOpen() !!}
    function x_confirm(title, content, callback) {
        var confirmModal = jQuery('#x-confirm-modal');
        confirmModal.find('#x-confirm-modal-title').html(title);
        confirmModal.find('#x-confirm-modal-content').html(content);
        confirmModal.find('#x-confirm-modal-button').on('click', function () {
            if (typeof callback === 'function') {
                callback.call();
            }
        });
        confirmModal.modal('show').on('hide.bs.modal', function () {
            confirmModal.find('#x-confirm-modal-title').text('');
            confirmModal.find('#x-confirm-modal-content').text('');
            confirmModal.find('#x-confirm-modal-button').off('click');

            confirmModal.off('hide.bs.modal');
        });
    }
    function x_href_confirm(href, title, content) {
        x_confirm(title, content, function () {
            window.location.href = href;
        });
    }

    function x_alert(content) {
        var alertModal = jQuery('#x-alert-modal');
        alertModal.find('#x-alert-modal-content').html(content);
        alertModal.modal('show');
    }

    function x_lock() {
        var lockModal = jQuery('#x-lock-modal');
        lockModal.modal({backdrop: 'static'}).modal('show');
    }

    jQuery(document).ready(function () {
        var jLockId = jQuery('.lockscreen-credentials input[name="id"]');
        var jLockPassword = jQuery('.lockscreen-credentials input[name="password"]');
        var jLockImage = jQuery('.lockscreen-image > img');
        jQuery('.lockscreen-credentials button').off('click').on('click', function (e) {
            e.preventDefault();
            var api = new KatnissApi(true);
            api.get('user/quick-login', {
                id: jLockId.val(),
                password: jLockPassword.val()
            }, function (failed, data, messages) {
                if (!failed) {
                    KATNISS_USER = data.user;
                    updateCsrfToken(data.csrf_token);
                    jLockId.val(KATNISS_USER.id);
                    jLockPassword.val('');
                    jLockImage.attr('src', KATNISS_USER.url_avatar);
                    jQuery('#x-lock-modal').modal('hide');
                }
                else {
                    x_alert(messages.first());
                }
            });
            return false;
        });
    });
    {!! cdataClose() !!}
</script>