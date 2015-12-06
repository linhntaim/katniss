@extends('admin_themes.admin_lte.master.auth')
@section('auth_type','login')
@section('box_message', trans('auth.pwd_mess'))
@section('auth_form')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <form action="{{ localizedURL('password/email') }}" method="post">
        {!! csrf_field() !!}
        <div class="form-group has-feedback">
            <input type="email" class="form-control" placeholder="{{ trans('auth.pwd_email') }}" required name="email" value="{{ old('email') }}">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('auth.pwd_reset') }}</button>
            </div><!-- /.col -->
        </div>
    </form>

    <div class="social-auth-links text-center">
    </div>

    <a href="{{ localizedURL('auth/login') }}" class="text-center">{{ trans('auth.pwd_log') }}</a><br>
    <a href="{{ localizedURL('auth/register') }}" class="text-center">{{ trans('auth.pwd_reg') }}</a><br>
    <a href="{{ homeURL() }}" class="text-center">{{ trans('auth.pwd_home') }}</a>
@endsection