@extends('home_themes.wow_skype.master.simple')
@section('lib_styles')
    <link rel="stylesheet" href="{{ libraryAsset('cropperjs/cropper.min.css') }}">
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
    <link rel="stylesheet" href="{{ _kExternalLink('select2-bootstrap-css') }}">
    <link rel="stylesheet" href="{{ libraryAsset('bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ libraryAsset('cropperjs/cropper.min.js') }}"></script>
    <script src="{{ _kExternalLink('select2-js') }}"></script>
    <script src="{{ libraryAsset('bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ libraryAsset('bootstrap-datepicker/locales/bootstrap-datepicker.'.$site_locale.'.min.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            $('.date-picker').datepicker({
                format: '{{ $date_js_format }}',
                language: '{{ $site_locale }}',
                enableOnReadonly : false
            });
            $('.select2').select2({
                theme: 'bootstrap'
            });
            new CropImageModal($('body'), 1, 'user/{{ $auth_user->id }}/avatar/cropper-js');
        });
    </script>
@endsection
@section('modals')
    @include('modal_cropper_image')
@endsection
@section('main_content')
    <div id="page-teacher-sign-up-step">
        <h2>{{ trans('label.become_our_teacher') }}</h2>
        <p>{{ trans('label.become_our_teacher_help') }}</p>

        <ul class="nav nav-wizard margin-top-20 margin-bottom-30">
            <li class="active"><a href="#"><span>1</span> <strong>{{ trans('label.personal_information') }}</strong></a></li>
            <li><a href="#"><span>2</span> <strong>{{ trans('label.teacher_information') }}</strong></a></li>
        </ul>

        <form method="post">
            {{ csrf_field() }}
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <div class="form-group">
                <div class="row img-edit-wrapper">
                    <div class="col-sm-4 text-center">
                        <img id="profile-user-img" class="profile-user-img full-width" src="{{ $auth_user->url_avatar }}" alt="{{ $auth_user->display_name }}">
                    </div>
                    <div class="col-sm-8">
                        <p>
                            Please choose your best photo. First impression is very important in making learners choose you as their teachers
                        </p>
                        <button type="button" class="btn btn-success cropper-image-view" data-img="#profile-user-img">{{ trans('form.action_upload_avatar') }}</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label for="inputBirthday" class="control-label">{{ trans('label.birthday') }} ({{ $date_js_format }})</label>
                        <input type="text" placeholder="{{ trans('label.birthday') }}" value="{{ $auth_user->birthday }}"
                               class="form-control date-picker" name="date_of_birth" id="inputBirthday" required>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="inputGender" class="control-label">{{ trans('label.gender') }}</label>
                        <select id="inputGender" class="form-control" name="gender" required>
                            <option value="">
                                - {{ trans('form.action_select') }} {{ trans('label.gender') }} -
                            </option>
                            @foreach(allGenders() as $gender)
                                <option value="{{ $gender }}"{{ $gender == $auth_user->gender ? ' selected' : '' }}>
                                    {{ trans('label.gender_'.$gender) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputCity" class="control-label">{{ trans('label.city') }}</label>
                <input type="text" placeholder="{{ trans('label.city') }}" value="{{ $auth_user->city }}"
                       class="form-control" id="inputCity" name="city" required>
            </div>
            <div class="form-group">
                <label for="inputCountry" class="control-label">{{ trans('label.country') }}</label>
                <select id="inputCountry" class="form-control select2" name="country" style="width: 100%;" required>
                    <option value="">
                        - {{ trans('form.action_select') }} {{ trans('label.country') }} -
                    </option>
                    {!! countriesAsOptions($auth_user->settings->country) !!}
                </select>
            </div>
            <div class="form-group">
                <label for="inputNationality" class="control-label">{{ trans('label.nationality') }}</label>
                <select id="inputNationality" class="form-control select2" name="nationality" style="width: 100%;" required>
                    <option value="">
                        - {{ trans('form.action_select') }} {{ trans('label.nationality') }} -
                    </option>
                    {!! countriesAsOptions($auth_user->nationality) !!}
                </select>
            </div>
            <div class="form-group">
                <label for="inputFacebook" class="control-label">Facebook URL</label>
                <input type="text" placeholder="Facebook URL" value="{{ $auth_user->facebook }}"
                       class="form-control" id="inputFacebook" name="facebook" required>
            </div>
            <div class="form-group text-right">
                <button type="submit" class="btn btn-success uppercase"><strong>{{ trans('form.action_continue') }}</strong></button>
            </div>
        </form>
        <div class="margin-v-30">&nbsp;</div>
    </div>
@endsection