@extends('home_themes.wow_skype.master.master')
@section('lib_styles')
    <link rel="stylesheet" href="{{ libraryAsset('bootstrap-datetimepicker/css/bootstrap-datetimepicker.css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ libraryAsset('moment/moment.min.js') }}"></script>
    <script src="{{ libraryAsset('moment/locale/' . $site_locale . '.js') }}"></script>
    <script src="{{ libraryAsset('bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ libraryAsset('inputmask/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ libraryAsset('inputmask/inputmask.binding.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            function renderClassTime(classTime) {
                return '<li id="class-time-' + classTime.id + '" class="media class-time-item month-' + classTime.month_year_start_at + '" ' +
                    'data-id="' + classTime.id + '" data-subject="' + classTime.subject + '" data-content="' + classTime.content + '">' +
                    '<div class="media-line"></div>' +
                    '<div class="media-left"><span>' + classTime.order + '</span></div>' +
                    '<div class="media-body">' +
                    '<div class="media-heading">' +
                    @if($can_classroom_edit)
                        '<a class="edit-class-time big pull-right" title="{{ trans('form.action_edit') }} {{ trans_choice('label.class_time', 1) }}" href="#">' +
                        '<i class="fa fa-edit"></i>' +
                        '</a>' +
                    @endif
                    '<span class="class-time-subject">' + classTime.subject + '</span>' +
                    '</div>' +
                    '<div class="help-block">' + classTime.duration + ' - {{ trans('label.start_at') }} ' + classTime.start_at + '</div>' +
                    '<div class="bg-warning padding-10 class-time-content' + (classTime.content != '' ? '' : ' hide') + '">' +
                    classTime.html_content +
                    '</div>' +
                    '</div>' +
                    '</li>';
            }

            function renderTotal(sumHours, monthYear, transMonthYear) {
                var formatter = new NumberFormatHelper();
                return '<li class="media class-time-month" data-hours="' + sumHours + '" ' +
                    'data-target=".month-' + monthYear + '">' +
                    '<div class="media-line"></div>' +
                    '<div class="media-left">' +
                    '<span class="info">Σ</span>' +
                    '</div>' +
                    '<div class="media-body">' +
                    '<div class="media-heading text-info">' + '{{ trans('label.statistics') }} - ' + transMonthYear + '</div>' +
                    '<div>' +
                    '{{ trans('label.total') }}: ' +
                    '<span class="sum-hours">' +
                    formatter.format(sumHours) +
                    (sumHours == 1 ? ' {{ trans_choice('label.hour_lc', 1) }}' : ' {{ trans_choice('label.hour_lc', 2) }}') +
                    '</span>' +
                    '</div>' +
                    '</div>' +
                    '</li>';
            }

            $('.date-time-picker').datetimepicker({
                format: '{{ $date_js_format }}'
            });

            var $classroomName = $('#classroom-name');
            var $updateClassroomNameForm = $('#update-classroom-name-form');
            var $updateClassroomNameFormLoading = $updateClassroomNameForm.find('.update-classroom-name-form-loading');
            var $updateClassroomNameModal = $('#update-classroom-name-modal');
            var $addClassTimeList = $('#class-time-list');
            var $addClassTimeLastItem = $addClassTimeList.children('.class-time-add');
            var $addClassTimeFirstItem = $addClassTimeList.children('.class-time-previous');
            var $addClassTimeForm = $('#add-class-time-form');
            var $addClassTimeFormLoading = $updateClassroomNameForm.find('.add-class-time-form-loading');
            var $addClassTimeModal = $('#add-class-time-modal');
            var $updateClassTimeForm = $('#update-class-time-form');
            var $updateClassTimeFormLoading = $updateClassroomNameForm.find('.update-class-time-form-loading');
            var $updateClassTimeModal = $('#update-class-time-modal');

            $updateClassroomNameModal.on('show.bs.modal', function () {
                $updateClassroomNameForm.find('[name="name"]').val($classroomName.text());
            });

            $updateClassroomNameForm.on('submit', function (e) {
                e.preventDefault();

                $updateClassroomNameFormLoading.removeClass('hide');

                var $alert = $updateClassroomNameForm.find('.alert');
                var $submit = $updateClassroomNameForm.find('[type="submit"]');
                $alert.addClass('hide');
                $submit.prop('disabled', true);
                var api = new KatnissApi(true);
                api.put('classrooms/{{ $classroom->id }}', {
                    only_name: 1,
                    name: $updateClassroomNameForm.find('[name="name"]').val()
                }, function (failed, data, messages) {
                    if (failed) {
                        $alert.removeClass('hide').html(messages.all().join('<br>'));
                    }
                    else {
                        $updateClassroomNameModal.modal('hide');

                        $classroomName.text(data.classroom.name);
                    }
                }, function () {
                    $alert.removeClass('hide').html('{{ trans('error.add_class_time_failed') }}');
                }, function () {
                    $submit.prop('disabled', false);
                    $updateClassroomNameFormLoading.addClass('hide');
                });
            });

            $addClassTimeModal.on('hide.bs.modal', function () {
                $addClassTimeForm.find('.form-control').val('');
            });

            $addClassTimeForm.on('submit', function (e) {
                e.preventDefault();

                $addClassTimeFormLoading.removeClass('hide');

                var $alert = $addClassTimeForm.find('.alert');
                var $submit = $addClassTimeForm.find('[type="submit"]');
                $alert.addClass('hide');
                $submit.prop('disabled', true);
                var api = new KatnissApi(true);
                api.post('class-times', {
                    classroom: '{{ $classroom->id }}',
                    subject: $addClassTimeForm.find('[name="subject"]').val(),
                    content: $addClassTimeForm.find('[name="content"]').val(),
                    duration: $addClassTimeForm.find('[name="duration"]').val(),
                    start_at: $addClassTimeForm.find('[name="start_at"]').val()
                }, function (failed, data, messages) {
                    if (failed) {
                        $alert.removeClass('hide').html(messages.all().join('<br>'));
                    }
                    else {
                        $addClassTimeModal.modal('hide');

                        var monthYearStartAt = data.class_time.month_year_start_at;
                        if ($('.class-time-item.month-' + monthYearStartAt).length > 0) {
                            var $prev = $addClassTimeLastItem.prev();
                            $prev.before(renderClassTime(data.class_time));
                            var hours = parseFloat($prev.attr('data-hours')) + data.class_time.hours;
                            var formatter = new NumberFormatHelper();
                            $prev.attr('data-hours', hours).find('.sum-hours').text(
                                formatter.format(hours) + (hours == 1 ? ' {{ trans_choice('label.hour_lc', 1) }}' : ' {{ trans_choice('label.hour_lc', 2) }}')
                            );
                        }
                        else {
                            $addClassTimeLastItem.before(renderClassTime(data.class_time));
                            $addClassTimeLastItem.before(renderTotal(
                                data.class_time.hours,
                                data.class_time.month_year_start_at,
                                data.class_time.trans_month_year_start_at
                            ));
                        }
                    }
                }, function () {
                    $alert.removeClass('hide').html('{{ trans('error.add_class_time_failed') }}');
                }, function () {
                    $submit.prop('disabled', false);
                    $addClassTimeFormLoading.addClass('hide');
                });
            });

            $(document).on('click', '.edit-class-time', function (e) {
                e.preventDefault();

                var $classTime = $(this).closest('.class-time-item');
                $updateClassTimeForm.find('[name="id"]').val($classTime.attr('data-id'));
                $updateClassTimeForm.find('[name="subject"]').val($classTime.attr('data-subject'));
                $updateClassTimeForm.find('[name="content"]').val($classTime.attr('data-content'));

                $updateClassTimeModal.modal('show');
            });
            $updateClassTimeForm.on('submit', function (e) {
                e.preventDefault();

                $updateClassTimeFormLoading.removeClass('hide');

                var $alert = $updateClassTimeForm.find('.alert');
                var $submit = $updateClassTimeForm.find('[type="submit"]');
                $alert.addClass('hide');
                $submit.prop('disabled', true);
                var api = new KatnissApi(true);
                api.put('class-times/' + $updateClassTimeForm.find('[name="id"]').val(), {
                    subject: $updateClassTimeForm.find('[name="subject"]').val(),
                    content: $updateClassTimeForm.find('[name="content"]').val()
                }, function (failed, data, messages) {
                    if (failed) {
                        $alert.removeClass('hide').html(messages.all().join('<br>'));
                    }
                    else {
                        $updateClassTimeModal.modal('hide');

                        var $classTime = $('#class-time-' + data.class_time.id);
                        $classTime.attr('data-subject', data.class_time.subject);
                        $classTime.attr('data-content', data.class_time.content);
                        $classTime.find('.class-time-subject').text(data.class_time.subject);
                        $classTime.find('.class-time-content').html(data.class_time.html_content);
                        if (data.class_time.content != '') {
                            $classTime.find('.class-time-content').removeClass('hide').html(data.class_time.html_content);
                        }
                        else {
                            $classTime.find('.class-time-content').addClass('hide');
                        }
                    }
                }, function () {
                    $alert.removeClass('hide').html('{{ trans('error.add_class_time_failed') }}');
                }, function () {
                    $submit.prop('disabled', false);
                    $updateClassTimeFormLoading.addClass('hide');
                });
            });

            var $previousMonthClassTimes = $('.previous-month-class-times');
            var $previousMonthClassTimesLoading = $('.previous-month-class-times-loading');
            $previousMonthClassTimes.on('click', function (e) {
                e.preventDefault();

                $previousMonthClassTimesLoading.removeClass('hide');

                var $this = $(this);
                var api = new KatnissApi(true);
                api.get('classrooms/{{ $classroom->id }}', {
                    monthly_class_times: 1,
                    year: $this.attr('data-year'),
                    month: $this.attr('data-month')
                }, function (failed, data, messages) {
                    if (!failed) {
                        $addClassTimeFirstItem.after(renderTotal(
                            data.stats.sum_hours,
                            data.stats.month_year,
                            data.stats.trans_month_year
                        ));
                        var classTime;
                        for (var i in data.class_times) {
                            classTime = data.class_times[i];
                            classTime.order = data.class_time_order_end--;
                            $addClassTimeFirstItem.after(renderClassTime(classTime));
                        }
                        if (data.has_previous_month_class_times) {
                            $previousMonthClassTimes.attr('data-year', data.previous_year)
                                .attr('data-month', data.previous_month);
                        }
                        else {
                            $addClassTimeFirstItem.remove();
                        }
                    }
                }, null, function () {
                    $previousMonthClassTimesLoading.addClass('hide');
                });
            });

            x_modal_put($('.classroom-close'), '{{ trans('form.action_close') }}', '{{ trans('label.wanna_close', ['name' => '']) }}');
        });
    </script>
