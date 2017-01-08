@extends('home_themes.wow_skype.master.master')
@section('main_content')
    <div id="{{ $html_page_id }}" class="page-profile">
        <div class="row">
            <div class="col-md-4">
                {{ $profile_menu }}
            </div>
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>{{ $panel_heading }}</strong>
                    </div>
                    <div class="panel-body">
                        @yield('profile_content')
                    </div>
                </div>
                @yield('profile_content_extra')
            </div>
        </div>
    </div>
@endsection