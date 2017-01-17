@if($articles->count()>0)
    <div id="{{ $html_id }}" class="widget-wall-newest-articles">
        @if(!empty($name))
            <h4 class="color-master bold-600 uppercase margin-top-none">
                {{ $name }}
            </h4>
        @endif
        <div class="row margin-h--5">
            @foreach($articles as $article)
                <div class="col-xs-12 col-sm-6 col-md-4 padding-h-5 margin-bottom-10">
                    <div class="latest-article-item bg-master">
                        <div class="image-cover height-200">
                            <a href="{{ homeUrl('knowledge/articles/{slug}', ['slug' => $article->slug]) }}">
                                <img class="img-responsive border-solid border-master border-3x border-bottom-none"
                                     src="{{ empty($article->featured_image) ? $default_image : $article->featured_image }}">
                            </a>
                        </div>
                        <div class="padding-10">
                            <a href="{{ homeUrl('knowledge/articles/{slug}', ['slug' => $article->slug]) }}">
                                <span class="color-white big uppercase bold-600">{{ $article->title }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
            <div class="text-right">
                <a role="button" class="btn btn-primary uppercase bold-600" href="{{ homeUrl('knowledge/articles') }}">
                    {{ trans('form.action_see_more') }}
                </a>
            </div>
    </div>
@endif