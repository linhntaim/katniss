@extends('home_themes.wow_skype.master.master')
@section('lib_styles')
    <link rel="stylesheet" href="{{ libraryAsset('fancybox/jquery.fancybox.css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ libraryAsset('fancybox/jquery.fancybox.pack.js') }}"></script>
    <script src="{{ libraryAsset('fancybox/helpers/jquery.fancybox-media.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function() {
            $('a.teacher-video').fancybox({
                openEffect  : 'none',
                closeEffect : 'none',
                helpers: {
                    overlay: {
                        locked: false
                    },
                    media : {}
                }
            });
        });
    </script>
@endsection
@section('main_content')
    <div id="page-teacher">
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-3">
                        <img class="width-120 img-circle margin-bottom-15" src="{{ $teacher->userProfile->url_avatar_thumb }}" alt="{{ $teacher->userProfile->display_name }}">
                    </div>
                    <div class="col-md-9">
                        <h4 class="margin-top-none">
                            <strong>{{ $teacher->userProfile->display_name }}</strong>
                        </h4>
                        <p>{{ allCountry($teacher->userProfile->nationality, 'name') }}</p>
                        <p>
                            @if(!empty($teacher->video_teaching_url))
                                <a target="_blank" role="button" class="btn btn-success teacher-video" href="{{ $teacher->video_teaching_url }}">
                                    <i class="fa fa-play-circle"></i> {{ trans('label.teaching_video') }}
                                </a>
                                &nbsp;
                            @endif
                            @if(!empty($teacher->video_introduce_url))
                                <a target="_blank" role="button" class="btn btn-success teacher-video" href="{{ $teacher->video_introduce_url }}">
                                    <i class="fa fa-play-circle"></i> {{ trans('label.self_introduction_video') }}
                                </a>
                            @endif
                        </p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <label>{{ trans('label.about_me') }}</label>
                    </div>
                    <div class="col-md-9">
                        <p>
                            {{ trans('label.gender_' . $teacher->userProfile->gender) }},
                            {{ $teacher->userProfile->age }} {{ trans_choice('label.year_old', $teacher->userProfile->age) }}.
                        </p>
                        <p>
                            {{ trans('label.living_in') }}
                            {{ $teacher->userProfile->city }}, {{ allCountry($teacher->userProfile->settings->country, 'name') }}.
                        </p>
                    </div>
                </div>
                <hr class="margin-top-10">
                <div class="row">
                    <div class="col-md-3">
                        <label>{{ trans('label.self_introduction') }}</label>
                    </div>
                    <div class="col-md-9">
                        {!! $teacher->html_about_me !!}
                    </div>
                </div>
                <hr class="margin-top-10">
                <div class="row">
                    <div class="col-md-3">
                        <label>{{ trans_choice('label.topic', 2) }}</label>
                    </div>
                    <div class="col-md-9">
                        @foreach($teacher->topics as $topic)
                            <span class="sausage-item sausage-item-default"><strong>{{ $topic->name }}</strong></span>
                        @endforeach
                    </div>
                </div>
                <hr class="margin-top-10">
                <div class="row">
                    <div class="col-md-3">
                        <label>{{ trans('label.teaching_experience') }}</label>
                    </div>
                    <div class="col-md-9">
                        {!! $teacher->html_experience !!}
                    </div>
                </div>
                <hr class="margin-top-10">
                <div class="row">
                    <div class="col-md-3">
                        <label>{{ trans('label.teaching_methodology') }}</label>
                    </div>
                    <div class="col-md-9">
                        {!! $teacher->html_methodology !!}
                    </div>
                </div>
                @if($teacher->userProfile->educations->count() > 0)
                    <hr class="margin-top-10">
                    <div class="row">
                        <div class="col-md-3">
                            <label>{{ trans('label.education_history') }}</label>
                        </div>
                        <div class="col-md-9">
                            <div class="media-list">
                                @foreach($teacher->userProfile->educations as $education)
                                    <div class="media">
                                        <div class="media-body">
                                            <div class="big text-success"><strong>{{ $education->field }}</strong></div>
                                            <div class="color-darker bold-700">
                                                <span class="color-lighter">{{ trans('label.at_lc') }}</span> {{ $education->school }}
                                                {!! $education->renderDuration('<span class="color-lighter">' . trans('label.from_lc') . '</span>', '<span class="color-lighter">' . trans('label.to_lc') . '</span>') !!}
                                            </div>
                                            @if(!empty($education->description))
                                                <div class="margin-top-5">{{ $education->description }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                @if($teacher->userProfile->works->count() > 0)
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <label>{{ trans('label.work_history') }}</label>
                        </div>
                        <div class="col-md-9">
                            <div class="media-list">
                                @foreach($teacher->userProfile->works as $work)
                                    <div class="media">
                                        <div class="media-body">
                                            <div class="big text-success"><strong>{{ $work->position }}</strong></div>
                                            <div class="color-darker bold-700">
                                                <span class="color-lighter">{{ trans('label.at_lc') }}</span> {{ $work->company }}
                                                {!! $work->renderDuration('<span class="color-lighter">' . trans('label.from_lc') . '</span>', '<span class="color-lighter">' . trans('label.to_lc') . '</span>') !!}
                                            </div>
                                            @if(!empty($work->description))
                                                <div class="margin-top-5">{{ $work->description }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-4">
                @if(!$is_auth || !$auth_user->hasRole('student'))
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <a role="button" class="btn btn-danger btn-block uppercase"
                               href="{{ homeUrl('student/sign-up') }}?teacher={{ $teacher->user_id }}">
                                {{ trans('form.action_register_class') }}
                            </a>
                        </div>
                    </div>
                @endif
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h4 class="margin-top-none margin-bottom-15">{{ trans('label.need_help') }}</h4>
                        <p>Skype: <a href="skype:{{ $skype_id }}?chat" class="greenColor">{{ $skype_id }} ({{ $skype_name }})</a></p>
                        <p>Hotline: <a>{{ $hot_line }}</a></p>
                        <p class="margin-bottom-none">Email: <a href="mail:{{ $email }}" class="greenColor">{{ $email }}</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection