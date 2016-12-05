/**
 * Created by Nguyen Tuan Linh on 2016-12-05.
 */
$(function() {
    var _sessionTimeout = null;

    function updateCsrfToken(csrfToken) {
        KATNISS_REQUEST_TOKEN = csrfToken;
        jQuery('input[type="hidden"][name="_token"]').val(csrfToken);
    }

    function startSessionTimeout() {
        if (isSet(_sessionTimeout)) {
            clearTimeout(_sessionTimeout);
        }
        _sessionTimeout = setTimeout(function () {
            console.log('session end');
            if (KATNISS_USER === false) {
                var api = new KatnissApi(true);
                api.get('user/csrf-token', null, function (failed, data, messages) {
                    if (!failed) {
                        updateCsrfToken(data.csrf_token);
                    }
                })
            }
            else {
                x_lock();
            }
        }, KATNISS_SESSION_LIFETIME);
    }

    startSessionTimeout();
});