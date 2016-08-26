<script>
    {!! cdataOpen() !!}
    function openMyDocuments(fromInputId, documentType) {
        window.open(
                '{{ homeUrl('me/documents/for/popup/{input_id}', ['input_id' => '{input_id}']) }}'.replace('{input_id}', fromInputId) + '?custom_type=' + documentType,
                '{{ trans('pages.my_documents_title') }}',
                'width=900,height=480'
        );
    }
    function processSelectedFile(file_url, input_id) {
        jQuery('#' + input_id).val(file_url);
    }
    jQuery(document).ready(function () {
        jQuery('.image-from-documents').on('click', function () {
            openMyDocuments(jQuery(this).attr('id'), 'images');
        });
    });
    {!! cdataClose() !!}
</script>