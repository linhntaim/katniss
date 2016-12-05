@extends('home_themes.default.master.index')
@section('extra_sections')
    <section id="facebook-comment" class="even-section">
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
@endsection