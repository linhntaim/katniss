@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_users_title'))
@section('page_description', trans('pages.admin_users_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('users') }}">{{ trans('pages.admin_users_title') }}</a></li>
        <li><a href="#">{{ trans('form.action_add') }}</a></li>
    </ol>
@endsection
@section('lib_styles')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css">
    <link rel="stylesheet" href="{{ libraryAsset('iCheck/square/blue.css') }}">
@endsection
@section('extended_styles')
    <style>
        .select2-dropdown {
            min-width: 320px;
        }
    </style>
@endsection
@section('lib_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
    <script src="{{ libraryAsset('iCheck/icheck.min.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        {!! cdataOpen() !!}
                jQuery(document).ready(function () {
                    jQuery('.select2').select2();
                    jQuery('[type=checkbox]').iCheck({
                        checkboxClass: 'icheckbox_square-blue',
                        radioClass: 'iradio_square-blue',
                        increaseArea: '20%' // optional
                    });
                });
        {!! cdataClose() !!}
    </script>
@endsection
@section('page_content')
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('form.action_add') }} {{ trans_choice('label.user_lc', 1) }}</h3>
            </div>
            <form method="post">
                {!! csrf_field() !!}
                <div class="box-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="inputDisplayName">{{ trans('label.display_name') }}</label>
                        <input class="form-control" id="inputDisplayName" name="display_name" maxlength="255" placeholder="{{ trans('label.display_name') }}" type="text" required value="{{ old('display_name') }}">
                    </div>
                    <div class="form-group">
                        <label for="inputEmail">{{ trans('label.email') }}</label>
                        <input class="form-control" id="inputEmail" name="email" maxlength="255" placeholder="{{ trans('label.email') }}" type="email" required value="{{ old('email') }}">
                    </div>
                    <div class="form-group">
                        <label for="inputName">{{ trans('label.user_name') }}</label>
                        <input class="form-control" id="inputName" name="name" maxlength="255" placeholder="{{ trans('label.user_name') }}" type="text" required value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="inputPassword">{{ trans('label.password') }}</label>
                        <input class="form-control" id="inputPassword" name="password" placeholder="{{ trans('label.password') }}" type="text" required value="{{ old('password') }}">
                    </div>
                    <div class="form-group">
                        <label for="inputRoles">{{ trans_choice('label.role', 2) }}</label>
                        <select id="inputRoles" class="form-control select2" name="roles[]" multiple="multiple" style="width: 100%"
                                data-placeholder="{{ trans_choice('label.role', 2) }}">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}"{{ $role->name == 'user' ? ' selected' : '' }}>
                                    {{ $role->display_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="checkbox icheck">
                            <label for="inputSendMail">
                                <input id="inputSendMail" name="send_welcomed_mail" type="checkbox" value="1" checked>
                                &nbsp; {{ trans('label.send_welcome_mail') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">{{ trans('form.action_add') }}</button>
                    <div class="pull-right">
                        <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                        <a role="button" class="btn btn-warning" href="{{ adminUrl('users') }}">{{ trans('form.action_cancel') }}</a>
                    </div>
                </div>
            </form>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
@endsection