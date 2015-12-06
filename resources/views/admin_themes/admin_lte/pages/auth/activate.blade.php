@extends('admin_themes.admin_lte.master.auth')
@section('auth_type','register')
@if($active)
    @section('box_message', trans('auth.act_success', array('url'=> $url, 'name'=> $name)))
@else
    @section('box_message', trans('auth.act_failed'))
@endif