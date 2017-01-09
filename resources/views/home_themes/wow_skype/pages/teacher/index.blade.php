@extends('home_themes.wow_skype.master.master')
@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
    <link rel="stylesheet" href="{{ _kExternalLink('select2-bootstrap-css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('select2-js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            $('.select2').select2({
                theme: 'bootstrap'
            });
        });
    </script>
@endsection
@section('main_content')
    <div id="page-teachers">
        <div class="row">
            <div class="col-md-4 hidden-sm hidden-xs">
                <div id="teachers-sidebar">
                    @include('home_themes.wow_skype.pages.teacher.index_help_' . $site_locale)
                </div>
            </div>
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form>
                            <div class="form-group">
                                <label for="inputTopics" class="control-label sr-only">{{ trans_choice('label.topic', 2) }}</label>
                                <select class="form-control select2" id="inputTopics" name="topics[]" multiple="multiple"
                                        data-placeholder="- {{ trans('form.action_select') }} {{ trans_choice('label.topic', 2) }} -" style="width: 100%;">
                                    @foreach($topics as $topic)
                                        <option value="{{ $topic->id }}"{{ in_array($topic->id, $search_topics) ? ' selected' : '' }}>
                                            {{ $topic->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label for="inputNationality" class="control-label sr-only">{{ trans('label.nationality') }}</label>
                                        <select id="inputNationality" class="form-control select2" name="nationality" style="width: 100%;">
                                            <option value="">
                                                - {{ trans('form.action_select') }} {{ trans('label.nationality') }} -
                                            </option>
                                            {!! countriesAsOptions($search_nationality) !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="inputGender" class="control-label sr-only">{{ trans('label.gender') }}</label>
                                        <select id="inputGender" class="form-control" name="gender">
                                            <option value="">
                                                - {{ trans('form.action_select') }} {{ trans('label.gender') }} -
                                            </option>
                                            @foreach(allGenders() as $gender)
                                                <option value="{{ $gender }}"{{ $gender == $search_gender ? ' selected' : '' }}>
                                                    {{ trans('label.gender_'.$gender) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-block">{{ trans('form.action_search') }}</button>
                            </div>
                        </form>
                        <hr>
                        @if($teachers->count()>0)
                            <div class="media-list teacher-list">
                                @foreach($teachers as $teacher)
                                    <div class="media">
                                        <div class="media-left">
                                            <a href="{{ homeUrl('teachers/{id}', ['id' => $teacher->id]) }}">
                                                <img class="width-120" src="{{ $teacher->userProfile->url_avatar_thumb }}" alt="{{ $teacher->userProfile->display_name }}">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <h5>
                                                <a href="{{ homeUrl('teachers/{id}', ['id' => $teacher->id]) }}">
                                                    <strong>{{ $teacher->userProfile->display_name }}</strong>
                                                </a>
                                            </h5>
                                            <p class="help-block">{{ allCountry($teacher->userProfile->nationality, 'name') }}</p>
                                            <p class="big">{{ shorten($teacher->about_me, \Katniss\Everdeen\Utils\AppConfig::TINY_SHORTEN_TEXT_LENGTH) }}</p>
                                            <p class="help-block">#{{ $teacher->topics->implode('name', ' #') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div>
                                {{ $pagination }}
                            </div>
                        @else
                            <div class="box-body">
                                {{ trans('label.list_empty') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection