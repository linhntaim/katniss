@extends('admin_themes.admin_lte.master.admin')
@section('page_title', trans('pages.admin_links_title'))
@section('page_description', trans('pages.admin_links_desc'))
@section('page_breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{ adminUrl() }}"><i class="fa fa-home"></i> {{ trans('pages.admin_dashboard_title') }}</a></li>
    <li><a href="{{ adminUrl('links') }}">{{ trans('pages.admin_links_title') }}</a></li>
    <li><a href="#">{{ trans('form.action_edit') }}</a></li>
</ol>
@endsection
@section('lib_styles')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css">
@endsection
@section('lib_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
@endsection
@section('extended_scripts')
    <script>
        {!! cdataOpen() !!}
        jQuery(document).ready(function () {
            jQuery('.select2').select2();

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
        <form method="post" action="{{ adminUrl('links/update') }}">
            {!! csrf_field() !!}
            <input type="hidden" name="id" value="{{ $link->id }}">
            <div class="col-xs-12">
                <div class="margin-bottom">
                    <a class="btn btn-warning delete" href="{{ adminUrl('links/{id}/delete', ['id'=> $link->id]) }}">
                        {{ trans('form.action_delete') }}
                    </a>
                    <a class="btn btn-primary pull-right" href="{{ adminUrl('links/add') }}">
                        {{ trans('form.action_add') }} {{ trans_choice('label.link', 1) }}
                    </a>
                </div>
                <h4 class="box-title">{{ trans('form.action_edit') }} {{ trans_choice('label.category_lc', 1) }}</h4>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <div class="row">
                      <div class="col-xs-12 col-sm-6 col-md-4">
                          <div class="form-group">
                              <label for="inputCategories">{{ trans_choice('label.category', 2) }}</label>
                              <select id="inputCategories"  class="form-control select2" name="categories[]" multiple="multiple" required
                                      data-placeholder="{{ trans('form.action_select') }} {{ trans_choice('label.link_category', 2) }}" style="width: 100%;">
                                  @foreach ($categories as $category)
                                      <option value="{{ $category->id }}"{{ $link_categories->contains('id', $category->id) ? ' selected' : '' }}>
                                          {{ $category->name }}
                                      </option>
                                  @endforeach
                              </select>
                          </div>
                      </div>
                </div>
                <div class="form-group">
                      <label for="inputImage">{{ trans('label.picture') }}</label>
                      <input class="form-control image-from-documents" id="inputImage" name="image" placeholder="{{ trans('label.picture') }}" type="text" value="{{ $link->image }}">
                </div>
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        @foreach(allSupportedLocales() as $locale => $properties)
                            <li{!! $locale == $site_locale ? ' class="active"' : '' !!}>
                                <a href="#tab_{{ $locale }}" data-toggle="tab">
                                    {{ $properties['native'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        @foreach(allSupportedLocales() as $locale => $properties)
                            <?php
                            $trans = $link->translate($locale);
                            $name = $trans ? $trans->name : '';
                            $description = $trans ? $trans->description : '';
                            $url = $trans ? $trans->url : '';
                            ?>
                            <div class="tab-pane{{ $locale == $site_locale ? ' active' : '' }}" id="tab_{{ $locale }}">
                                <div class="form-group">
                                    <label for="inputName_{{ $locale }}">{{ trans('label.name') }}</label>
                                    <input class="form-control" id="inputName_{{ $locale }}" name="name[{{ $locale }}]"
                                           placeholder="{{ trans('label.name') }}" type="text" value="{{ $name }}">
                                </div>
                                <div class="form-group">
                                    <label for="inputDescription_{{ $locale }}">{{ trans('label.description') }}</label>
                                    <input class="form-control" id="inputDescription_{{ $locale }}" name="description[{{ $locale }}]"
                                           placeholder="{{ trans('label.description') }}" type="text" value="{{ $description }}">
                                </div>
                                <div class="form-group">
                                    <label for="inputUrl_{{ $locale }}">{{ trans('label.url') }}</label>
                                    <input class="form-control" id="inputUrl_{{ $locale }}" name="url[{{ $locale }}]"
                                           placeholder="{{ trans('label.url') }}" type="text" value="{{ $url }}">
                                </div>
                            </div><!-- /.tab-pane -->
                        @endforeach
                    </div><!-- /.tab-content -->
                </div><!-- nav-tabs-custom -->
                <div>
                    <button class="btn btn-primary" type="submit">{{ trans('form.action_save') }}</button>
                    <div class="pull-right">
                        <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                        <a role="button" class="btn btn-warning" href="{{ adminUrl('links') }}">{{ trans('form.action_cancel') }}</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection