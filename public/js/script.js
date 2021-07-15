function addMessageBlock(color, user, message, new_mes_id, my) {
    let date = new Date().format('d.m.Y в H:i');
    return "<div id='" + new_mes_id + "' class='msg_block " + (my ? "my_message" : "") + "'>" +
                "<div class='msg' style='background: " + color + "'>" +
                    "<div class='buttons'>" +
                        "<span class='edit'>✎</span>" +
                        "<span class='delete'>🗑</span>" +
                    "</div>" +
                    "<span class='like hidden'>❤<span class='qty'></span></span>" +
                    "<span class='msg_nick'>" + user + "</span>" +
                    "<span class='msgMessage'>" + message + "</span>" +
                    "<span class='datetime'>" + date + "</span>" +
                "</div>" +
            "</div>";
}

function incrementLike(likeBlock, my) { //Добавляем лайк. Второй параметр - мы ли ставим лайк (в сокетах нужен false, чтобы у других людей ваш лайк не окрашивался как свой)
    likeQty = likeBlock.find('.qty'); //Блок с кол-вом лайков
    if (likeBlock.hasClass('hidden')) { //Если блок изначально спрятан, значит у него нет лайков
        likeBlock.removeClass('hidden').fadeIn(100); //Покажем его (и насильно применим fadeIn, без него если пользователь поставит лайк и затем уберет резко мышь с сообщения, он исчезнет и больше не появится, так как у него теперь нет .hidden)
        likeQty.addClass('filled').text('1'); //И поставим ему первый лайк
    } else {
        likeQty.text(parseInt(likeQty.text()) + 1); //Иначе же лайки уже стоят, и просто прибавим еще один
    }
    if (my) likeQty.addClass('my'); //Если это наш лайк, окрасим его в красный
}

function decrementLike(likeBlock, my) {
    likeQty = likeBlock.find('.qty');
    newQty = parseInt(likeQty.text()) - 1; //Вычтем лайк из кол-ва
    if (!newQty) { //Если их стало 0
        likeBlock.addClass('hidden'); //Спрячем блок
        likeBlock.find('.qty').text(''); //И уберем кол-во
    } else {
        likeQty.text(newQty);
    }
    if (my) likeQty.removeClass('my'); //Уберем окраску лайка, так как мы убрали свой лайк
}

var type = 0;

function cancelEditing() {
    type = 0;
    $('.message_input').val('').removeAttr('data-edit');
    $('.sendMessage').text('Отправить');
    $('.cancelButton').addClass('closed');
}

function closePopup() {
    $('.popup_back').fadeOut(300).removeClass('visible');
    $('.popupDelete').removeAttr('data-del');
}

$(document).ready(function () {

    CometServer().start({dev_id:2607});

    $('.block_switch').on('click', function() {
        $('.block_visible').removeClass('block_visible');
        $('.switch_visible').removeClass('switch_visible');
        $('.' + $(this).attr('data-block')).addClass('block_visible');
        $(this).addClass('switch_visible');
    });

    $('button.close').on('click', function () {
       $(this).parent().remove();
    });

    $('.sendMessage').on('click', function () {
        if (!type) {
            let msg = $('.message_input').val();
            let color = $('.userColor').val();
            let nickname = $('.my_nickname').text();
            $.post('/send', { _token: $('#token').val(), message : msg }, function(new_mes_id) {
                CometServer().web_pipe_send("web_boguchat_newMessage", { "color" : color, "nickname" : nickname, "msg" : msg, "new_mes_id" : new_mes_id });
                $('.messages_field').prepend(addMessageBlock(color, nickname, msg, new_mes_id, true));
                $('.message_input').val('');
            });
        } else {
            let id = $('.message_input').attr('data-edit');
            let text = $('.message_input').val();
            $.post('/edit', { _token: $('#token').val(), id : id, text : text }, function(accepted) {
                CometServer().web_pipe_send("web_boguchat_editMessage", { "id" : id, "text" : text });
                if (accepted) {
                    $('#' + id).find('.msgMessage').text(text);
                }
                cancelEditing();
            });
        }
    });

    CometServer().subscription("web_boguchat_newMessage", function(message) {
        $('.messages_field').prepend(addMessageBlock(message.data.color, message.data.nickname, message.data.msg, message.data.new_mes_id, false));
    });

    CometServer().subscription("web_boguchat_editMessage", function(message) {
        $('#' + message.data.id).find('.msgMessage').text(message.data.text);
    });

    CometServer().subscription("web_boguchat_deleteMessage", function(message) {
        let msg = $('#' + message.data.id);
        msg.find('.msg').addClass('deleted');
        setTimeout(function () {
            msg.remove();
        }, 300);
    });

    CometServer().subscription("web_boguchat_likeMessage", function(message) {
        let likeBlock = $('#' + message.data.id).find('.like');
        if (message.data.liked) {
            incrementLike(likeBlock, false);
        } else {
            decrementLike(likeBlock, false);
        }
    });

    $(document).on('mouseenter', '.msg', function () {
        if ($(this).parent().hasClass('my_message')) $(this).find('.buttons').fadeIn(100);
        $(this).find('.like.hidden').fadeIn(100); //Показываем только спрятанные лайки, остальные и так видны
    });
    $(document).on('mouseleave', '.msg', function () {
        if ($(this).parent().hasClass('my_message')) $(this).find('.buttons').fadeOut(100);
        $(this).find('.like.hidden').fadeOut(100);
    });

    // $(document).on('.msg', {
    //     'mouseenter' : function () {
    //         console.log('gt');
    //
    //     },
    //     'mouseleave' : function () {
    //
    //     },
    // });

    $(document).on('click', '.edit', function () {
        type = 1;
        msg = $(this).closest('.msg');
        $('.message_input').val(msg.find('.msgMessage').text()).attr('data-edit', msg.parent().attr('id'));
        $('.sendMessage').text('Изменить');
        $('.cancelButton').removeClass('closed');
    });

    $('.cancelButton').on('click', function() {
        cancelEditing();
    });

    $(document).on('click', '.delete', function () {
        $('.popup_back').fadeIn(300).addClass('visible');
        $('.popupDelete').attr('data-del', $(this).closest('.msg_block').attr('id'));
    });

    $('.popupCancel').on('click', function() {
        closePopup();
    });

    $('.popupDelete').on('click', function() {
        let id = $(this).attr('data-del');
        $.post('/delete', { _token: $('#token').val(), id : id }, function(accepted) {
            CometServer().web_pipe_send("web_boguchat_deleteMessage", { "id" : id });
            if (accepted) {
                let msg = $('#' + id);
                msg.find('.msg').addClass('deleted');
                setTimeout(function () {
                    msg.remove();
                }, 300);
            }
            closePopup();
        });
    });

    $(document).on('click', '.like', function () {
        let id = $(this).closest('.msg_block').attr('id');
        $.post('/like', { _token: $('#token').val(), id : id }, function(liked) {
            CometServer().web_pipe_send("web_boguchat_likeMessage", { "id" : id, "liked" : liked });
            let likeBlock = $('#' + id).find('.like');
            if (liked) {
                incrementLike(likeBlock, true);
            } else {
                decrementLike(likeBlock, true);
            }
        });
    });

});
