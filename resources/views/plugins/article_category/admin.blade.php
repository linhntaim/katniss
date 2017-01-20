@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('select2-js') }}"></script>
@endsection
@section('extended_scripts')
    @include('file_manager.open_documents_script')
    <script>
        $(function () {
            $('.select2').select2();
        });
    </script>
@endsection

<div class="row">
    <div class="col-xs-12">
        <div class="form-group">
            <label for="inputCategory">{{ trans_choice('label.category', 1) }}</label>
            <select id="inputCategory" class="form-control select2" name="category_id" style="width: 100%;">
                <option value="0">[{{ trans('label.not_set') }}]</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"{{ $category_id == $category->id ? ' selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="inputImage">{{ trans('label.picture') }}</label>
            <div class="input-group">
                <input class="form-control" id="inputImage" name="image"
                       placeholder="{{ trans('label.picture') }}" type="text" value="{{ $image }}">
                <div class="input-group-btn">
                    <button type="button" class="btn btn-primary image-from-documents"
                            data-input-id="inputImage">
                        <i class="fa fa-server"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
