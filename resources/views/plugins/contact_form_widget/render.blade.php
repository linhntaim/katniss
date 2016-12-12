<div id="{{ $html_id }}" class="widget-contact-form">
    @if(!empty($name))
        <h4>{{ $name }}</h4>
    @endif
    @if(!empty($description))
        <div class="help-block">{{ $description }}</div>
    @endif
    @include('plugins.contact_form.form')
</div>