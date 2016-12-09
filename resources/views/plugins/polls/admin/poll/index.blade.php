@section('extended_scripts')
    <script>
        {!! cdataOpen() !!}
        $(function () {
            x_modal_delete($('a.delete'), '{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}');
        });
        {!! cdataClose() !!}
    </script>
@endsection
<div class="row">
    <div class="col-md-12">
        <div class="margin-bottom">
            <a class="btn btn-primary" href="{{ addExtraUrl('admin/polls/create', adminUrl('extra')) }}">
                {{ trans('form.action_add') }} {{ trans_choice('polls.poll_lc', 1) }}
            </a>
        </div>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('form.list_of', ['name' => trans_choice('polls.poll', 2)]) }}</h3>
            </div><!-- /.box-header -->
            @if($polls->count()>0)
                <div class="box-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th class="order-col-2">#</th>
                            <th>{{ trans('label.name') }}</th>
                            <th>{{ trans('label.description') }}</th>
                            <th>{{ trans('polls.multi_choice') }}</th>
                            <th>{{ trans('form.action') }}</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th class="order-col-2">#</th>
                            <th>{{ trans('label.name') }}</th>
                            <th>{{ trans('label.description') }}</th>
                            <th>{{ trans('polls.multi_choice') }}</th>
                            <th>{{ trans('form.action') }}</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($polls as $poll)
                            <tr>
                                <td class="order-col-1">{{ ++$start_order }}</td>
                                <td>{{ $poll->name }}</td>
                                <td>{{ $poll->description }}</td>
                                <td>{{ $poll->multi_choice ? trans('label.enabled') : trans('label.disabled') }}</td>
                                <td>
                                    <a href="{{ addExtraUrl('admin/polls/id', adminUrl('extra')) . '&id=' . $poll->id }}">
                                        {{ trans('form.action_edit') }}
                                    </a>
                                    <a class="delete"
                                       href="{{ addRdrUrl(addExtraUrl('admin/polls/id', adminUrl('extra')) . '&id=' . $poll->id) }}">
                                        {{ trans('form.action_delete') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    {{ $pagination }}
                </div>
            @else
                <div class="box-body">
                    {{ trans('label.list_empty') }}
                </div>
            @endif
        </div><!-- /.box -->
    </div>
</div>