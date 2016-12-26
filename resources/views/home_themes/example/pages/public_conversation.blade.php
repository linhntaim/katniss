@extends('home_themes.example.master.index')
@section('extra_sections')
    <section id="public-conversation" class="even-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">{{ trans('example_theme.public_conversation') }}</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <iframe src="{{ addThemeUrl(webApiUrl('conversations/1') . '?messages=1', 'conversation') }}"></iframe>
                </div>
            </div>
        </div>
    </section>
@endsection