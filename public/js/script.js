function addMessageBlock(color, user, message, my) {
    let date = new Date().format('d.m.Y в H:i');
    return (my ? "<div class='my_message'>" : "") + "<div class='msg' style='background: " + color + "'><span class='msg_nick'>" + user + "</span>" + message + "<span class='datetime'>" + date + "</span></div>" + (my ? "</div>" : "");
}

function cancelEditing() {
    type = 0;
    $('.message_input').val('').removeAttr('data-edit');
    $('.sendMessage').text('Отправить');
    $('.cancelButton').addClass('closed');
}

$(document).ready(function () {

    var type = 0;

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
            $.post('/send', { _token: $('#token').val(), message : msg }, function() {
                CometServer().web_pipe_send("web_boguchat_newMessage", { "color" : color, "nickname" : nickname, "msg" : msg });
                $('.messages_field').prepend(addMessageBlock(color, nickname, msg, true));
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
        $('.messages_field').prepend(addMessageBlock(message.data.color, message.data.nickname, message.data.msg, false));
    });

    CometServer().subscription("web_boguchat_editMessage", function(message) {
        $('#' + message.data.id).find('.msgMessage').text(message.data.text);
    });

    $('.my_message .msg').on({
        'mouseenter' : function () {
            $(this).find('.buttons').fadeIn(100);
        },
        'mouseleave' : function () {
            $(this).find('.buttons').fadeOut(100);
        },
    });

    $('.edit').on('click', function() {
        type = 1;
        msg = $(this).parent().parent();
        $('.message_input').val(msg.find('.msgMessage').text()).attr('data-edit', msg.parent().attr('id'));
        $('.sendMessage').text('Изменить');
        $('.cancelButton').removeClass('closed');
    });

    $('.cancelButton').on('click', function() {
        cancelEditing();
    });

});
