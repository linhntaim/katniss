@extends('admin_themes.admin_lte.master.auth')
@section('auth_type','register')
@section('box_message', trans('auth.act_mess'))
@section('auth_form')
    <form action="{{ localizedURL('auth/inactive') }}" method="post">
        {!! csrf_field() !!}
        <div class="row">
            <div class="col-xs-2">
            </div><!-- /.col -->
            <div class="col-xs-8">
                @if($resend)
                    <div class="text-center alert alert-success">
                        {{ trans('auth.act_mess_resent') }}
                    </div>
                @else
                    <button id="btn-resend" type="submit" class="btn btn-primary btn-block btn-flat">
                        {{ trans('auth.act_resent') }}
                    </button>
                @endif
            </div><!-- /.col -->
            <div class="col-xs-2">
            </div><!-- /.col -->
        </div>

    </form>
@endsection