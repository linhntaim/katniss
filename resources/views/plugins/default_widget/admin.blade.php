@yield('extended_widget_top')

<!-- Custom Tabs -->
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
            <div class="tab-pane{{ $locale == $site_locale ? ' active' : '' }}" id="tab_{{ $locale }}">
                <div class="form-group">
                    <label for="inputName_{{ $locale }}">{{ trans('label.name') }}</label>
                    <input class="form-control" id="inputName_{{ $locale }}" name="name[{{ $locale }}]"
                           placeholder="{{ trans('label.name') }}" type="text" value="{{ $widget->getProperty('name', $locale) }}">
                </div>
                <div class="form-group">
                    <label for="inputDescription_{{ $locale }}">{{ trans('label.description') }}</label>
                    <input class="form-control" id="inputDescription_{{ $locale }}" name="description[{{ $locale }}]"
                           placeholder="{{ trans('label.description') }}" type="text" value="{{ $widget->getProperty('description', $locale) }}">
                </div>
                @if(isset($extended_localizing_path))
                    @include($extended_localizing_path)
                @endif
            </div><!-- /.tab-pane -->
        @endforeach
    </div><!-- /.tab-content -->
</div><!-- nav-tabs-custom -->

@yield('extended_widget_bottom')