@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_widgets_title'))
@section('page_description', trans('pages.admin_widgets_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('widgets') }}">{{ trans('pages.admin_widgets_title') }}</a>
        <li><a href="#">{{ trans('form.action_edit') }}</a></li>
    </ol>
@endsection
@section('page_content')
    <div class="row">
        <form method="post" action="{{ adminUrl('widgets/update') }}">
            {!! csrf_field() !!}
            <input type="hidden" name="id" value="{{ $themeWidget->id }}">
            <div class="col-xs-12">
                <h4 class="box-title">{{ trans('form.action_edit') }} {{ $widget->getDisplayName() }}</h4>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @include($widget_view)

                <div class="margin-bottom">
                    <button class="btn btn-primary" type="submit">{{ trans('form.action_save') }}</button>
                    <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                    <a role="button" class="btn btn-warning pull-right" href="{{ adminUrl('widgets') }}">{{ trans('form.action_cancel') }}</a>
                </div>
            </div>
        </form>
    </div>
@endsection