@extends('home_themes.default.master.index')
@section('extra_sections')
    <section id="social-sharing" class="even-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">Social Sharing</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <p>{{ isActivatedExtension('social_integration') ? 'Social Integration extension is activated' : 'Social Integration extension is not activated' }}</p>
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
                    <h1 class="text-uppercase">Facebook Comments</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-1 col-sm-2 col-md-3"></div>
                <div class="col-xs-10 col-sm-8 col-md-6">
                    <p>{{ isActivatedExtension('social_integration') ? 'Social Integration extension is activated' : 'Social Integration extension is not activated' }}</p>
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
                    <h1 class="text-uppercase">Example Widgets</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-1"></div>
                <div class="col-xs-10">
                    {!! placeholder('default_placeholder', KATNISS_EMPTY_STRING, KATNISS_EMPTY_STRING, 'No widget is placed here') !!}
                </div>
                <div class="col-xs-1"></div>
            </div>
        </div>
    </section>
    <section id="my-settings" class="odd-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">My Settings</h1>
                    <p><strong>Country</strong><br>{{ $country }}</p>
                    <p><strong>Locale</strong><br>{{ $locale }}</p>
                    <p><strong>Timezone</strong><br>{{ $timezone }}</p>
                    <p><strong>Currency + Number Format</strong><br>{{ $price }}</p>
                    <p><strong>Long Date Time</strong><br>{{ $long_datetime }}</p>
                    <p><strong>Short Date Time</strong><br>{{ $short_datetime }}</p>
                    <p><a href="{{ homeUrl('my-settings') }}">{{ trans('form.action_go_to') }} {{ trans('pages.my_settings_title') }}</a></p>
                </div>
            </div>
        </div>
    </section>
@endsection