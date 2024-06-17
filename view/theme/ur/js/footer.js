var text = text || {};
text.footer = {
    en: {
        warning: 'Warning',
        all_fields_required: 'All fields are required.'
    },
    es: {
        warning: 'Advertencia',
        all_fields_required: 'Todos los campos son obligatorios.\n'
    }
};

var footerText = text.footer[language];


$(function () {
    var clickedMessageSubmit = false;
    $("#message-submit").click(function (e) {
        e.preventDefault();
        if (clickedMessageSubmit) {
            return;
        }
        clickedMessageSubmit = true;

        var $name = $('#contact-name').val();
        var $email = $('#contact-email').val();
        var $message = $('#contact-message').val();

        if (!$name || !$email || !$message) {
            swal({
                title: footerText.warning,
                text: footerText.all_fields_required,
                type: "warning",
                confirmButtonText: "OK"
            });
            clickedMessageSubmit = false;
        } else {
            $.post('index.php?route=main/home/contact', {
                name: $name,
                email: $email,
                message: $message
            }, function () {
                $("#message-input").hide();
                $("#message-sent").show();

                clickedMessageSubmit = false;
            });
            fbq('track', 'Contact');
        }
    });
});