@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_app_options_title'))
@section('page_description', trans('pages.admin_app_options_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('app-options') }}">{{ trans('pages.admin_app_options_title') }}</a></li>
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
@section('modals')
    @include('admin_themes.admin_lte.master.common_modals')
@endsection
@section('page_content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('form.list_of',['name'=>trans('pages.admin_app_options_title')]) }}</h3>
                </div><!-- /.box-header -->
                @if($options->count()>0)
                    <div class="box-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th class="order-col-2">#</th>
                                <th>{{ trans('label.key') }}</th>
                                <th>{{ trans('label.value') }}</th>
                                <th>{{ trans('form.action') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th class="order-col-2">#</th>
                                <th>{{ trans('label.key') }}</th>
                                <th>{{ trans('label.value') }}</th>
                                <th>{{ trans('form.action') }}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($options as $option)
                                <tr>
                                    <td class="order-col-1">{{ ++$page_helper->startOrder }}</td>
                                    <td>
                                        {{ $option->key }}
                                    </td>
                                    <td>
                                        {{ shorten($option->rawValue, $value_max_length) }}
                                    </td>
                                    <td>
                                        <a class="delete" href="{{ $query->update('delete', $option->key)->toString() }}">
                                            {{ trans('form.action_delete') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <ul class="pagination pagination-sm no-margin pull-right">
                            <li class="first">
                                <a href="{{ $query->update('page', $page_helper->first)->toString() }}">&laquo;</a>
                            </li>
                            <li class="prev{{ $page_helper->atFirst ? ' disabled':'' }}">
                                <a href="{{ $query->update('page', $page_helper->prev)->toString() }}">&lsaquo;</a>
                            </li>
                            @for($i=$page_helper->start;$i<=$page_helper->end;++$i)
                                <li{!! $i==$page_helper->current ? ' class="active"':'' !!}>
                                    <a href="{{ $query->update('page', $i)->toString() }}">{{ $i }}</a>
                                </li>
                            @endfor
                            <li class="next{{ $page_helper->atLast ? ' disabled':'' }}">
                                <a href="{{ $query->update('page', $page_helper->next)->toString() }}">&rsaquo;</a>
                            </li>
                            <li class="last">
                                <a href="{{ $query->update('page', $page_helper->last)->toString() }}">&raquo;</a>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="box-body">
                        {{ trans('label.list_empty') }}
                    </div>
                @endif
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection