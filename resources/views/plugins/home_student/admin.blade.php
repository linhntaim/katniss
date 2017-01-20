@extends('plugins.default_widget.admin')
@section('extended_scripts')
    @parent
    @include('file_manager.open_documents_script')
@endsection
@section('extended_widget_bottom')
    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <label for="inputVideoUrl">Video URL</label>
                <input class="form-control" id="inputVideoUrl"
                       name="video_url" type="text"
                       placeholder="Video URL" value="{{ $video_url }}">
            </div>
        </div>
    </div>

    <!-- Custom Tabs -->
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            @foreach(supportedLocalesAsInputTabs() as $locale => $properties)
                <li{!! $locale == $site_locale ? ' class="active"' : '' !!}>
                    <a href="#tab2_{{ $locale }}" data-toggle="tab">
                        {{ $properties['native'] }}
                    </a>
                </li>
            @endforeach
        </ul>
        <div class="tab-content">
            @foreach(supportedLocalesAsInputTabs() as $locale => $properties)
                <div class="tab-pane{{ $locale == $site_locale ? ' active' : '' }}" id="tab2_{{ $locale }}">
                    <div class="form-group">
                        <label for="{{ localeInputId('inputImage_1', $locale) }}">{{ trans('label.picture') }}</label>
                        <div class="input-group">
                            <input class="form-control" id="{{ localeInputId('inputImage_1', $locale) }}"
                                   name="{{ localeInputName('picture_1', $locale) }}" type="text"
                                   placeholder="{{ trans('label.picture') }}" value="{{ $widget->getProperty('picture_1', $locale) }}">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-primary image-from-documents"
                                        data-input-id="{{ localeInputId('inputImage_1', $locale) }}">
                                    <i class="fa fa-server"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="{{ localeInputId('inputReview_1', $locale) }}">{{ trans('label.review') }}</label>
                        <textarea class="form-control" id="{{ localeInputId('inputReview_1', $locale) }}"
                                  name="{{ localeInputName('review_1', $locale) }}" rows="3" cols="3"
                                  placeholder="{{ trans('label.review') }}">{{ $widget->getProperty('review_1', $locale) }}</textarea>
                    </div>
                </div><!-- /.tab-pane -->
            @endforeach
        </div><!-- /.tab-content -->
    </div><!-- nav-tabs-custom -->

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            @foreach(supportedLocalesAsInputTabs() as $locale => $properties)
                <li{!! $locale == $site_locale ? ' class="active"' : '' !!}>
                    <a href="#tab3_{{ $locale }}" data-toggle="tab">
                        {{ $properties['native'] }}
                    </a>
                </li>
            @endforeach
        </ul>
        <div class="tab-content">
            @foreach(supportedLocalesAsInputTabs() as $locale => $properties)
                <div class="tab-pane{{ $locale == $site_locale ? ' active' : '' }}" id="tab3_{{ $locale }}">
                    <div class="form-group">
                        <label for="{{ localeInputId('inputImage_2', $locale) }}">{{ trans('label.picture') }}</label>
                        <div class="input-group">
                            <input class="form-control" id="{{ localeInputId('inputImage_2', $locale) }}"
                                   name="{{ localeInputName('picture_2', $locale) }}" type="text"
                                   placeholder="{{ trans('label.picture') }}" value="{{ $widget->getProperty('picture_2', $locale) }}">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-primary image-from-documents"
                                        data-input-id="{{ localeInputId('inputImage_2', $locale) }}">
                                    <i class="fa fa-server"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="{{ localeInputId('inputReview_2', $locale) }}">{{ trans('label.review') }}</label>
                        <textarea class="form-control" id="{{ localeInputId('inputReview_2', $locale) }}"
                                  name="{{ localeInputName('review_2', $locale) }}" rows="3" cols="3"
                                  placeholder="{{ trans('label.review') }}">{{ $widget->getProperty('review_2', $locale) }}</textarea>
                    </div>
                </div><!-- /.tab-pane -->
            @endforeach
        </div><!-- /.tab-content -->
    </div><!-- nav-tabs-custom -->

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            @foreach(supportedLocalesAsInputTabs() as $locale => $properties)
                <li{!! $locale == $site_locale ? ' class="active"' : '' !!}>
                    <a href="#tab4_{{ $locale }}" data-toggle="tab">
                        {{ $properties['native'] }}
                    </a>
                </li>
            @endforeach
        </ul>
        <div class="tab-content">
            @foreach(supportedLocalesAsInputTabs() as $locale => $properties)
                <div class="tab-pane{{ $locale == $site_locale ? ' active' : '' }}" id="tab4_{{ $locale }}">
                    <div class="form-group">
                        <label for="{{ localeInputId('inputImage_3', $locale) }}">{{ trans('label.picture') }}</label>
                        <div class="input-group">
                            <input class="form-control" id="{{ localeInputId('inputImage_3', $locale) }}"
                                   name="{{ localeInputName('picture_3', $locale) }}" type="text"
                                   placeholder="{{ trans('label.picture') }}" value="{{ $widget->getProperty('picture_3', $locale) }}">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-primary image-from-documents"
                                        data-input-id="{{ localeInputId('inputImage_3', $locale) }}">
                                    <i class="fa fa-server"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="{{ localeInputId('inputReview_3', $locale) }}">{{ trans('label.review') }}</label>
                        <textarea class="form-control" id="{{ localeInputId('inputReview_3', $locale) }}"
                                  name="{{ localeInputName('review_3', $locale) }}" rows="3" cols="3"
                                  placeholder="{{ trans('label.review') }}">{{ $widget->getProperty('review_3', $locale) }}</textarea>
                    </div>
                </div><!-- /.tab-pane -->
            @endforeach
        </div><!-- /.tab-content -->
    </div><!-- nav-tabs-custom -->
@endsection