@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_users_title'))
@section('page_description', trans('pages.admin_users_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('users') }}">{{ trans('pages.admin_users_title') }}</a></li>
        <li><a href="#">{{ trans('form.action_edit') }}</a></li>
    </ol>
@endsection
@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
@endsection
@section('extended_styles')
    <style>
        .select2-dropdown {
            min-width: 320px;
        }
    </style>
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('select2-js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            $('.select2').select2();
            x_modal_delete($('a.delete'), '{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}');
        });
    </script>
@endsection
@section('page_content')
    <div class="row">
        <div class="col-xs-12">
            <div class="margin-bottom">
                <a class="btn btn-warning delete" href="{{ addErrorUrl(adminUrl('users/{id}', ['id'=> $user->id])) }}">
                    {{ trans('form.action_delete') }}
                </a>
                <a class="btn btn-primary pull-right" href="{{ adminUrl('users/create') }}">{{ trans('form.action_add') }} {{ trans_choice('label.user_lc', 1) }}</a>
            </div>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        {{ trans('form.action_edit') }} {{ trans_choice('label.user_lc', 1) }} - <em>{{ $user->display_name }} ({{ $user->name }})</em>
                    </h3>
                </div>
                <form method="post" action="{{ adminUrl('users/{id}', ['id'=> $user->id]) }}">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <div class="box-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                        <div class="form-group">
                            <label class="required" for="inputDisplayName">{{ trans('label.display_name') }}</label>
                            <input class="form-control" id="inputDisplayName" name="display_name" maxlength="255" placeholder="{{ trans('label.display_name') }}" type="text" required value="{{ $user->display_name }}">
                        </div>
                        <div class="form-group">
                            <label class="required" for="inputEmail">{{ trans('label.email') }}</label>
                            <input class="form-control" id="inputEmail" name="email" maxlength="255" placeholder="{{ trans('label.email') }}" type="email" required value="{{ $user->email }}">
                        </div>
                        <div class="form-group">
                            <label class="required" for="inputName">{{ trans('label.user_name') }}</label>
                            <input class="form-control" id="inputName" name="name" maxlength="255" placeholder="{{ trans('label.user_name') }}" type="text" required value="{{ $user->name }}">
                        </div>
                        <div class="form-group">
                            <label class="required" for="inputPassword">{{ trans('label.password') }}</label>
                            <input class="form-control" id="inputPassword" name="password" placeholder="{{ trans('label.password') }}" type="text">
                        </div>
                        <div class="form-group">
                            <label for="inputRoles">{{ trans_choice('label.role', 2) }}</label>
                            <select id="inputRoles" class="form-control select2" name="roles[]" multiple="multiple" data-placeholder="{{ trans_choice('label.role', 2) }}">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}"{{ $user_roles->contains('id', $role->id) ? ' selected' : '' }}>{{ $role->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button class="btn btn-primary" type="submit">{{ trans('form.action_save') }}</button>
                        <div class="pull-right">
                            <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                            <a role="button" class="btn btn-warning pull-right" href="{{ adminUrl('users') }}">{{ trans('form.action_cancel') }}</a>
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