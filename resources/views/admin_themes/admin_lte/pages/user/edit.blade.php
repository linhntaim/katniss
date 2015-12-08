@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_users_title'))
@section('page_description', trans('pages.admin_users_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('users') }}">{{ trans('pages.admin_users_title') }}</a></li>
    </ol>
@endsection
@section('lib_styles')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css">
    <link rel="stylesheet" href="{{ libraryAsset('bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
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
    <script src="{{ libraryAsset('bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ libraryAsset('bootstrap-datepicker/locales/bootstrap-datepicker.'.$site_locale.'.min.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        {!! cdataOpen() !!}
                jQuery(document).ready(function () {
                    jQuery('.select2').select2();
                    jQuery('.date-picker').datepicker({
                        format: '{{ $date_js_format }}',
                        language: '{{ $site_locale }}',
                        enableOnReadonly : false
                    });
                    jQuery('a.delete').off('click').on('click', function (e) {
                        e.preventDefault();

                        var $this = $(this);

                        x_confirm('{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}', function () {
                            window.location.href = $this.attr('href');
                        });

                        return false;
                    });
                });
        {!! cdataClose() !!}
    </script>
@endsection
@section('modals')
    @include('admin_themes.admin_lte.master.common_modals')
@endsection
@section('page_content')
    <div class="row">
        <div class="col-xs-12">
            <div class="margin-bottom">
                <a class="btn btn-primary" href="{{ adminUrl('users/add') }}">{{ trans('form.action_add') }} {{ trans_choice('label.user_lc', 1) }}</a>
                <a class="btn btn-warning delete" href="{{ adminUrl('users/{id}/delete', ['id'=> $user->id])}}">
                    {{ trans('form.action_delete') }}
                </a>
            </div>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        {{ trans('label.user_information') }}
                    </h3>
                </div>
                <form action="{{ adminUrl('users/update') }}" method="post">
                    {!! csrf_field() !!}
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <div class="box-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                        <div class="form-group">
                            <label for="inputName">{{ trans('label.user_name') }}</label>
                            <input class="form-control" id="inputName" name="name" maxlength="255" placeholder="{{ trans('label.user_name') }}" type="text" required value="{{ $user->name }}">
                            <div class="help-block">{{ trans('label.slug') }}: <em>{{ $user->slug }}</em></div>
                        </div>
                        <div class="form-group">
                            <label for="inputBirthday">{{ trans('label.birthday') }}</label>
                            <input class="form-control date-picker" id="inputBirthday" type="text" name="date_of_birth" placeholder="{{ $date_js_format }}" value="{{ $user->dateOfBirth }}">
                        </div>
                        <div class="form-group">
                            <label for="inputGender">{{ trans('label.gender') }}</label>
                            <select id="inputGender" class="form-control" name="gender" data-placeholder="{{ trans('form.action_select') }} {{ trans('label.gender_lc') }}">
                                @foreach(allGenders() as $gender)
                                    <option value="{{ $gender }}"{{ $gender == $user->gender ? ' selected' : '' }}>
                                        {{ trans('label.gender_'.$gender) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail">{{ trans('label.email') }}</label>
                            <input class="form-control" id="inputEmail" name="email" maxlength="255" placeholder="{{ trans('label.email') }}" type="email" required value="{{ $user->email }}">
                        </div>
                        <div class="form-group">
                            <label for="inputPassword">{{ trans('label.password') }}</label>
                            <input class="form-control" id="inputPassword" name="password" placeholder="{{ trans('label.password') }}" type="text">
                        </div>
                        <div class="form-group">
                            <label for="inputPhone">{{ trans('label.phone') }}</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="inputPhoneCode" class="sr-only">{{ trans('label.phone_code') }}</label>
                                    <select id="inputPhoneCode" name="phone_code" class="form-control select2" data-placeholder="{{ trans('form.action_select') }} {{ trans('label.calling_code_lc') }}" style="width: 100%">
                                        {!! callingCodesAsOptions($user->phone_code) !!}
                                    </select>
                                </div>
                                <div class="col-md-9">
                                    <input class="form-control" id="inputPhone" name="phone" placeholder="{{ trans('label.phone') }}" type="tel" required value="{{ $user->phone }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputSkype">{{ trans('label.skype') }}</label>
                            <input class="form-control" id="inputSkype" name="skype" placeholder="{{ trans('label.skype') }}" type="text" value="{{ $user->skype }}">
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">{{ trans('label.address') }}</label>
                            <input class="form-control" id="inputAddress" name="address" placeholder="{{ trans('label.address') }}" type="text" value="{{ $user->address }}">
                        </div>
                        <div class="form-group">
                            <label for="inputCity">{{ trans('label.city') }}</label>
                            <input class="form-control" id="inputCity" name="city" placeholder="{{ trans('label.city') }}" type="text" value="{{ $user->city }}">
                        </div>
                        <div class="form-group">
                            <label for="inputCountry">{{ trans('label.nationality') }}</label>
                            <select id="inputCountry" class="form-control select2" name="country" style="width: 100%;">
                                {!! countriesAsOptions($user->country) !!}
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inputLanguage">{{ trans('label.main_language') }}</label>
                            <select id="inputLanguage" class="form-control" name="language" data-placeholder="{{ trans('form.action_select') }} {{ trans('label.main_language_lc') }}">
                                @foreach (allSupportedLocales() as $localeCode => $properties)
                                    <option value="{{ $localeCode }}"{{ $localeCode == $user->language ? ' selected' : '' }}>
                                        {{ $properties['native'] }}
                                    </option>
                                @endforeach
                            </select>
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
                        <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                        <a role="button" class="btn btn-default pull-right" href="{{ adminUrl('users') }}">{{ trans('form.action_cancel') }}</a>
                    </div>
                </form>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
@endsection