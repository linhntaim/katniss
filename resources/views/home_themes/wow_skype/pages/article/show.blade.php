@extends('home_themes.wow_skype.master.master')
@section('main_content')
    <div id="page-article">
        <div class="row">
            <div class="col-md-8">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ homeUrl('knowledge') }}">{{ trans('pages.home_knowledge_title') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ homeUrl('knowledge/articles') }}">{{ trans('pages.home_articles_title') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $article->title }}</li>
                </ol>
                <h1 class="margin-top-none color-master margin-bottom-10 uppercase">
                    <strong>{{ $article->title }}</strong>
                </h1>
                <div class="master-slave-bar clearfix">
                    <div class="bar pull-left"></div>
                    <div class="bar pull-right"></div>
                </div>
                <div class="article-meta margin-v-10">
                    <img class="img-circle border-solid border-master width-30"
                         src="{{ $article->author->url_avatar_thumb }}">
                    {{ $article->author->display_name }}
                    @if($article->diffDays > 0)
                        <span class="color-lighter hidden-sm hidden-xs">
                            / {{ $article->diffDays }} {{ trans_choice('label.days_ago_lc', $article->diffDays) }}
                        </span>
                    @endif
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
                    <div class="image-cover margin-bottom-10">
                        <img src="{{ $article->featured_image }}">
                    </div>
                @endif
                <article class="article-responsive">{!! $article->content  !!}</article>
                <div class="margin-top-20">
                    {!! contentPlace('sharing_buttons', [homeUrl('knowledge/articles/{slug}', ['slug' => $article->slug])]) !!}
                </div>
                <div class="master-slave-bar margin-v-10 clearfix">
                    <div class="bar pull-left"></div>
                    <div class="bar pull-right"></div>
                </div>
                <div class="comments">
                    {!! contentPlace('facebook_comments', [homeUrl('knowledge/articles/{slug}', ['slug' => $article->id], 'en')]) !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="margin-bottom-10">
                    <a id="categories-menu-toggle" class="btn btn-primary btn-block collapsed" data-toggle="collapse" data-target="#categories-menu">
                        {{ trans_choice('label.category', 2) }}
                    </a>
                    <div class="well padding-none collapse border-master" id="categories-menu">
                        {{ $categories_menu }}
                    </div>
                </div>
                {!! placeholder('article_sidebar_right') !!}
            </div>
        </div>
    </div>
@endsection