@extends('admin_themes.admin_lte.master.auth')
@section('site_description', trans('pages.page_register_desc'))
@section('auth_type','register')
@section('box_message', trans('pages.page_register_desc'))
@section('auth_form')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <form action="{{ currentURL() }}" method="post">
        {!! csrf_field() !!}
        <input type="hidden" name="provider" value="{{ old('provider') }}">
        <input type="hidden" name="provider_id" value="{{ old('provider_id') }}">
        <input type="hidden" name="profile_picture" value="{{ old('profile_picture') }}">
        <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="{{ trans('label.user_name') }}" name="name" value="{{ old('name') }}">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="email" class="form-control" placeholder="{{ trans('label.email') }}" name="email" required value="{{ old('email') }}">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <div class="form-group">
                    <label for="inputPhoneCode" class="sr-only">{{ trans('label.phone_code') }}</label>
                    <select id="inputPhoneCode" name="phone_code" class="form-control select2" data-placeholder="{{ trans('form.action_select') }} {{ trans('label.calling_code_lc') }}" style="width: 100%">
                        {!! callingCodesAsOptions(old('phone_code', 'US')) !!}
                    </select>
                </div>
            </div>
            <div class="col-xs-8 margin-left-none">
                <div class="form-group has-feedback">
                    <input type="tel" class="form-control" placeholder="{{ trans('label.phone') }}" name="phone" required value="{{ old('phone') }}">
                    <span class="glyphicon glyphicon-earphone form-control-feedback"></span>
                </div>
            </div>
        </div>
        <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="{{ trans('label.password') }}" required name="password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="{{ trans('label.password_retype') }}" required name="password_confirmation">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group icheck">
            {{ trans('label.you_are') }}: &nbsp;&nbsp;
            @foreach($public_roles as $role)
                <label>
                    @if(in_array($role->name, $selected_roles) || in_array($role->id, $selected_roles))
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" checked> {{ trans('roles.'.$role->name) }}
                    @else
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}"> {{ trans('roles.'.$role->name) }}
                    @endif
                </label>
                &nbsp;&nbsp;
            @endforeach
        </div>
        <div class="row">
            <div class="col-xs-8">
            </div><!-- /.col -->
            <div class="col-xs-4">
                <button id="btn-register" type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('form.action_register') }}</button>
            </div><!-- /.col -->
        </div>
    </form>

    <a href="{{ localizedURL('auth/login') }}" class="text-center">{{ trans('label.already_member') }}</a><br>
    <a href="{{ homeURL() }}" class="text-center">{{ trans('label.back_to_homepage') }}</a>
@endsection