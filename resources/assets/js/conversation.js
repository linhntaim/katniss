/**
 * Created by Nguyen Tuan Linh on 2016-07-22.
 */
$(document).ready(function () {
    var HTML_LEFT_MESSAGE_TEMPLATE = '<div id="box-{id}" class="square-box" style="{style}"></div><p id="message-{id}" class="bg-info message pull-left">{message}</p><div id="clearfix-{id}" class="clearfix"></div>';
    var HTML_RIGHT_MESSAGE_TEMPLATE = '<p id="message-{id}" class="bg-primary message pull-right">{message}</p><div id="clearfix-{id}" class="clearfix"></div>';
    var HTML_TYPING_MESSAGE_TEMPLATE = '<span class="text-grey">Is typing...</span>';
    var TYPING_TIMEOUT = 3000;
    var REMOVE_TYPING_TIMEOUT = TYPING_TIMEOUT * 2;
    var MESSAGE_FIRST_CHILD_HEIGHT = 10;
    var _header, _main, _footer;

    _header = {
        $self: $('header'),
        $wrapper: $('header > .wrapper'),
        $userBoxes: $('#user-boxes'),
        $scrollControls: null,
        $users: null,
        marginLeft: 0,
        init: function () {
            var _self = this;
            this.$users = this.$userBoxes.find('.users');
            this.$scrollControls = this.$userBoxes.find('.scrolling-control');
            this.$users.width(32 * this.$users.children().length);
            this.$scrollControls.on('click', function () {
                if ($(this).hasClass('pull-left')) {
                    _self.marginLeft += 32;
                    if (_self.marginLeft > 16) {
                        _self.marginLeft = 16;
                    }
                }
                else {
                    _self.marginLeft -= 32;
                    var deltaWidth = _self.$userBoxes.width() - _self.$users.width();
                    if (_self.marginLeft < deltaWidth) {
                        _self.marginLeft = deltaWidth - 16;
                    }
                }
                _self.$users.css('margin-left', _self.marginLeft + 'px');
            });
            this.autoLayout();
        },
        autoLayout: function () {
            if (this.$users.width() > this.$userBoxes.width()) {
                this.$scrollControls.removeClass('hide');
                this.marginLeft = 16;
            }
            else {
                this.$scrollControls.addClass('hide');
                this.marginLeft = 0;
            }
            this.$users.css('margin-left', this.marginLeft + 'px');
        },
        height: function () {
            return this.$wrapper.height();
        }
    };

    _main = {
        $self: $('main'),
        $wrapper: $('main > .wrapper'),
        $messages: $('#messages'),
        $messageFirstChild: null,
        typing: false,
        isTypingMessage: false,
        removeTypingTimeout: null,
        clearFixHeight: 0,
        topMessageId: null,
        forceScrollToBottom: false,
        enablePreviousMessages: true,
        init: function () {
            var _self = this;
            _self.$messageFirstChild = _self.$messages.children().eq(0);
            _self.clearFixHeight = _self.$messageFirstChild.height();
            _self.autoSetHeight();
            _self.$wrapper.scroll(function () {
                if (_self.$wrapper.scrollTop() == 0) {
                    previousMessages();
                }
            });
        },
        height: function () {
            return this.$self.height();
        },
        setHeight: function (height) {
            height = height < 0 ? 0 : height;
            this.$wrapper.height(height);
            this.autoSetFirstClearFixHeight();
        },
        autoSetHeight: function () {
            this.setHeight($(window).height() - _footer.height() - _header.height());
        },
        autoSetFirstClearFixHeight: function () {
            var height = 0;
            var $items = this.$messages.find('.clearfix');
            for (var i = $items.length - 1; i >= 1; --i) {
                height += $items.eq(i).height();
                if (height + this.clearFixHeight > this.height()) {
                    this.$messageFirstChild.height(MESSAGE_FIRST_CHILD_HEIGHT);
                    return;
                }
            }
            var restHeight = this.height() - height;
            this.$messageFirstChild.height(restHeight < MESSAGE_FIRST_CHILD_HEIGHT ? MESSAGE_FIRST_CHILD_HEIGHT : restHeight);
        },
        prepend: function (message) {
            this.$messageFirstChild.after(message);
        },
        append: function (message) {
            if (this.isTypingMessage || !this.typing) {
                this.$messages.append(message);
            }
            else {
                this.$messages.find('.message').last().before(message);
            }
            _footer.autoSetHeight();
        },
        clearRemoveTypingTimeout: function () {
            if (this.removeTypingTimeout) {
                clearTimeout(this.removeTypingTimeout);
                this.removeTypingTimeout = null;
            }
        },
        addTyping: function () {
            this.clearRemoveTypingTimeout();

            if (!this.typing) {
                this.isTypingMessage = true;
                this.append(strReplaceMany(HTML_LEFT_MESSAGE_TEMPLATE, {
                    id: 'typing',
                    message: HTML_TYPING_MESSAGE_TEMPLATE
                }));
                this.isTypingMessage = false;
                this.typing = true;

                var _self = this;
                this.removeTypingTimeout = setTimeout(function () {
                    _self.removeTyping();
                }, REMOVE_TYPING_TIMEOUT);
            }
        },
        removeTyping: function () {
            this.clearRemoveTypingTimeout();

            this.$messages.find('#box-typing,#message-typing,#clearfix-typing').remove();
            this.typing = false;

            _footer.autoSetHeight();
        },
        scrollToBottom: function () {
            this.$wrapper.scrollTop(this.$wrapper.get(0).scrollHeight);
        },
        scrollToTop: function () {
            this.$wrapper.scrollTop(42);
        }
    };

    _footer = {
        $self: $('footer'),
        $wrapper: $('footer > .wrapper'),
        $textArea: $('#inputMessage'),
        $actions: $('#actions'),
        $button: $('#buttonSend'),
        $enter: $('#inputEnter'),
        isTyping: false,
        typingTimeout: null,
        lastTypingTime: 0,
        maxLength: 0,
        lineHeight: 0,
        paddingTop: 0,
        paddingBottom: 0,
        init: function () {
            var _self = this;
            _self.maxLength = parseInt(_self.$textArea.attr('maxlength'));
            _self.lineHeight = parseInt(_self.$textArea.css('line-height'));
            _self.paddingTop = parseInt(_self.$textArea.css('padding-top'));
            _self.paddingBottom = parseInt(_self.$textArea.css('padding-bottom'));
            _self.$textArea.on('keyup', function () {
                if (_self.$textArea.val().length >= _self.maxLength) {
                    _self.$textArea.addClass('text-red');
                }
                else {
                    _self.$textArea.removeClass('text-red');
                }
                _self.autoSetHeight();

                _self.endTyping();
            }).on('keydown', function (e) {
                if (e.keyCode == 13 && _self.$enter.is(':checked')) {
                    _self.$button.trigger('click');
                    return false;
                }
                if (e.keyCode != 116) { // not refresh browser
                    _self.startTyping();
                }
            }).on('startTyping', function () {
                conversationToPusher('typing', {
                    value: true
                });
            }).on('endTyping', function () {
                conversationToPusher('typing', {
                    value: false
                });
            }).focus();
            _self.$button.on('click', function () {
                var input = _self.$textArea.val();
                _self.$textArea.val('').focus();
                sendMessage(input);
            });
            var cookieEnterToSend = Cookies.get('enter_to_send');
            if (typeof cookieEnterToSend === 'undefined') {
                Cookies.set('enter_to_send', 1);
                cookieEnterToSend = 1;
            }
            _self.$enter.prop('checked', cookieEnterToSend == 1);
            _self.$button.text(cookieEnterToSend == 1 ? 'Enter' : 'Send');
            _self.$enter.on('click', function () {
                var isChecked = $(this).is(':checked');
                Cookies.set('enter_to_send', isChecked ? 1 : 0);
                _self.$button.text(isChecked == 1 ?
                    _self.$button.attr('data-action-press-enter') : _self.$button.attr('data-action-send'));
            });
        },
        clearTypingTimeout: function () {
            if (this.typingTimeout) {
                clearTimeout(this.typingTimeout);
                this.typingTimeout = null;
            }
        },
        startTyping: function () {
            this.clearTypingTimeout();

            var typingTime = new Date().getTime();
            if (!this.isTyping || typingTime - this.lastTypingTime > TYPING_TIMEOUT) {
                this.isTyping = true;
                this.lastTypingTime = typingTime;
                this.$textArea.trigger('startTyping');
            }
        },
        endTyping: function () {
            if (this.isTyping) {
                this.clearTypingTimeout();

                var _self = this;
                this.typingTimeout = setTimeout(function () {
                    _self.isTyping = false;
                    _self.$textArea.trigger('endTyping');
                }, TYPING_TIMEOUT);
            }
        },
        height: function () {
            return this.$self.height();
        },
        setHeight: function (height) {
            if (this.$actions.css('float') == 'none') {
                height += this.$actions.height();
            }
            this.$wrapper.height(height < 0 ? 0 : height);
        },
        autoSetHeight: function () {
            var _self = this;
            var textAreaHeight = _self.lineHeight * _self.$textArea.val().split('\n').length;
            _self.$textArea.height(textAreaHeight);
            _self.setHeight(textAreaHeight + _self.paddingTop + _self.paddingBottom + 1);

            _main.autoSetHeight();
        }
    };

    function previousMessages() {
        if (!_main.enablePreviousMessages) return;
        var api = new KatnissApi(true);
        api.get('messages', {
            previous: 1,
            conversation_id: CONVERSATION_ID,
            message_id: _main.topMessageId
        }, function (isFailed, data, messages) {
            if (isFailed || data.messages.length < data.max_messages) {
                _main.enablePreviousMessages = false;
            }
            if (!isFailed) {
                unShiftMessages(data.messages);
                if (_main.$messageFirstChild.height() > MESSAGE_FIRST_CHILD_HEIGHT) {
                    _main.forceScrollToBottom = true;
                    previousMessages();
                }
            }
        });
    }

    function conversationToPusher(type, data) {
        sendToPusher(CONVERSATION_CHANNEL, {
            type: type,
            data: data
        });
    }

    function sendMessage(content) {
        if (content.trim() != '') {
            var api = new KatnissApi(true);
            api.post('messages', {
                conversation_id: CONVERSATION_ID,
                content: content
            }, function (isFailed, data, messages) {
                conversationToPusher('conversation', {
                    message: data.message,
                    device_id: data.device_id
                });
            });
        }
    }

    function messageFilter(message) {
        var pattern = /((http|https|ftp|ftps)\:\/\/[^\s]+)/g;
        message = message.replace(pattern, '<a href="$1" target="_blank">$1</a>');

        message = message.replace(/[\n]/g, '<br>');

        return message;
    }

    function unShiftMessages(messages) {
        if (messages.length <= 0) return;

        var needScrollToBottom = _main.topMessageId == null || _main.forceScrollToBottom;
        _main.forceScrollToBottom = false;
        var htmlMessage;
        for (var i in messages) {
            htmlMessage = messageFilter(messages[i].content);
            htmlMessage = strReplaceMany(
                !messages[i].is_owner ? HTML_LEFT_MESSAGE_TEMPLATE : HTML_RIGHT_MESSAGE_TEMPLATE,
                {
                    id: messages[i].id,
                    message: htmlMessage,
                    style: 'background:' +
                    (isSet(messages[i].device_id) ?
                        '#' + CONVERSATION_DEVICES[messages[i].device_id]
                        : 'url(' + CONVERSATION_DEVICES[messages[i].user_id] + ')' )
                }
            );
            _main.prepend(htmlMessage);
            _main.topMessageId = messages[i].id;
        }
        _footer.autoSetHeight();
        if (needScrollToBottom) {
            _main.scrollToBottom();
        }
        else {
            _main.scrollToTop();
        }
    }

    function addMessage(message) {
        var htmlMessage = messageFilter(message.content);
        htmlMessage = strReplaceMany(
            !message.is_owner ? HTML_LEFT_MESSAGE_TEMPLATE : HTML_RIGHT_MESSAGE_TEMPLATE,
            {
                id: message.id,
                message: htmlMessage,
                style: 'background:' +
                (isSet(message.device_id) ?
                    '#' + CONVERSATION_DEVICES[message.device_id]
                    : 'url(' + CONVERSATION_DEVICES[message.user_id] + ')' )
            }
        );
        _main.append(htmlMessage);
        _main.scrollToBottom();
        if (!message.is_owner) {
            playSound(base64Sound(FACEBOOK_CHAT_SOUND_64));
        }
    }

    function addTyping() {
        _main.addTyping();
    }

    function removeTyping() {
        _main.removeTyping();
    }

    _header.init();
    _footer.init();
    _main.init();
    previousMessages();

    $(window).on('resize', function (e) {
        _header.autoLayout();
        _footer.autoSetHeight();
    });

    setupPushClient(
        ORTC_SERVER,
        ORTC_CLIENT_ID,
        ORTC_CLIENT_KEY,
        ORTC_CLIENT_SECRET
    );
    subscribePusher(CONVERSATION_CHANNEL, function (theClient, channel, msg) {
        var pushObject = $.parseJSON(msg);
        if (pushObject.type && pushObject.data) {
            switch (pushObject.type) {
                case 'conversation':
                    removeTyping();
                    var message = pushObject.data.message;
                    if (CURRENT_DEVICE_ID != pushObject.data.device_id) {
                        message.is_owner = false;
                    }
                    addMessage(message);
                    break;
                case 'typing':
                    if (pushObject.data.value == true) {
                        addTyping();
                    }
                    else {
                        removeTyping();
                    }
                    break;
                default:
                    console.log('Unknown action.');
                    break;
            }
        }
    });
    registerPusher();
});
