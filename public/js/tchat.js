function tchat(){
    $.ajax({
        url:'/tchat',
        type: "POST",
        async: true,
        success: function (data)
        {
            $('#tchat').html(data);
        }
    });
    tchatRefresh();
}

function tchatRefresh() {
    setTimeout(function() {
        $.ajax({
            url:'/tchat/messages',
            type: "POST",
            async: true,
            success: function (data)
            {
                $('#tchat_messages').html(data);
            }
        });

        tchatRefresh();
    }, 3000);
}

tchat();