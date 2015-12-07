@extends('admin_themes.admin_lte.master.auth')
@section('auth_type','login')
@section('box_message', trans('pages.account_login_desc'))
@section('auth_form')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <form method="post">
        {!! csrf_field() !!}
        <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="{{ trans('label.email') }} {{ trans('label.or_lc') }} {{ trans('label.user_name') }}" required name="account" value="{{ old('account') }}">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="{{ trans('label.password') }}" required name="password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-8">
                <div class="checkbox icheck">
                    <label for="inputRemember">
                        <input id="inputRemember" type="checkbox" name="remember"{{ old('remember') ? ' checked' : '' }}>
                        &nbsp; {{ trans('label.remember_me') }}
                    </label>
                </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('form.action_login') }}</button>
            </div><!-- /.col -->
        </div>
    </form>

    <div class="social-auth-links text-center">
        <p class="text-uppercase">- {{ trans('label.or') }} -</p>
        <a href="{{ homeUrl('auth/social/{provider}', array('provider' => 'facebook')) }}" class="btn btn-block btn-social btn-facebook btn-flat">
            <i class="fa fa-facebook"></i> {{ trans('label.sign_in_with_facebook') }}
        </a>
        <a href="{{ homeUrl('auth/social/{provider}', array('provider' => 'google')) }}" class="btn btn-block btn-social btn-google btn-flat">
            <i class="fa fa-google-plus"></i> {{ trans('label.sign_in_with_google') }}
        </a>
    </div><!-- /.social-auth-links -->

    <a href="{{ homeUrl('password/email') }}" class="text-center">{{ trans('label.forgot_password') }}</a><br>
    <a href="{{ homeUrl('auth/register') }}" class="text-center">{{ trans('label.register_membership') }}</a><br>
    <a href="{{ homeURL() }}" class="text-center">{{ trans('label.back_to_homepage') }}</a>
@endsection