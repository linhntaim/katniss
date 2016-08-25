@extends('admin_themes.admin_lte.master.auth')
@section('auth_type','login')
@section('box_message', trans('pages.my_account_desc'))
@section('lib_styles')
    <link rel="stylesheet" href="{{ libraryAsset('cropperjs/cropper.min.css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ libraryAsset('cropperjs/cropper.min.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        {!! cdataOpen() !!}
        jQuery(document).ready(function () {
            new CropImageModal($('body'), 1, 'user/{{ $auth_user->id }}/avatar/cropper-js');
        });
        {!! cdataClose() !!}
    </script>
@endsection
@section('modals')
    @include('modal_cropper_image')
@endsection
@section('auth_form')
    @include('messages_after_action')
    <form method="post">
        {!! csrf_field() !!}
        <div class="form-group">
            <img class="profile-user-img img-responsive img-circle cropper-image-view"
                 alt="{{ $auth_user->name }}" src="{{ $auth_user->url_avatar }}">
        </div>
        <div class="form-group">
            <label for="inputDisplayName">{{ trans('label.display_name') }}</label>
            <input class="form-control" id="inputDisplayName" name="display_name" maxlength="255" placeholder="{{ trans('label.display_name') }}" type="text" required value="{{ $auth_user->display_name }}">
        </div>
        <div class="form-group">
            <label for="inputEmail">{{ trans('label.email') }}</label>
            <input class="form-control" id="inputEmail" name="email" maxlength="255" placeholder="{{ trans('label.email') }}" type="email" required value="{{ $auth_user->email }}">
        </div>
        <div class="form-group">
            <label for="inputName">{{ trans('label.user_name') }}</label>
            <input class="form-control" id="inputName" name="name" maxlength="255" placeholder="{{ trans('label.user_name') }}" type="text" required value="{{ $auth_user->name }}">
        </div>
        <div class="form-group">
            <label for="inputPassword">{{ trans('label.password') }}</label>
            <input type="password" class="form-control" id="inputPassword" name="password" placeholder="{{ trans('label.password') }}">
        </div>
        <div class="form-group">
            <label for="inputPasswordRetype">{{ trans('label.password_retype') }}</label>
            <input type="password" class="form-control" id="inputPasswordRetype" name="password_confirmation" placeholder="{{ trans('label.password_retype') }}">
        </div>
        <hr>
        <div class="form-group">
            <label for="inputCurrentPassword">{{ trans('label.current_password') }}</label>
            <input type="password" class="form-control" id="inputCurrentPassword" name="current_password" placeholder="{{ trans('label.current_password') }}" required>
        </div>
        <div class="form-group text-right">
            <button type="submit" class="btn btn-primary">{{ trans('form.action_save') }}</button>
        </div>
    </form>
    <p>
        <a href="{{ homeUrl() }}">{{ trans('label.back_to_homepage') }}</a>
        @if(!$is_auth)
            <br><a href="{{ homeUrl('auth/login') }}">{{ trans('form.action_login') }}</a>
        @else
            @if($auth_user->can('access-admin'))
                <br><a href="{{ adminUrl() }}">{{ trans('form.action_go_to') }} {{ trans('pages.admin_title') }}</a>
            @endif
        @endif
    </p>
@endsection