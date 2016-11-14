<div id="{{ $html_id }}" class="widget-instagram-wall">
    @if(!empty($name))
        <h4>{{ $name }}</h4>
    @endif
    <ul class="list-unstyled media-list clearfix">
        @foreach($instagram_media as $media)
            <li class="media-item">
                <a href="{{ $media['link'] }}" title="{{ $media['caption']['text'] }}">
                    <img src="{{ $media['images']['low_resolution']['url'] }}" alt="{{ $media['caption']['text'] }}">
                </a>
            </li>
        @endforeach
    </ul>
</div>