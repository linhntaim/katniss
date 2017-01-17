@extends('home_themes.wow_skype.master.master')
@section('main_content')
    <div id="page-articles">
        <div class="row">
            <div class="col-md-8">
                @if(!empty($category))
                    <ol class="breadcrumb margin-bottom-none">
                        <li class="breadcrumb-item">
                            <a href="{{ homeUrl('knowledge') }}">{{ trans('pages.home_knowledge_title') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $category->name }}</li>
                    </ol>
                @endif
                @if($articles->count() > 0)
                    <?php $first = true; ?>
                    @foreach($articles as $article)
                        @if(!$first)
                            <div class="master-slave-bar clearfix">
                                <div class="bar pull-left"></div>
                                <div class="bar pull-right"></div>
                            </div>
                        @endif
                        <div class="article-item padding-v-20">
                            <h4 class="margin-top-none margin-bottom-10 uppercase">
                                <a href="{{ homeUrl('knowledge/articles/{slug}', ['slug' => $article->slug]) }}">
                                    <strong>{{ $article->title }}</strong>
                                </a>
                            </h4>
                            <div class="article-meta margin-bottom-10">
                                <img class="img-circle border-solid border-master width-30"
                                     src="{{ $article->author->url_avatar_thumb }}">
                                {{ $article->author->display_name }}
                                <span class="color-lighter hidden-sm hidden-xs">
                                    / {{ $article->diffDays == 0 ? trans('datetime.today') : $article->diffDays .' '. trans_choice('label.days_ago_lc', $article->diffDays) }}
                                </span>
                                <span class="color-lighter hidden-xs">
                                    / {{ trans_choice('label.category', $article->categories->count()) }}:
                                    <?php $first = true; ?>
                                    @foreach($article->categories as $category)
                                        <a href="{{ homeUrl('knowledge/categories/{slug}', ['slug' => $category->slug]) }}">
                                            {{ $category->name }}</a>{{ !$first ? ',' : '' }}
                                        <?php $first = false; ?>
                                    @endforeach
                                </span>
                            </div>
                            @if(!empty($article->featured_image))
                                <div class="image-cover height-200 margin-bottom-10">
                                    <img src="{{ $article->featured_image }}">
                                </div>
                            @endif
                            <article>{{ htmlShorten($article->content)  }}</article>
                            <div class="text-right margin-top-10">
                                <a href="{{ homeUrl('knowledge/articles/{slug}', ['slug' => $article->slug]) }}">
                                    {{ trans('form.action_see_more') }} &raquo;
                                </a>
                            </div>
                        </div>
                        <?php $first = false; ?>
                    @endforeach
                    <div class="text-center">
                        {{ $pagination }}
                    </div>
                @else
                    <div class="margin-v-10">
                        {{ trans('label.list_empty') }}
                    </div>
                @endif
            </div>
            <div class="col-md-4">
                {!! placeholder('article_sidebar_right') !!}
            </div>
        </div>
    </div>
@endsection