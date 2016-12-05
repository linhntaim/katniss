/**
 * Created by Nguyen Tuan Linh on 2016-12-05.
 */
$(function() {
    // fix stacked modals
    var _zIndexModal = 1050;
    $(document).on('shown.bs.modal', '.modal', function (e) {
        $(this).css('z-index', ++_zIndexModal);
    });

    function openWindow(url, name, specs, replace) {
        var specsArr = [];
        for (var key in specs) {
            specsArr.push(key + '=' + specs[key]);
        }
        return window.open(url, name, specsArr.join(','), replace);
    }

    $(document).on('click', '.go-url', function (e) {
        e.preventDefault();
        var url = $(this).attr('data-url');
        if (url) {
            window.location.href = url;
        }
    }).on('click', '.open-window', function (e) {
        e.preventDefault();

        var $this = $(this);
        var data = $.extend($this.data());
        var url = '';
        var name = '';
        var replace = true;
        var callback = null;
        if (data.url) {
            url = data.url;
            delete data.url;
        }
        else {
            if ($this.is('a')) {
                url = $this.attr('href');
            }
            else if ($this.is('img')) {
                url = $this.attr('src');
            }
        }
        if (data.name) {
            name = data.name;
            delete data.name;
        }
        if (data.replace) {
            replace = data.replace;
            delete data.replace;
        }
        if (data.callback) {
            callback = data.callback;
            delete data.callback;
        }
        var w = openWindow(url, name, data, replace);
        if (callback && typeof callback === 'string') {
            window[callback](this, w);
        }
        return false;
    });
});
