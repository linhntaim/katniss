@extends('home_themes.wow_skype.master.master')
@section('extra_sections')
    <section id="404" class="odd-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">{{ $site_name }}</h1>
                    <p>{{ trans('label.error') }}: {{ $code }}{{ empty($message) ? '' : ' - ' . $message }}.</p>
                </div>
            </div>
        </div>
    </section>
@endsection