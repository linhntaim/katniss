@extends('home_themes.default.master.index')
@section('extra_sections')
    <section id="social-sharing" class="even-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">{{ trans('label.social_sharing') }}</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <p>{{ isActivatedExtension('social_integration') ? trans('social_integration.ext_activated') : trans('social_integration.ext_not_activated') }}</p>
                    <p>
                        {!! content_place('sharing_buttons', [currentUrl()]) !!}
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section id="facebook-comment" class="odd-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">{{ trans('label.facebook_comment') }}</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-1 col-sm-2 col-md-3"></div>
                <div class="col-xs-10 col-sm-8 col-md-6">
                    <p>{{ isActivatedExtension('social_integration') ? trans('social_integration.ext_activated') : trans('social_integration.ext_not_activated') }}</p>
                    {!! content_place('facebook_comment', [currentUrl()]) !!}
                </div>
                <div class="col-xs-1 col-sm-2 col-md-3"></div>
            </div>
        </div>
    </section>
    <section id="example-widgets" class="even-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">{{ trans('label.example_widget') }}</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-1"></div>
                <div class="col-xs-10">
                    {!! placeholder('default_placeholder', KATNISS_EMPTY_STRING, KATNISS_EMPTY_STRING, trans('label.no_widget')) !!}
                </div>
                <div class="col-xs-1"></div>
            </div>
        </div>
    </section>
    <section id="my-settings" class="odd-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">{{ trans('pages.my_settings_title') }}</h1>
                    <p><strong>{{ trans('label.country') }}</strong><br>{{ $country }}</p>
                    <p><strong>{{ trans('label.language') }}</strong><br>{{ $locale }}</p>
                    <p><strong>{{ trans('label.timezone') }}</strong><br>{{ $timezone }}</p>
                    <p><strong>{{ trans('label.currency') }} &amp; {{ trans('label.number_format') }}</strong><br>{{ $price }}</p>
                    <p><strong>{{ trans('label.long_date_format') }} &amp; {{ trans('label.long_time_format') }}</strong><br>{{ $long_datetime }}</p>
                    <p><strong>{{ trans('label.short_date_format') }} &amp; {{ trans('label.short_time_format') }}</strong><br>{{ $short_datetime }}</p>
                    <p><a href="{{ meUrl('settings') }}">{{ trans('form.action_go_to') }} {{ trans('pages.my_settings_title') }}</a></p>
                </div>
            </div>
        </div>
    </section>
@endsection