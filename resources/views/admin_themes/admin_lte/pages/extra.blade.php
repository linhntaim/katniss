@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans($extra_page_title))
@section('page_description', trans($extra_page_desc))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li>{{ $extra_page_title }}</li>
    </ol>
@endsection
@section('page_content')
    @include($extra_view)
@endsection