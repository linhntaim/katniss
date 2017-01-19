@extends('home_themes.wow_skype.master.master')
@section('lib_styles')
    <link rel="stylesheet" href="{{ libraryAsset('fancybox/jquery.fancybox.css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ libraryAsset('fancybox/jquery.fancybox.pack.js') }}"></script>
    <script src="{{ libraryAsset('fancybox/helpers/jquery.fancybox-media.js') }}"></script>
@endsection
@section('main_content')
    <div id="page-home">
        {!! placeholder('homepage') !!}
    </div>
@endsection