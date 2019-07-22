$('#tchat_form').submit(function (e) { 
    e.preventDefault();
    var user = $('#tchat_user').val();
    var message = $('#tchat_message').val();

    $.ajax({
        url : '/tchat',
        type : 'POST',
        dataType: 'html',
        data : {
            'tchat_user': user,
            'tchat_message': message
        },
        async: true,
        success: function ()
        {
            $('#tchat_message').val(''); 
        }
     });
 });

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
    }, 1000);
}

tchatRefresh();