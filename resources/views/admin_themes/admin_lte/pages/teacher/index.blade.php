@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_teachers_title'))
@section('page_description', trans('pages.admin_teachers_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('teachers') }}">{{ trans('pages.admin_teachers_title') }}</a></li>
    </ol>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            x_modal_delete($('a.delete'), '{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}');
        });
    </script>
@endsection
@section('page_content')
    <div class="row">
        <div class="col-xs-12">
            <div class="margin-bottom">
                <a class="btn btn-primary" href="{{ adminUrl('teachers/create') }}">
                    {{ trans('form.action_add') }} {{ trans_choice('label.teacher_lc', 1) }}
                </a>
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
                    <h3 class="box-title">{{ trans('form.list_of',['name' => trans_choice('label.teacher_lc', 2)]) }}</h3>
                </div><!-- /.box-header -->
                @if($teachers->count()>0)
                    <div class="box-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="order-col-2">#</th>
                                    <th>{{ trans('label.display_name') }}</th>
                                    <th>{{ trans('label.email') }}</th>
                                    <th>Skype ID</th>
                                    <th>{{ trans('label.phone') }}</th>
                                    <th>{{ trans('label.status') }}</th>
                                    <th>{{ trans('form.action') }}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th class="order-col-2">#</th>
                                    <th>{{ trans('label.display_name') }}</th>
                                    <th>{{ trans('label.email') }}</th>
                                    <th>Skype ID</th>
                                    <th>{{ trans('label.phone') }}</th>
                                    <th>{{ trans('label.status') }}</th>
                                    <th>{{ trans('form.action') }}</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach($teachers as $teacher)
                                    <tr>
                                        <td class="order-col-2">{{ ++$start_order }}</td>
                                        <td>{{ $teacher->userProfile->display_name }}</td>
                                        <td>{{ $teacher->userProfile->email }}</td>
                                        <td>{{ $teacher->userProfile->skype_id }}</td>
                                        <td>{{ $teacher->userProfile->phone }}</td>
                                        <td>
                                            @if($teacher->status==\Katniss\Everdeen\Models\Teacher::REQUESTED)
                                                <span class="label label-warning">{{ trans('label.status_requested') }}</span>
                                            @elseif($teacher->status==\Katniss\Everdeen\Models\Teacher::APPROVED)
                                                <span class="label label-success">{{ trans('label.status_approved') }}</span>
                                            @elseif($teacher->status==\Katniss\Everdeen\Models\Teacher::REJECTED)
                                                <span class="label label-danger">{{ trans('label.status_rejected') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ adminUrl('teachers/{id}/edit', ['id'=> $teacher->id]) }}">
                                                {{ trans('form.action_edit') }}
                                            </a>
                                            <a class="delete" href="{{ addRdrUrl(adminUrl('teachers/{id}', ['id'=> $teacher->id])) }}">
                                                {{ trans('form.action_delete') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                             </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">
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
@endsection