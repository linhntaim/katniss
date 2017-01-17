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
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('form.action_edit') }}</h3>
            </div>
            <form method="post" action="{{ currentFullUrl() }}">
                {{ csrf_field() }}
                {{ method_field('put') }}
                <div class="box-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="inputKnowledgeCoverImage">{{ trans('wow_skype_theme.knowledge_cover_image') }}</label>
                        <div class="input-group">
                            <input class="form-control" id="inputKnowledgeCoverImage" name="knowledge_cover_image"
                                   placeholder="{{ trans('wow_skype_theme.knowledge_cover_image') }}" type="text" value="{{ $knowledge_cover_image }}">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-primary image-from-documents"
                                        data-input-id="inputKnowledgeCoverImage">
                                    <i class="fa fa-server"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputKnowledgeDefaultArticleImage">{{ trans('wow_skype_theme.knowledge_default_article_image') }}</label>
                        <div class="input-group">
                            <input class="form-control" id="inputKnowledgeDefaultArticleImage" name="knowledge_default_article_image"
                                   placeholder="{{ trans('wow_skype_theme.knowledge_default_article_image') }}" type="text" value="{{ $knowledge_default_article_image }}">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-primary image-from-documents"
                                        data-input-id="inputKnowledgeDefaultArticleImage">
                                    <i class="fa fa-server"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">{{ trans('form.action_save') }}</button>
                    <div class="pull-right">
                        <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                    </div>
                </div>
            </form>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>