var text = text || {};
text.contact = {
    en: {
        warning: 'Warning',
        all_fields_required: 'All fields are required.',
        ok: 'Thank you!',
        sent: 'Message sent - we will get back to you shortly.'
    },
    es: {
        warning: 'Advertencia',
        all_fields_required: 'Todos los campos son obligatorios.\n',
        ok: '¡Gracias!',
        sent: 'Mensaje enviado - nos pondremos en contacto con usted en breve.'
    }
};

var contactText = text.contact[language];


$(function () {
    var clickedSubmit = false;
    $("#submit").click(function (e) {
        e.preventDefault();
        if(clickedSubmit) {
            return;
        }
        clickedSubmit = true;

        var $name = $('#name').val();
        var $email = $('#email').val();
        var $message = $('#message').val();

        if (!$name || !$email || !$message) {
            swal({
                title: contactText.warning,
                text: contactText.all_fields_required,
                type: "warning",
                confirmButtonText: "OK"
            });
            clickedSubmit = false;
        } else {
            $.post('index.php?route=main/home/contact', {
                name: $name,
                email: $email,
                message: $message
            }, function () {
                swal({
                    title: contactText.ok,
                    text: contactText.sent,
                    type: "success",
                    confirmButtonText: "OK"
                });

                $('#name').val('');
                $('#email').val('');
                $('#message').val('');

                clickedSubmit=false;
            });
            fbq('track', 'Contact');
        }
    });
});