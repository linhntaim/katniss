@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_links_title'))
@section('page_description', trans('pages.admin_links_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('links') }}">{{ trans('pages.admin_links_title') }}</a></li>
    </ol>
@endsection
@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('select2-js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        $(function () {
            $('.select2').select2();
            x_modal_delete($('a.delete'), '{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}');
        });
    </script>
@endsection
@section('modals')
    <div class="modal fade" id="search-modal" tabindex="false" role="dialog" aria-labelledby="search-modal-title">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="search-modal-title">{{ trans('form.action_search') }}</h4>
                </div>
                <form>
                    <div id="search-modal-content" class="modal-body">
                        <div class="form-group">
                            <label for="inputCategories">{{ trans_choice('label.category', 2) }}</label>
                            <select id="inputCategories" class="form-control select2" name="categories[]" multiple="multiple"
                                    data-placeholder="{{ trans('form.action_select') }} {{ trans_choice('label.category', 2) }}" style="width: 100%;">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"{{ in_array($category->id, $search_categories) ? ' selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ trans('form.action_close') }}</button>
                        <a role="button" class="btn btn-warning {{ $on_searching ? '' : 'hide' }}" href="{{ $clear_search_url }}">
                            {{ trans('form.action_clear_search') }}
                        </a>
                        <button type="submit" class="btn btn-primary">{{ trans('form.action_search') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('page_content')
    <div class="row">
        <div class="col-xs-12">
            <div class="margin-bottom">
                <a class="btn btn-primary" href="{{ adminUrl('links/create') }}">
                    {{ trans('form.action_add') }} {{ trans_choice('label.link_lc', 1) }}
                </a>
            </div>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('form.list_of', ['name' => trans_choice('label.link_lc', 2)]) }}</h3>
                    <div class="box-tools">
                        <button type="button" class="btn {{ $on_searching ? 'btn-warning' : 'btn-primary' }} btn-sm" data-toggle="modal" data-target="#search-modal">
                            <i class="fa fa-search"></i> {{ trans('form.action_search') }}
                        </button>
                    </div>
                </div><!-- /.box-header -->
            @if($links->count()>0)
                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="order-col-2">#</th>
                                <th>{{ trans('label.name') }}</th>
                                <th>{{ trans('label.url') }}</th>
                                <th>{{ trans('label.picture') }}</th>
                                <th>{{ trans_choice('label.category', 2) }}</th>
                                <th>{{ trans('form.action') }}</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="order-col-2">#</th>
                                <th>{{ trans('label.name') }}</th>
                                <th>{{ trans('label.url') }}</th>
                                <th>{{ trans('label.picture') }}</th>
                                <th>{{ trans_choice('label.category', 2) }}</th>
                                <th>{{ trans('form.action') }}</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($links as $link)
                                <tr>
                                    <td class="order-col-2">{{ ++$start_order }}</td>
                                    <td>{{ $link->name }}</td>
                                    <td>
                                        <a class="open-window" href="{{ $link->url }}"
                                           data-name="_blank" data-width="800" data-height="600">
                                            <i class="fa fa-external-link"></i>
                                        </a> &nbsp;
                                        {{ $link->url }}
                                    </td>
                                    <td>
                                        @if(!empty($link->image))
                                            <a class="open-window" href="{{ $link->image }}"
                                                data-name="_blank" data-width="800" data-height="600">
                                                <i class="fa fa-external-link"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ $link->categories->implode('name', ', ') }}</td>
                                    <td>
                                          <a href="{{ adminUrl('links/{id}/edit', ['id'=> $link->id]) }}">
                                              {{ trans('form.action_edit') }}
                                          </a>
                                          <a class="delete" href="{{ addRdrUrl(adminUrl('links/{id}', ['id'=> $link->id])) }}">
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
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection