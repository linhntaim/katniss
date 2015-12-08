@extends('simple')
@section('lib_styles')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="{{ libraryAsset('elfinder/css/elfinder.min.css') }}">
    <link rel="stylesheet" href="{{ libraryAsset('elfinder/css/theme.css') }}">
@endsection
@section('lib_scripts')
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script src="{{ libraryAsset('elfinder/js/elfinder.min.js') }}"></script>
    <script src="{{ libraryAsset('elfinder/js/i18n/elfinder.'.$site_locale.'.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        {!! cdataOpen() !!}
        jQuery.urlParam = function (name) {
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
            if (results == null) {
                return null;
            }
            else {
                return decodeURIComponent(results[1]) || 0;
            }
        };

        jQuery(document).ready(function () {
            var funcNum = $.urlParam('CKEditorFuncNum');
            var elfinderOptions = {
                lang: '{{ $site_locale }}',
                customData: {
                    _token: '{{ csrf_token() }}'
                },
                url: '{{ localizedURL('documents/connector') }}{{ empty($custom_type) ? '' : '?custom_type='.$custom_type }}',
                getFileCallback: function (file) {
                    window.opener.CKEDITOR.tools.callFunction(funcNum, file.url);
                    window.close();
                },
                uiOptions: {
                    toolbar: [
                        ['back', 'forward'],
                        ['home', 'reload', 'up'],
                        ['mkdir', 'mkfile', 'upload'],
                        ['open', 'download', 'getfile'],
                        ['info', 'quicklook'],
                        ['copy', 'cut', 'paste'],
                        ['rm'],
                        ['duplicate', 'rename', 'edit', 'resize', 'pixlr'],
                        ['search'],
                        ['view', 'sort']
                    ]
                },
                dateFormat: '{{ $dateFormat }} {{ $timeFormat }}'
            };
            var onlyMimes = '{{ $onlyMimes }}';
            if (onlyMimes.trim() != '') {
                elfinderOptions.onlyMimes = onlyMimes.split(',');
            }
            jQuery('#elfinder').elfinder(elfinderOptions);
        });
        {!! cdataClose() !!}
    </script>
@endsection
@section('body')
    <div id="elfinder"></div>
@endsection