<div class="form-group">
    <label for="inputContent_{{ $locale }}">{{ trans('label.content') }}</label>
    <textarea rows="10" class="form-control ck-editor" id="inputContent_{{ $locale }}" name="content[{{ $locale }}]"
           placeholder="{{ trans('label.content') }}">{{ $widget->getProperty('content', $locale) }}</textarea>
</div>