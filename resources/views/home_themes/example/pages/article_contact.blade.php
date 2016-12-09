@extends('home_themes.example.master.index')
@section('extra_sections')
    <section id="articles" class="even-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="text-uppercase">{{ trans_choice('label.article', 2) }}</h1>
                    <div class="help-block small">Contact Article Template</div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    {!! placeholder('articles', null, null, trans('label.no_widget')) !!}
                </div>
                <div class="col-sm-9 text-left">
                    <div id="article-{{ $article->id }}">
                        <h3>{{ $article->title }}</h3>
                        <div class="help-block small">
                            {{ trans('label.author') }}: {{ $article->author->display_name }} |
                            {{ trans_choice('label.category', $article_categories->count()) }}:
                            {{ $article_categories->implode('name', ', ') }} |
                            {{ $article->created_at }}
                        </div>
                        @if(!empty($article->featured_image))
                            <div class="thumbnail">
                                <img class="img-responsive" alt="{{ $article->title }}" src="{{ $article->featured_image }}">
                            </div>
                        @endif
                        <article>{!! $article->content !!}</article>
                    </div>
                </div>
            </div>
            @if(isActivatedExtension('contact_form') || isActivatedExtension('google_maps'))
                <hr>
                <div class="row text-left">
                    @if(isActivatedExtension('contact_form'))
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        {{ trans('contact_form.page_contact_forms_title') }}
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    {{ \Katniss\Everdeen\Themes\Plugins\ContactForm\htmlContactForm() }}
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(isActivatedExtension('google_maps'))
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        {{ trans('contact_form.page_contact_forms_title') }}
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    {{ \Katniss\Everdeen\Themes\Plugins\ContactForm\htmlContactForm() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </section>
@endsection