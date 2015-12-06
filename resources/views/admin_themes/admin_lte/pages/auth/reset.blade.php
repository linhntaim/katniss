@extends('admin_themes.admin_lte.master.auth')
@section('auth_type','login')
@section('box_message', trans('auth.rst_mess'))
@section('auth_form')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <form action="{{ localizedURL('password/reset') }}" method="post">
        {!! csrf_field() !!}
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="form-group has-feedback">
            <input type="email" class="form-control" placeholder="{{ trans('auth.rst_email') }}" name="email" required value="{{ old('email') }}">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="{{ trans('auth.rst_password') }}" required name="password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="{{ trans('auth.rst_retype') }}" required name="password_confirmation">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <button id="btn-register" type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('auth.reset') }}</button>
            </div><!-- /.col -->
        </div>
    </form>
@endsection