@endsection
@section('main_content')
    <div id="page-classrooms-detail">
        <ol class="breadcrumb big">
            <li>
                <a href="{{ $classrooms_url }}">{{ trans_choice('label.classroom', 2) }}</a>
            </li>
            <li><span id="classroom-name">{{ $classroom->name }}</span>
                @if($can_classroom_edit)
                    &nbsp;
                    <a data-toggle="modal" data-target="#update-classroom-name-modal"
                       title="{{ trans('form.action_edit') }} {{ trans('label.name') }}" href="#">
                        <i class="fa fa-edit"></i>
                    </a>
                @endif
            </li>
        </ol>
        <div class="row">
            <div class="col-xs-12 col-md-3 margin-bottom-10">
                <div>
                    <strong>{{ trans('label.class_duration') }}:</strong>
                    {{ $classroom->duration }} {{ trans_choice('label.hour_lc', $classroom->hours) }}
                </div>
                <div>
                    <strong>{{ trans('label.class_spent_time') }}:</strong>
                    {{ $classroom->spentTimeDuration }} {{ trans_choice('label.hour_lc', $classroom->spentTime) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3 margin-bottom-10">
                <div class="media">
                    <div class="media-left">
                        <img class="width-32" src="{{ $teacher->userProfile->url_avatar_thumb }}">
                    </div>
                    <div class="media-body">
                        <div><strong>{{ $teacher->userProfile->display_name }}</strong></div>
                        <div>{{ trans_choice('label.teacher', 1) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3 margin-bottom-10">
                <div class="media">
                    <div class="media-left">
                        <img class="width-32" src="{{ $student->userProfile->url_avatar_thumb }}">
                    </div>
                    <div class="media-body">
                        <div><strong>{{ $student->userProfile->display_name }}</strong></div>
                        <div>{{ trans_choice('label.student', 1) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3 margin-bottom-10">
                <div class="media">
                    <div class="media-left">
                        <img class="width-32" src="{{ $supporter->url_avatar_thumb }}">
                    </div>
                    <div class="media-body">
                        <div><strong>{{ $supporter->display_name }}</strong></div>
                        <div>{{ trans_choice('label.supporter', 1) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <ul id="class-time-list" class="media-list step-list margin-top-30">
            @if($has_previous_month_class_times)
                <li class="media class-time-previous">
                    <div class="media-line"></div>
                    <div class="media-left">
                        <span class="success role-button previous-month-class-times"
                              data-year="{{ $previous_year }}" data-month="{{ $previous_month }}">
                            <i class="fa fa-chevron-up" style="position:relative;top:-2px"></i>
                        </span>
                    </div>
                    <div class="media-body">
                        <div class="media-heading text-success">
                            <span class="role-button previous-month-class-times"
                                  data-year="{{ $previous_year }}" data-month="{{ $previous_month }}">
                                {{ trans('form.action_view') }} {{ trans('label.previous_month_lc') }}
                            </span>
                            <span class="previous-month-class-times-loading hide">
                                <i class="fa fa-refresh fa-spin fa-fw"></i>
                            </span>
                        </div>
                    </div>
                </li>
            @endif
            @if($class_times->count() > 0)
                @foreach($class_times as $class_time)
                    <li id="class-time-{{ $class_time->id }}"
                        class="media class-time-item month-{{ date('m-Y', strtotime($class_time->start_at)) }}"
                        data-id="{{ $class_time->id }}" data-subject="{{ $class_time->subject }}" data-content="{{ $class_time->content }}">
                        <div class="media-line"></div>
                        <div class="media-left">
                            <span>{{ $class_time_order_start++ }}</span>
                        </div>
                        <div class="media-body">
                            <div class="media-heading">
                                @if($can_classroom_edit)
                                    <a class="edit-class-time big pull-right"
                                       title="{{ trans('form.action_edit') }} {{ trans_choice('label.class_time', 1) }}" href="#">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                @endif
                                <span class="class-time-subject">{{ $class_time->subject }}</span>
                            </div>
                            <div class="help-block">
                                {{ $class_time->duration }} {{ trans_choice('label.hour_lc', $class_time->hours) }}
                                - {{ trans('label.start_at') }} {{ $class_time->inverseFullFormattedStartAt }}
                            </div>
                            <div class="bg-warning padding-10 class-time-content{{ !empty($class_time->content) ? '' : ' hide' }}">
                                {!! $class_time->htmlContent !!}
                            </div>
                        </div>
                    </li>
                @endforeach
                    <?php $sum_hours = $class_times->sum('hours') ?>
                    <li class="media class-time-month" data-hours="{{ $sum_hours }}"
                        data-target=".month-{{ date('m-Y', strtotime($class_times[0]->start_at)) }}">
                        <div class="media-line"></div>
                        <div class="media-left">
                            <span class="info">Σ</span>
                        </div>
                        <div class="media-body">
                            <div class="media-heading text-info">
                                {{ trans('label.statistics') }} - {{ transMonthYear($class_times[0]->start_at) }}
                            </div>
                            <div>
                                {{ trans('label.total') }}:
                                <span class="sum-hours">
                                    {{ toFormattedNumber($sum_hours) }} {{ trans_choice('label.hour_lc', $sum_hours) }}
                                </span>
                            </div>
                        </div>
                    </li>
            @endif
            @if($can_classroom_edit && $classroom->spentTime < $classroom->hours)
                <li class="media class-time-add">
                    <div class="media-line"></div>
                    <div class="media-left">
                        <span class="success role-button" data-toggle="modal" data-target="#add-class-time-modal">
                            <i class="fa fa-plus"></i>
                        </span>
                    </div>
                    <div class="media-body">
                        <div class="media-heading text-success">
                            <span class="role-button" data-toggle="modal" data-target="#add-class-time-modal">
                                {{ trans('form.action_add') }} {{ trans_choice('label.class_time', 1) }}
                            </span>
                        </div>
                    </div>
                </li>
            @endif
        </ul>
        @if($can_classroom_close)
            <div class="bg-warning text-warning padding-15 text-center margin-top-30 role-button classroom-close"
                 data-put="{{ addRdrUrl(homeUrl('classrooms/{id}', ['id'=> $classroom->id]) . '?close=1') }}">
                {{ trans('label.classroom_ready_to_close_message') }}
            </div>
        @elseif(!$classroom->isOpening)
            <div class="bg-danger text-danger padding-15 text-center margin-top-30">
                {{ trans('label.classroom_was_closed') }}
            </div>
        @endif
    </div>
@endsection
@section('modals')
    <div class="modal fade" id="update-classroom-name-modal" tabindex="-1" role="dialog" aria-labelledby="update-classroom-name-modal-title">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="update-classroom-name-modal-title">
                        {{ trans('form.action_edit') }} {{ trans_choice('label.classroom', 1) }}
                    </h4>
                </div>
                <form id="update-classroom-name-form" method="post" action="{{ webApiUrl('classrooms/{id}', ['id' => $classroom->id]) }}">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <input type="hidden" name="only_name" value="1">
                    <div id="update-classroom-name-modal-content" class="modal-body">
                        <div class="alert alert-danger hide"></div>
                        <div class="form-group">
                            <label for="inputName" class="control-label required">{{ trans('label.name') }}</label>
                            <input type="text" placeholder="{{ trans('label.name') }}"
                                   class="form-control" id="inputName" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ trans('form.action_cancel') }}</button>
                        <span class="update-classroom-name-form-loading hide">
                            <i class="fa fa-refresh fa-spin fa-fw"></i>
                        </span>
                        <button type="submit" class="btn btn-success">{{ trans('form.action_save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="add-class-time-modal" tabindex="-1" role="dialog" aria-labelledby="add-class-time-modal-title">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="add-class-time-modal-title">
                        {{ trans('form.action_add') }} {{ trans_choice('label.class_time', 1) }}
                    </h4>
                </div>
                <form id="add-class-time-form" method="post" action="{{ webApiUrl('class-times') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="classroom" value="{{ $classroom->id }}">
                    <div id="add-class-time-modal-content" class="modal-body">
                        <div class="alert alert-danger hide"></div>
                        <div class="form-group">
                            <label for="inputSubject" class="control-label required">{{ trans('label.subject') }}</label>
                            <input type="text" placeholder="{{ trans('label.subject') }}"
                                   class="form-control" id="inputSubject" name="subject" required>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="inputDuration" class="control-label required">{{ trans('label.class_duration') }}</label>
                                    <div class="input-group">
                                        <input type="text" placeholder="{{ trans('label.class_duration') }}" value="{{ old('duration') }}"
                                               class="form-control" id="inputDuration" name="duration" required
                                               data-inputmask="'alias':'decimal','radixPoint':'{{ $number_format_chars[0] }}','groupSeparator':'{{ $number_format_chars[1] }}','autoGroup':true,'integerDigits':6,'digits':2,'digitsOptional':false,'placeholder':'0{{ $number_format_chars[0] }}00'">
                                        <span class="input-group-addon">{{ trans_choice('label.hour_lc', 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="inputStartAt" class="control-label required">{{ trans('label.start_at') }} ({{ $date_js_format }})</label>
                                    <input type="text" placeholder="{{ trans('label.start_at') }}" value="{{ old('start_at') }}"
                                           data-inputmask="'mask':'9999-99-99 99:99'"
                                           class="form-control date-time-picker" name="start_at" id="inputStartAt" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputContent" class="control-label">{{ trans('label.content') }}</label>
                            <textarea rows="6" class="form-control" id="inputContent" name="content"
                                      placeholder="{{ trans('label.content') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ trans('form.action_cancel') }}</button>
                        <span class="add-class-time-form-loading hide">
                            <i class="fa fa-refresh fa-spin fa-fw"></i>
                        </span>
                        <button type="submit" class="btn btn-success">{{ trans('form.action_add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="update-class-time-modal" tabindex="-1" role="dialog" aria-labelledby="update-class-time-modal-title">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="update-class-time-modal-title">
                        {{ trans('form.action_edit') }} {{ trans_choice('label.class_time', 1) }}
                    </h4>
                </div>
                <form id="update-class-time-form" method="post">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <input type="hidden" name="id">
                    <div id="update-class-time-modal-content" class="modal-body">
                        <div class="alert alert-danger hide"></div>
                        <div class="form-group">
                            <label for="inputSubject" class="control-label required">{{ trans('label.subject') }}</label>
                            <input type="text" placeholder="{{ trans('label.subject') }}"
                                   class="form-control" id="inputSubject" name="subject" required>
                        </div>
                        <div class="form-group">
                            <label for="inputContent" class="control-label">{{ trans('label.content') }}</label>
                            <textarea rows="6" class="form-control" id="inputContent" name="content"
                                      placeholder="{{ trans('label.content') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ trans('form.action_cancel') }}</button>
                        <span class="update-class-time-form-loading hide">
                            <i class="fa fa-refresh fa-spin fa-fw"></i>
                        </span>
                        <button type="submit" class="btn btn-success">{{ trans('form.action_save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection