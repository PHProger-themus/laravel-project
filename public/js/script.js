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
        $.post('/send', {_token: $('#token').val(), message : msg}, function() {
            CometServer().web_pipe_send("web_boguchat", {"message":msg});
            $('.messages_field').prepend('<div class="msg"> ' + $('.user_nickname').html() + ': ' + msg + '</div>');
            $('.message_input').val('');
        });
    });

    CometServer().subscription("web_boguchat", function(message)
    {
        $('.messages_field').prepend('<div class="msg"> ' + $('.user_nickname').html() + ': ' + message.data.message + '</div>');
        //$("#WebChatFormForm").append("<p><b>"+strip(msg.data.name)+": </b>"+msg.data.text+"</p>");
    });

});
