/**
 * Created by Nguyen Tuan Linh on 2015-11-04.
 */

CKEDITOR.editorConfig = function (config) {
    config.extraPlugins = 'videodetector';
    // Toolbar configuration generated automatically by the editor based on config.toolbarGroups.
    config.toolbar = [
        ['Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript'],
        ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', 'Blockquote', 'CreateDiv'],
        ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
        ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
        ['Find', 'Replace', '-', 'SelectAll', '-', 'RemoveFormat'],
        '/',
        ['Styles', 'Format', 'Font', 'FontSize'],
        ['TextColor', 'BGColor'],
        ['Link', 'Unlink', 'Anchor'],
        ['Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'Iframe', 'VideoDetector'],
        ['Maximize', 'ShowBlocks'],
        ['Source']
    ];
};
