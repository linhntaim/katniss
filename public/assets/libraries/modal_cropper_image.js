(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as anonymous module.
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        // Node / CommonJS
        factory(require('jquery'));
    } else {
        // Browser globals.
        factory(jQuery);
    }
})(function ($) {

    'use strict';

    var console = window.console || {
            log: function () {
            }
        };

    function CropImageModal($element) {
        this.$container = $element;

        this.$cropperImageView = this.$container.find('.cropper-image-view');
        this.$cropperImage = this.$cropperImageView.is('img') ? this.$cropperImageView : this.$cropperImageView.find('img');
        this.$cropperImageModal = this.$container.find('#cropper-image-modal');
        this.$loading = this.$container.find('.cropper-image-loading');

        this.$cropperImageForm = this.$cropperImageModal.find('.cropper-image-form');
        this.$cropperImageUpload = this.$cropperImageForm.find('.cropper-image-upload');
        this.$cropperImageSrc = this.$cropperImageForm.find('.cropper-image-src');
        this.$cropperImageData = this.$cropperImageForm.find('.cropper-image-data');
        this.$cropperImageInput = this.$cropperImageForm.find('.cropper-image-input');
        this.$cropperImageSave = this.$cropperImageForm.find('.cropper-image-save');
        this.$cropperImageButtons = this.$cropperImageForm.find('.cropper-image-buttons button');

        this.$cropperImageWrapper = this.$cropperImageModal.find('.cropper-image-wrapper');
        this.$cropperImagePreview = this.$cropperImageModal.find('.cropper-image-preview');

        this.init();
    }

    CropImageModal.prototype = {
        constructor: CropImageModal,

        support: {
            fileList: !!$('<input type="file">').prop('files'),
            blobURLs: !!window.URL && URL.createObjectURL,
            formData: !!window.FormData
        },

        init: function () {
            this.support.datauri = this.support.fileList && this.support.blobURLs;

            if (!this.support.formData) {
                this.initIframe();
            }

            this.initTooltip();
            this.initModal();
            this.addListener();
        },

        addListener: function () {
            this.$cropperImageView.on('click', $.proxy(this.click, this));
            this.$cropperImageInput.on('change', $.proxy(this.change, this));
            this.$cropperImageForm.on('submit', $.proxy(this.submit, this));
            this.$cropperImageButtons.on('click', $.proxy(this.rotate, this));
        },

        initTooltip: function () {
            this.$cropperImageView.tooltip({
                placement: 'bottom'
            });
        },

        initModal: function () {
            this.$cropperImageModal.modal({
                show: false
            });
        },

        initPreview: function () {
            var url = this.$cropperImage.attr('src');

            this.$cropperImagePreview.html('<img src="' + url + '">');
        },

        initIframe: function () {
            var target = 'upload-iframe-' + (new Date()).getTime();
            var $iframe = $('<iframe>').attr({
                name: target,
                src: ''
            });
            var _this = this;

            // Ready ifrmae
            $iframe.one('load', function () {

                // respond response
                $iframe.on('load', function () {
                    var data;

                    try {
                        data = $(this).contents().find('body').text();
                    } catch (e) {
                        console.log(e.message);
                    }

                    if (data) {
                        try {
                            data = $.parseJSON(data);
                        } catch (e) {
                            console.log(e.message);
                        }

                        _this.submitDone(data);
                    } else {
                        _this.submitFail('Image upload failed!');
                    }

                    _this.submitEnd();

                });
            });

            this.$iframe = $iframe;
            this.$cropperImageForm.attr('target', target).after($iframe.hide());
        },

        click: function () {
            this.$cropperImageModal.modal('show');
            this.initPreview();
        },

        change: function () {
            var files;
            var file;

            if (this.support.datauri) {
                files = this.$cropperImageInput.prop('files');

                if (files.length > 0) {
                    file = files[0];

                    if (this.isImageFile(file)) {
                        if (this.url) {
                            URL.revokeObjectURL(this.url); // Revoke the old one
                        }

                        this.url = URL.createObjectURL(file);
                        this.startCropper();
                    }
                }
            } else {
                file = this.$cropperImageInput.val();

                if (this.isImageFile(file)) {
                    this.syncUpload();
                }
            }
        },

        submit: function () {
            if (!this.$cropperImageSrc.val() && !this.$cropperImageInput.val()) {
                return false;
            }

            if (this.support.formData) {
                this.ajaxUpload();
                return false;
            }
        },

        rotate: function (e) {
            var data;

            if (this.active) {
                data = $(e.target).data();

                if (data.method) {
                    this.$img.cropper(data.method, data.option);
                }
            }
        },

        isImageFile: function (file) {
            if (file.type) {
                return /^image\/\w+$/.test(file.type);
            } else {
                return /\.(jpg|jpeg|png|gif)$/.test(file);
            }
        },

        startCropper: function () {
            var _this = this;

            if (this.active) {
                this.$img.cropper('replace', this.url);
            } else {
                this.$img = $('<img src="' + this.url + '">');
                this.$cropperImageWrapper.empty().html(this.$img);
                this.$img.cropper({
                    viewMode: 1,
                    aspectRatio: 1,
                    preview: this.$cropperImagePreview.selector,
                    crop: function (e) {
                        var json = [
                            '{"x":' + e.x,
                            '"y":' + e.y,
                            '"height":' + e.height,
                            '"width":' + e.width,
                            '"rotate":' + e.rotate + '}'
                        ].join();

                        _this.$cropperImageData.val(json);
                    }
                });

                this.active = true;
            }

            this.$cropperImageModal.one('hidden.bs.modal', function () {
                _this.$cropperImagePreview.empty();
                _this.stopCropper();
            });
        },

        stopCropper: function () {
            if (this.active) {
                this.$img.cropper('destroy');
                this.$img.remove();
                this.active = false;
            }
        },

        ajaxUpload: function () {
            var url = this.$cropperImageForm.attr('action');
            var data = new FormData(this.$cropperImageForm[0]);
            var _this = this;

            $.ajax(url, {
                type: 'post',
                data: data,
                dataType: 'json',
                processData: false,
                contentType: false,

                beforeSend: function () {
                    _this.submitStart();
                },

                success: function (data) {
                    _this.submitDone(data);
                },

                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    _this.submitFail(textStatus || errorThrown);
                },

                complete: function () {
                    _this.submitEnd();
                }
            });
        },

        syncUpload: function () {
            this.$cropperImageSave.click();
        },

        submitStart: function () {
            this.$loading.fadeIn();
        },

        submitDone: function (data) {
            console.log(data);

            if ($.isPlainObject(data) && data.state === 200) {
                if (data.result) {
                    this.url = data.result;

                    if (this.support.datauri || this.uploaded) {
                        this.uploaded = false;
                        this.cropDone();
                    } else {
                        this.uploaded = true;
                        this.$cropperImageSrc.val(this.url);
                        this.startCropper();
                    }

                    this.$cropperImageInput.val('');
                } else if (data.message) {
                    this.alert(data.message);
                }
            } else {
                this.alert('Failed to response');
            }
        },

        submitFail: function (msg) {
            this.alert(msg);
        },

        submitEnd: function () {
            this.$loading.fadeOut();
        },

        cropDone: function () {
            this.$cropperImageForm.get(0).reset();
            this.$cropperImage.attr('src', this.url);
            this.stopCropper();
            this.$cropperImageModal.modal('hide');
        },

        alert: function (msg) {
            var $alert = [
                '<div class="alert alert-danger cropper-image-alert alert-dismissable">',
                '<button type="button" class="close" data-dismiss="alert">&times;</button>',
                msg,
                '</div>'
            ].join('');

            this.$cropperImageUpload.after($alert);
        }
    };

    $(function () {
        return new CropImageModal($('body'));
    });

});