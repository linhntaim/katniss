@extends('home_themes.default.master.index')
@section('extra_sections')
    <section id="pages" class="even-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">{{ trans_choice('label.page', 2) }}</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    {!! placeholder('articles') !!}
                </div>
                <div class="col-sm-9 text-left">
                    @foreach($articles as $article)
                        <div id="page-{{ $article->id }}">
                            <h3>
                                <a href="{{ homeUrl('example/pages/{id}', ['id' => $article->id]) }}">
                                    {{ $article->title }}
                                </a>
                            </h3>
                            <article>{!! htmlShorten($article->content) !!}</article>
                        </div>
                    @endforeach
                    {{ $articles->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection