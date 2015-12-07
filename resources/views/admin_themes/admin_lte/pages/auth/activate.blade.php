@extends('admin_themes.admin_lte.master.auth')
@section('auth_type','register')
@if($active)
    @section('box_message', trans('error.success_account_activate', ['url'=> $url])))
@else
    @section('box_message', trans('error.fail_account_activate'))
@endif