function addMessageBlock(color, user, message) {
    let date = new Date().format('d.m.Y в H:i');
    return "<div class='my_message'><div class='msg' style='background: " + color + "'><span class='msg_nick'>" + user + "</span>" + message + "<span class='datetime'>" + date + "</span></div></div>";
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
        let msg = $('.message_input').val();
        let color = $('.userColor').val();
        let nickname = $('.my_nickname').text();
        $.post('/send', {_token: $('#token').val(), message : msg}, function() {
            /*CometServer().web_pipe_send("web_boguchat", { "message" : msg, "user" : user });*/
            $('.messages_field').prepend(addMessageBlock(color, nickname, msg));
            $('.message_input').val('');
        });
    });

    /*CometServer().subscription("web_boguchat", function(message)
    {
        $('.messages_field').prepend('<div class="msg"> ' + message.data.user + ': ' + message.data.message + '</div>');
        //$("#WebChatFormForm").append("<p><b>"+strip(msg.data.name)+": </b>"+msg.data.text+"</p>");
    });*/

});
