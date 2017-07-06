function getMarkdown(data) {
    data = data
        .replace(/\n/g, '<br>')
        .replace(/\*(.+?)\*/g, '<strong>$1</strong>')
        .replace(/_(.+?)_/g, '<em>$1</em>')
        .replace(/```(.+?)```/g, '<code>$1</code>')
        .replace(/\[(.+?)\]\((.+?)\)/g, '<a href="$2">$1</a>');
    return data;
}

$(function () {

    $('#auth-link').click(function () {
        $('#notify-message').html('Ждём вашего сообщения <a href="https://telegram.me/AutoChanBot">@AutoChanBot</a> с текстом ' + code);
        $('#notify').fadeIn();
        setInterval(function () {
            $.get(
                'https://seibel.xyz/bots/auto.php?code=' + code,
                function (data) {
                    data = JSON.parse(data);
                    if(data.user_id) {
                        $.get(
                            'logic.php?act=success&code=' + code,
                            function () {
                                $('#notify-message').html('Авторизованы!');
                                location.reload();
                            }
                        );
                    }
                }
            );
        }, 1000);
    });

    $('[data-scroll]').click(function () {
        var scroll = $(this).data('scroll');
        $('html, body').animate({
            scrollTop: $(scroll).offset().top
        }, 500);
    });

    $('.post-add').find('.image-full').click(function () {
        $(this).parent().find('.image-inp').click();
    });

    $('.image-inp').change(function () {
        var reader = new FileReader();
        reader.readAsDataURL($(this)[0].files[0]);
        reader.onload = function() {
            $('.post-add').find('.image-full').attr('src', reader.result);
            $('#post-add-content').attr('maxlength', 200);
            $('#post-add-image-remove').show();
        }
    });

    $('#post-add-content').keyup(function () {
        var preview = $('#post-add-preview');
        var content = $('#post-add-content');
        preview.html(
            getMarkdown(content.val())
        );
    });

    $('#post-add-image-remove').click(function () {
        $('.post-add').find('.image-full').attr('src', 'images/image-add.png');
        $('#post-add-content').attr('maxlength', '');
        $('.image-inp').val('');
        $(this).hide();
    });

    $('[data-markdown]').each(function () {
        $(this).html(
            getMarkdown($(this).html())
        );
    });

    $('#channels').find('[name="name"]').keyup(function () {
        if(!$(this).val().match(/@/)) {
            $(this).val(
                '@' + $(this).val()
            );
        }
    });

});
