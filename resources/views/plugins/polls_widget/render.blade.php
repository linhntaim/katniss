<div id="{{ $html_id }}" class="widget-polls">
    @if(!empty($name))
        <h4>{{ $name }}</h4>
    @endif
    @if(!empty($poll_name))
        <h5>{{ $poll_name }}</h5>
    @endif
    @if(!empty($poll_description))
        <div class="help-block small">{{ $poll_description }}</div>
    @endif
    @if($choices->count() > 0)
        <form class="poll-votes" method="post" data-id="{{ $poll_id }}"
              action="{{ addRdrUrl(addExtraUrl('web-api/polls/id', webApiUrl('extra')) . '&id=' . $poll_id) }}">
            {{ csrf_field() }}
            {{ method_field('put') }}
            <input type="hidden" name="votes" value="1">
            <input type="hidden" name="poll_id" value="{{ $poll_id }}">
            <div class="form-group">
                @foreach($choices as $choice)
                    <div class="{{ $input_type }}">
                        <label>
                            <input type="{{ $input_type }}" name="choice_ids[]" id="inputChoice_{{ $choice->id }}" value="{{ $choice->id }}">
                            {{ $choice->name }}
                        </label>
                    </div>
                @endforeach
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">{{ trans('polls.action_vote') }}</button>
                <button type="button" class="btn btn-success show-poll-result">{{ trans('polls.action_view_result') }}</button>
            </div>
        </form>
        <div class="poll-result" style="display: none">
            <ul class="list-unstyled">
                @foreach($choices as $choice)
                    <li>{{ $choice->name }}: {{ $choice->votes }} {{ trans_choice('polls.vote_lc', $choice->votes) }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn btn-success show-poll-votes">{{ trans('polls.action_view_votes') }}</button>
        </div>
    @else
        <div>{{ trans('label.list_empty') }}</div>
    @endif
</div>