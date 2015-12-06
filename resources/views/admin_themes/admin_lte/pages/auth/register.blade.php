@extends('admin_themes.admin_lte.master.auth')
@section('site_description', trans('pages.page_register_desc'))
@section('auth_type','register')
@section('box_message', trans('pages.page_register_desc'))
@section('lib_styles')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css">
@endsection
@section('extended_styles')
    <style>
        .select2-dropdown {
            min-width: 320px;
        }
    </style>
@endsection
@section('lib_scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
@endsection
@section('extended_scripts')
    <script>
        {!! cdataOpen() !!}
        jQuery(function () {
            jQuery('.select2').select2();
        });
        {!! cdataClose() !!}
    </script>
@endsection
@section('auth_form')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <form action="{{ localizedURL('auth/register') }}" method="post">
        {!! csrf_field() !!}
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

    <div class="social-auth-links text-center">
        <p>- {{ trans('auth.log_or') }} -</p>
        <a href="{{ localizedURL('auth/social/{provider}', array('provider' => 'facebook')) }}?selected_roles={{ implode(',', $selected_roles) }}" class="btn btn-block btn-social btn-facebook btn-flat">
            <i class="fa fa-facebook"></i> {{ trans('label.sign_in_with_facebook') }}
        </a>
        <a href="{{ localizedURL('auth/social/{provider}', array('provider' => 'google')) }}?selected_roles={{ implode(',', $selected_roles) }}" class="btn btn-block btn-social btn-google btn-flat">
            <i class="fa fa-google-plus"></i> {{ trans('label.sign_in_with_google') }}
        </a>
    </div><!-- /.social-auth-links -->

    <a href="{{ localizedURL('auth/login') }}" class="text-center">{{ trans('label.already_member') }}</a><br>
    <a href="{{ homeURL() }}" class="text-center">{{ trans('label.back_to_homepage') }}</a>
@endsection