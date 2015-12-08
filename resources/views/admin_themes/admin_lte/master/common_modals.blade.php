<div class="modal fade" id="x-confirm-modal" tabindex="-1" role="dialog" aria-labelledby="x-confirm-modal-title">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="x-confirm-modal-title"></h4>
            </div>
            <div id="x-confirm-modal-content" class="modal-body">
            </div>
            <div class="modal-footer">
                <button id="x-confirm-modal-button" type="button" class="btn btn-danger" data-dismiss="modal">{{ trans('form.action_confirm') }}</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('form.action_cancel') }}</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="x-alert-modal" tabindex="-1" role="dialog" aria-labelledby="x-alert-modal-title">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="x-alert-modal-title">{{ trans('label.alert') }}</h4>
            </div>
            <div id="x-alert-modal-content" class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">{{ trans('form.action_close') }}</button>
            </div>
        </div>
    </div>
</div>
<script>
    {!! cdataOpen() !!}
    function x_confirm(title, content, callback) {
        var confirmModal=jQuery('#x-confirm-modal');
        confirmModal.find('#x-confirm-modal-title').html(title);
        confirmModal.find('#x-confirm-modal-content').html(content);
        confirmModal.find('#x-confirm-modal-button').on('click', function(){
            if(typeof callback === 'function') {
                callback.call();
            }
        });
        confirmModal.modal('show').on('hide.bs.modal', function(){
            confirmModal.find('#x-confirm-modal-title').text('');
            confirmModal.find('#x-confirm-modal-content').text('');
            confirmModal.find('#x-confirm-modal-button').off('click');

            confirmModal.off('hide.bs.modal');
        });
    }
    function x_href_confirm(href, title, content) {
        x_confirm(title, content, function () {
            window.location.href = href;
        });
    }

    function x_alert(content) {
        var alertModal=jQuery('#x-alert-modal');
        alertModal.find('#x-alert-modal-content').html(content);
        alertModal.modal('show');
    }
    {!! cdataClose() !!}
</script>