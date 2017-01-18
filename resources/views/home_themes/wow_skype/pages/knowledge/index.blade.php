@extends('home_themes.wow_skype.master.master')
@section('before_main_content')
    <div id="knowledge-cover" class="bg-master">
        {!! contentPlace('knowledge_cover') !!}
    </div>
@endsection
@section('main_content')
    <div id="page-knowledge">
        <div id="knowledge-middle">
            {!! placeholder('knowledge_middle') !!}
        </div>
        <div id="knowledge-bottom">
            {!! placeholder('knowledge_bottom') !!}
        </div>
    </div>
@endsection