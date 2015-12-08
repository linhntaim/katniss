@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_dashboard_title'))
@section('page_description', trans('pages.admin_dashboard_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
    </ol>
@endsection
@section('page_content')
@endsection