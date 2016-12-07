@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_articles_title'))
@section('page_description', trans('pages.admin_articles_desc'))
@section('page_breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
    <li><a href="{{ adminUrl('articles') }}">{{ trans('pages.admin_articles_title') }}</a></li>
    <li><a href="#">{{ trans('form.action_edit') }}</a></li>
</ol>
@endsection
@section('lib_styles')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css">
@endsection
@section('lib_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
    <script src="{{ libraryAsset('ckeditor-4.5.5/ckeditor.js') }}"></script>
    <script src="{{ libraryAsset('ckeditor-4.5.5/adapters/jquery.js') }}"></script>
@endsection
@section('extended_scripts')
    @include('file_manager.open_documents_script')
    <script>
        {!! cdataOpen() !!}
        $(function () {
            $('.select2').select2();
            $('.slug-from').each(function () {
                var $this = $(this);
                $this.registerSlugTo($this.closest('.tab-pane').find('.slug'));
            });
            var $slug = $('.slug');
            $slug.registerSlug();
            $('form.check-slug').on('submit', function () {
                var slugs = [];
                var unique = true;
                $slug.each(function () {
                    var slug = $(this).val();
                    if (slugs.indexOf(slug) != -1) {
                        unique = false;
                    }
                    else if (slug.trim().length != 0) {
                        slugs.push(slug);
                    }
                });
                if (!unique) {
                    x_modal_alert('{{ trans('validation.unique', ['attribute' => 'slug']) }}');
                    return false;
                }
            });
            $('.ck-editor').ckeditor({
                language: '{{ $site_locale }}',
                filebrowserBrowseUrl: '{{ meUrl('documents/for/ckeditor') }}',
                filebrowserFlashBrowseUrl: '{{ meUrl('documents/for/ckeditor') }}?custom_type=flash',
                filebrowserFlashUploadUrl: '{{ meUrl('documents/for/ckeditor') }}?custom_type=flash',
                filebrowserImageBrowseLinkUrl: '{{ meUrl('documents/for/ckeditor') }}?custom_type=images',
                filebrowserImageBrowseUrl: '{{ meUrl('documents/for/ckeditor') }}?custom_type=images',
                customConfig: '{{ libraryAsset('ckeditor-4.5.5/config_typical.js') }}'
            });
            x_modal_delete($('a.delete'), '{{ trans('form.action_delete') }}', '{{ trans('label.wanna_delete', ['name' => '']) }}');
        });
        {!! cdataClose() !!}
    </script>
@endsection
@section('page_content')
    <form method="post" action="{{ adminUrl('articles/{id}', ['id'=> $article->id]) }}">
        {{ csrf_field() }}
        {{ method_field('put') }}
        <div class="row">
            <div class="col-xs-12">
                <div class="margin-bottom">
                    <a class="btn btn-warning delete" href="{{ adminUrl('articles/{id}', ['id'=> $article->id]) }}?{{ $rdr_param }}">
                        {{ trans('form.action_delete') }}
                    </a>
                    <a class="btn btn-primary pull-right" href="{{ adminUrl('articles/create') }}">
                        {{ trans('form.action_add') }} {{ trans_choice('label.article', 1) }}
                    </a>
                </div>
                <h4 class="box-title">{{ trans('form.action_edit') }} {{ trans_choice('label.article_lc', 1) }}</h4>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                @if(!empty($templates))
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <div class="form-group">
                                <label for="inputTemplate">{{ trans('label.post_template') }}</label>
                                <select id="inputTemplate" class="form-control select2" name="template" style="width: 100%;"
                                        data-placeholder="{{ trans('form.action_select') }} {{ trans('label.post_template') }}">
                                    <option value="0">[{{ trans('label.not_set') }}]</option>
                                    @foreach($templates as $templateValue => $templateText)
                                        <option value="{{ $templateValue }}"{{ $templateValue == $article->template ? ' selected' : '' }}>
                                            {{ $templateText }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-group">
                            <label for="inputLinkCategories">{{ trans_choice('label.category', 2) }}</label>
                            <select id="inputLinkCategories" class="form-control select2" name="categories[]" multiple="multiple"
                                    data-placeholder="{{ trans('form.action_select') }} {{ trans_choice('label.category', 2) }}" style="width: 100%;">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"{{ $article_categories->contains('id', $category->id) ? ' selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputFeaturedImage">
                        {{ trans('label.picture') }}
                        @if(!empty($article->featured_image))
                            (<a class="open-window" href="{{ $article->featured_image }}"
                               data-name="_blank" data-width="800" data-height="600">
                                <i class="fa fa-external-link"></i>
                            </a>)
                        @endif
                    </label>
                    <input class="form-control image-from-documents" id="inputFeaturedImage" name="featured_image"
                           placeholder="{{ trans('label.picture') }}" type="text" value="{{ $article->featured_image }}">
                </div>
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        @foreach(supportedLocalesAsInputTabs() as $locale => $properties)
                            <li{!! $locale == $site_locale ? ' class="active"' : '' !!}>
                                <a href="#{{ localeInputId('tab', $locale) }}" data-toggle="tab">
                                    {{ $properties['native'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        @foreach(supportedLocalesAsInputTabs() as $locale => $properties)
                            <?php
                            $trans = $article->translate($locale);
                            $title = $trans ? $trans->title : '';
                            $slug = $trans ? $trans->slug : '';
                            $description = $trans ? $trans->description : '';
                            $content = $trans ? $trans->content : '';
                            ?>
                            <div class="tab-pane{{ $locale == $site_locale ? ' active' : '' }}" id="{{ localeInputId('tab', $locale) }}">
                                <div class="form-group">
                                    <label class="required separated" for="{{ localeInputId('inputTitle', $locale) }}">{{ trans('label.title') }}</label>
                                    <input class="form-control slug-from" id="{{ localeInputId('inputTitle', $locale) }}"
                                           name="{{ localeInputName('title', $locale) }}"
                                           placeholder="{{ trans('label.title') }}" type="text" value="{{ $title }}">
                                </div>
                                <div class="form-group">
                                    <label class="required separated" for="{{ localeInputId('inputSlug', $locale) }}">{{ trans('label.slug') }}</label>
                                    <input class="form-control slug" id="{{ localeInputId('inputSlug', $locale) }}"
                                           name="{{ localeInputName('slug', $locale) }}"
                                           placeholder="{{ trans('label.slug') }}" type="text" value="{{ $slug }}">
                                </div>
                                <div class="form-group">
                                    <label for="{{ localeInputId('inputDescription', $locale) }}">{{ trans('label.description') }}</label>
                                    <input class="form-control" id="{{ localeInputId('inputDescription', $locale) }}"
                                           name="{{ localeInputName('description', $locale) }}"
                                           placeholder="{{ trans('label.description') }}" type="text" value="{{ $description }}">
                                </div>
                                <div class="form-group">
                                    <label for="{{ localeInputId('inputContent', $locale) }}">{{ trans('label.content') }}</label>
                                    <textarea cols="10" rows="10" class="form-control ck-editor" id="{{ localeInputId('inputContent', $locale) }}"
                                              name="{{ localeInputName('content', $locale) }}"
                                              placeholder="{{ trans('label.content') }}">{{ $content }}</textarea>
                                </div>
                            </div><!-- /.tab-pane -->
                        @endforeach
                    </div><!-- /.tab-content -->
                </div><!-- nav-tabs-custom -->
                <div>
                    <button class="btn btn-primary" type="submit">{{ trans('form.action_save') }}</button>
                    <div class="pull-right">
                        <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                        <a role="button" class="btn btn-warning" href="{{ adminUrl('articles') }}">{{ trans('form.action_cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection