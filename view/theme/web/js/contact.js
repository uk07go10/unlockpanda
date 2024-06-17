var contactText = {
    warning: 'Warning',
    all_fields_required: 'All fields are required.',
    ok: 'Thank you!',
    sent: 'Message sent - we will get back to you shortly.'
};
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
            Swal.fire({
                title: contactText.warning,
                text: contactText.all_fields_required,
                icon: "warning",
                confirmButtonText: "OK",
                confirmButtonColor: "#C90000"
            });
            clickedSubmit = false;
        } else {
            $.post('index.php?route=main/home/contact', {
                name: $name,
                email: $email,
                message: $message
            }, function () {
                Swal.fire({
                    title: contactText.ok,
                    text: contactText.sent,
                    icon: "success",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#C90000"
                });

                $('#name').val('');
                $('#email').val('');
                $('#message').val('');

                clickedSubmit = false;
            });
        }
    });
});