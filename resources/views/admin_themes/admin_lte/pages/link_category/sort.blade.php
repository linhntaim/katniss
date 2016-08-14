@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_link_categories_title'))
@section('page_description', trans('pages.admin_link_categories_desc'))
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
        <li><a href="{{ adminUrl('link-categories') }}">{{ trans('pages.admin_link_categories_title') }}</a></li>
        <li><a href="#">{{ trans('form.action_sort') }}</a></li>
    </ol>
@endsection
@section('extended_scripts')
    <script>
        {!! cdataOpen() !!}
        jQuery(document).ready(function () {
            jQuery('.sortable').sortable({
                placeholder: 'sort-highlight',
                handle: '.handle',
                forcePlaceholderSize: true,
                zIndex: 999999,
                update: function (e, ui) {
                    var items = [];
                    var self = jQuery(this);
                    self.children().each(function () {
                        items.push(jQuery(this).attr('data-item'));
                    });
                    jQuery.post('{{ apiUrl('link-categories/{id}/update-order', ['id' => $category->id]) }}', {
                        _token: '{{ csrf_token() }}',
                        link_ids: items
                    }).done(function (data) {
                        if (data.success) {
                            console.log('success');
                        }
                        else {
                            console.log('fail');
                        }
                    }).fail(function () {
                        console.log('fail');
                    });
                }
            });
            jQuery('a.delete').off('click').on('click', function (e) {
                e.preventDefault();

                var $this = $(this);

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
        <div class="col-md-6">
            <div class="margin-bottom">
                <a role="button" class="btn btn-warning delete" href="{{ adminUrl('link-categories/{id}/delete', ['id'=> $category->id]) }}">
                    {{ trans('form.action_delete') }}
                </a>
                <a role="button" class="btn btn-primary" href="{{ adminUrl('link-categories/{id}/edit', ['id'=> $category->id]) }}">
                    {{ trans('form.action_edit') }}
                </a>
                <a role="button" class="btn btn-primary pull-right" href="{{ adminUrl('link-categories/add') }}">
                    {{ trans('form.action_add') }} {{ trans_choice('label.category_lc', 1) }}
                </a>
            </div>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('form.action_sort') }} {{ trans_choice('label.category_lc', 1) }} - <em>{{ $category->name }}</em></h3>
                </div>
                <div class="box-body">
                    @if($links->count()>0)
                        <ul class="todo-list sortable" data-category="{{ $category->id }}">
                            @foreach($links as $link)
                                <li data-item="{{ $link->id }}">
                                    <span class="handle">
                                        <i class="fa fa-ellipsis-v"></i>
                                        <i class="fa fa-ellipsis-v"></i>
                                    </span>
                                    <span class="text"><a href="{{ $link->link }}">{{ $link->name }}</a></span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>{{ trans('label.list_empty') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection