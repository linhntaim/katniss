@extends('home_themes.wow_skype.master.profile', [
    'html_page_id' => 'page-profile-teaching-time',
    'panel_heading' => trans('label.teaching_time'),
])
@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
    <link rel="stylesheet" href="{{ _kExternalLink('select2-bootstrap-css') }}">
@endsection
@section('extended_styles')
    <style>
        input[type=checkbox], input[type=radio] {margin-top:2px}
    </style>
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('select2-js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            $('.select2').select2({
                theme: 'bootstrap'
            });
        });
    </script>
@endsection
@section('profile_content')
    <form method="post">
        {{ csrf_field() }}
        {{ method_field('put') }}
        @include('messages_after_action')
        <div class="form-group">
            <label for="inputTimeZone">{{ trans('label.timezone') }}:</label>
            <select id="inputTimeZone" class="form-control select2" name="timezone" style="width: 100%;">
                {!!  timeZoneListAsOptions(settings()->timezone) !!}
            </select>
        </div>
        <div class="form-group">
            <label>{{ trans('label.available_times') }}:</label>
            @for($i=0; $i<7; ++$i)
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="times[]" value="{{ $i }}"{{ in_array($i, $available_times) ? ' checked' : '' }}>
                        {{ trans('datetime.day_' . $i) }}
                    </label>
                </div>
            @endfor
        </div>
        <div class="form-group text-right">
            <button type="submit" class="btn btn-success uppercase"><strong>{{ trans('form.action_save') }}</strong></button>
        </div>
    </form>
@endsection