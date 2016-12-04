@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_widgets_title'))
@section('page_description', trans('pages.admin_widgets_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('widgets') }}">{{ trans('pages.admin_widgets_title') }}</a></li>
    </ol>
@endsection
@section('lib_styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css">
@endsection
@section('extended_styles')
    <style>
        .theme-widget-empty {
            height: 40px;
            background-color: #f4f4f4;
        }
    </style>
@endsection
@section('lib_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
@endsection
@section('extended_scripts')
    <script>
        {!! cdataOpen() !!}
        jQuery(document).ready(function () {
            jQuery('.select2').select2();

            jQuery('.theme-widget-sortable').sortable({
                placeholder: 'sort-highlight',
                handle: '.handle',
                forcePlaceholderSize: true,
                connectWith: '.theme-widget-sortable',
                zIndex: 999999,
                update: function (e, ui) {
                    var items = [];
                    var self = $(this);
                    self.children().each(function () {
                        items.push($(this).attr('data-widget'));
                    });
                    if (items.length == 0) {
                        self.addClass('theme-widget-empty');
                    }
                    else {
                        self.removeClass('theme-widget-empty');
                    }
                    var api = new KatnissApi();
                    api.post('widgets/update-order', {
                        placeholder: self.attr('data-placeholder'),
                        widget_ids: items
                    });
                }
            });

            jQuery('a.delete').off('click').on('click', function (e) {
                e.preventDefault();

                var $this = jQuery(this);

                x_confirm('{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}', function () {
                    window.location.href = $this.attr('href');
                });

                return false;
            });

            var cloneModal = jQuery('#clone-modal');
            jQuery('a.clone').off('click').on('click', function (e) {
                e.preventDefault();

                var $this = $(this);
                var $li = $this.closest('li');
                var widgetId = $li.attr('data-widget');
                var widgetName = $li.children('span.text').text();
                var placeholder = $li.closest('.box').find('.box-title').text();
                cloneModal.find('.clone-widget').text(widgetName);
                cloneModal.find('.from-placeholder').text(placeholder);
                cloneModal.find('[name="id"]').val(widgetId);
                cloneModal.modal('show');

                return false;
            });
        });
        {!! cdataClose() !!}
    </script>
@endsection
@section('modals')
    <div class="modal fade" id="clone-modal" tabindex="-1" role="dialog" aria-labelledby="clone-modal-title">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="clone-modal-title">{{ trans('form.action_clone') }}</h4>
                </div>
                <form method="post" action="{{ adminUrl('widgets/clone') }}?{{ $rdr_param }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="">
                    <div id="clone-modal-content" class="modal-body">
                        <div class="form-group">
                            {{ trans('form.action_clone') }}
                            <strong class="clone-widget"></strong>
                            {{ trans('label.from_lc') }}
                            <strong class="from-placeholder"></strong>
                            {{ trans('label.to_lc') }}:
                        </div>
                        <div class="form-group">
                            <label for="inputClonePlaceHolder">{{ trans_choice('label.theme_placeholder', 1) }}</label>
                            <select id="inputClonePlaceHolder" class="form-control select2" name="placeholder" required
                                    style="width: 100%">
                                @foreach($placeholders as $name => $display_name)
                                    <option value="{{ $name }}">{{ $display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="clone-modal-button" type="submit"
                                class="btn btn-danger">{{ trans('form.action_confirm') }}</button>
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal">{{ trans('form.action_cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('page_content')
    <div class="row">
        <div class="col-xs-12">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <div class="box box-primary">
                <div class="box-body">
                    <form method="post">
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="form-group col-xs-12 col-sm-4">
                                <label for="inputWidget">{{ trans_choice('label.widget', 1) }}</label>
                                <select id="inputWidget" class="form-control select2" name="widget" required
                                        style="width: 100%">
                                    @foreach($widgets as $name => $display_name)
                                        <option value="{{ $name }}">{{ $display_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xs-12 col-sm-4">
                                <label for="inputPlaceHolder">{{ trans_choice('label.theme_placeholder', 1) }}</label>
                                <select id="inputPlaceHolder" class="form-control select2" name="placeholder" required
                                        style="width: 100%">
                                    @foreach($placeholders as $name => $display_name)
                                        <option value="{{ $name }}">{{ $display_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xs-12 col-sm-4">
                                <label class="hidden-xs">&nbsp;</label>
                                <div>
                                    <button type="submit"
                                            class="btn btn-primary">{{ trans('form.action_add') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    <div class="row">
        <?php
        $count_placeholders = count($placeholderNames);
        ?>
        <div class="col-md-6">
            @for($i=0;$i<$count_placeholders;$i+=2)
                <?php
                $placeholderName = $placeholderNames[$i];
                $themeWidgets = $themePlaceholders[$placeholderName];
                ?>
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $placeholders[$placeholderName] }}</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <ul id="placeholder-{{ $i }}"
                            class="todo-list theme-widget-sortable{{ count($themeWidgets)>0 ? '' : ' theme-widget-empty' }}"
                            data-placeholder="{{ $placeholderName }}">
                            @foreach($themeWidgets as $themeWidget)
                                <li class="clearfix" data-widget="{{ $themeWidget->id }}">
                                    <span class="handle">
                                        <i class="fa fa-ellipsis-v"></i>
                                        <i class="fa fa-ellipsis-v"></i>
                                    </span>
                                    <span class="text">{{ $widgets[$themeWidget->name] }}</span>
                                    @if($themeWidget->active)
                                        <span class="label label-success">{{ trans('label.status_activated') }}</span>
                                    @else
                                        <span class="label label-warning">{{ trans('label.status_deactivated') }}</span>
                                    @endif
                                    <div class="tools">
                                        <a href="{{ adminUrl('widgets/{id}/edit', ['id'=> $themeWidget->id]) }}">{{ trans('form.action_edit') }}</a>
                                        |
                                        @if($themeWidget->active)
                                            <a href="{{ adminUrl('widgets/{id}/deactivate', ['id'=> $themeWidget->id]) }}?{{ $rdr_param }}">{{ trans('form.action_deactivate') }}</a>
                                            |
                                        @else
                                            <a href="{{ adminUrl('widgets/{id}/activate', ['id'=> $themeWidget->id]) }}?{{ $rdr_param }}">{{ trans('form.action_activate') }}</a>
                                            |
                                        @endif
                                        <a class="clone" href="#">{{ trans('form.action_clone') }}</a> |
                                        <a class="delete"
                                           href="{{ adminUrl('widgets/{id}/delete', ['id'=> $themeWidget->id]) }}?{{ $rdr_param }}">{{ trans('form.action_delete') }}</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endfor
        </div>
        <div class="col-md-6">
            @for($i=1;$i<$count_placeholders;$i+=2)
                <?php
                $placeholderName = $placeholderNames[$i];
                $themeWidgets = $themePlaceholders[$placeholderName];
                ?>
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $placeholders[$placeholderName] }}</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <ul id="placeholder-{{ $i }}"
                            class="todo-list theme-widget-sortable{{ count($themeWidgets)>0 ? '' : ' theme-widget-empty' }}"
                            data-placeholder="{{ $placeholderName }}">
                            @foreach($themeWidgets as $themeWidget)
                                <li class="clearfix" data-widget="{{ $themeWidget->id }}">
                                    <span class="handle">
                                        <i class="fa fa-ellipsis-v"></i>
                                        <i class="fa fa-ellipsis-v"></i>
                                    </span>
                                    <span class="text">{{ $widgets[$themeWidget->name] }}</span>
                                    @if($themeWidget->active)
                                        <span class="label label-success">{{ trans('label.status_activated') }}</span>
                                    @else
                                        <span class="label label-warning">{{ trans('label.status_deactivated') }}</span>
                                    @endif
                                    <div class="tools">
                                        <a href="{{ adminUrl('widgets/{id}/edit', ['id'=> $themeWidget->id]) }}">{{ trans('form.action_edit') }}</a>
                                        |
                                        @if($themeWidget->active)
                                            <a href="{{ adminUrl('widgets/{id}/deactivate', ['id'=> $themeWidget->id]) }}?{{ $rdr_param }}">{{ trans('form.action_deactivate') }}</a>
                                            |
                                        @else
                                            <a href="{{ adminUrl('widgets/{id}/activate', ['id'=> $themeWidget->id]) }}?{{ $rdr_param }}">{{ trans('form.action_activate') }}</a>
                                            |
                                        @endif
                                        <a class="clone" href="#">{{ trans('form.action_clone') }}</a> |
                                        <a class="delete"
                                           href="{{ adminUrl('widgets/{id}/delete', ['id'=> $themeWidget->id]) }}?{{ $rdr_param }}">{{ trans('form.action_delete') }}</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endfor
        </div>
    </div>
@endsection