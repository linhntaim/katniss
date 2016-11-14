@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_users_title'))
@section('page_description', trans('pages.admin_users_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('users') }}">{{ trans('pages.admin_users_title') }}</a></li>
    </ol>
@endsection
@section('extended_scripts')
    <script>
        {!! cdataOpen() !!}
        jQuery(document).ready(function(){
            jQuery('a.delete').off('click').on('click', function (e) {
                e.preventDefault();

                var $this = jQuery(this);

                x_confirm('{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}', function () {
                    window.location.href = $this.attr('href');
                });

                return false;
            });
        });
        {!! cdataClose() !!}
    </script>
@endsection
@section('page_content')
<div class="row">
    <div class="col-md-12">
        <div class="margin-bottom">
            <a class="btn btn-primary" href="{{ adminUrl('users/add') }}">{{ trans('form.action_add') }} {{ trans_choice('label.user_lc', 1) }}</a>
        </div>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('form.list_of', ['name' => trans_choice('label.user_lc', 2)]) }}</h3>
            </div><!-- /.box-header -->
        @if($users->count()>0)
            <div class="box-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="order-col-2">#</th>
                            <th>{{ trans('label.display_name') }}</th>
                            <th>{{ trans('label.user_name') }}</th>
                            <th>{{ trans('label.email') }}</th>
                            <th>{{ trans_choice('label.role', 2) }}</th>
                            <th>{{ trans('label.status') }}</th>
                            <th>{{ trans('form.action') }}</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="order-col-2">#</th>
                            <th>{{ trans('label.display_name') }}</th>
                            <th>{{ trans('label.user_name') }}</th>
                            <th>{{ trans('label.email') }}</th>
                            <th>{{ trans_choice('label.role', 2) }}</th>
                            <th>{{ trans('label.status') }}</th>
                            <th>{{ trans('form.action') }}</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($users as $user)
                        <tr id="user-{{ $user->id }}">
                            <td class="order-col-2">{{ ++$page_helper->startOrder }}</td>
                            <td>{{ $user->display_name }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                {{ $user->roles->implode('display_name', ', ') }}
                            </td>
                            <td>
                                @if($user->active)
                                    <span class="label label-success">{{ trans('label.status_activated') }}</span>
                                @else
                                    <span class="label label-danger">{{ trans('label.status_not_activated') }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ adminUrl('users/{id}/edit', ['id'=> $user->id]) }}">{{ trans('form.action_edit') }}</a>
                                <a class="delete" href="{{ adminUrl('users/{id}/delete', ['id'=> $user->id])}}?{{ $rdr_param }}">
                                    {{ trans('form.action_delete') }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div><!-- /.box-body -->
            <div class="box-footer clearfix">
                <ul class="pagination pagination-sm no-margin pull-right">
                    <li class="first">
                        <a href="{{ $users_query->update('page', $page_helper->first)->toString() }}">&laquo;</a>
                    </li>
                    <li class="prev{{ $page_helper->atFirst ? ' disabled':'' }}">
                        <a href="{{ $users_query->update('page', $page_helper->prev)->toString()}}">&lsaquo;</a>
                    </li>
                    @for($i=$page_helper->start;$i<=$page_helper->end;++$i)
                        <li{!! $i==$page_helper->current ? ' class="active"':'' !!}>
                            <a href="{{ $users_query->update('page', $i)->toString() }}">{{ $i }}</a>
                        </li>
                    @endfor
                    <li class="next{{ $page_helper->atLast ? ' disabled':'' }}">
                        <a href="{{ $users_query->update('page', $page_helper->next)->toString() }}">&rsaquo;</a>
                    </li>
                    <li class="last">
                        <a href="{{ $users_query->update('page', $page_helper->last)->toString() }}">&raquo;</a>
                    </li>
                </ul>
            </div>
        @else
            <div class="box-body">
                {{ trans('label.list_empty') }}
            </div>
        @endif
        </div><!-- /.box -->
    </div>
</div>
@endsection