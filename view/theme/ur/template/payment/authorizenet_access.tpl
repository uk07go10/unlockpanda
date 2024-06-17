<?php if ($authorizenet_access_enabled): ?>
    <button id="pay-authorizenet-access" class="submit-btn-1 black-bg btn-hover-2" style="background-color: #279CCE"><?php echo $authorizenet_access_payment_button_text ?></button>
    <form method="post"
          action="<?php echo $authorizenet_gateway_url ?>"
          id="pay-authorizenet-access-form"
          name="pay-authorizenet-access-form">

        <input id="pay-authorizenet-access-token" type="hidden" name="token" value=""/>
    </form>

    <script>
        var text = text || {};
        text.payment = {
            en: {
                header: 'Warning',
                message: 'Please accept the terms.',
                wait_header: 'Processing',
                wait_message: 'Please wait..'
            },
            es: {
                header: 'Advertencia',
                message: 'Acepta los términos.',
                wait_header: 'Tratamiento',
                wait_message: 'Por favor espera..'
            }
        };

        var paymentText = text.payment[language];
        $(function () {
            $('#pay-authorizenet-access').click(function () {
                if(!$('#terms-agree').is(':checked')) {
                    swal({
                        title: paymentText.header,
                        text: paymentText.message,
                        type: "warning",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                $("#loading").show();
                swal({
                    title: paymentText.wait_header,
                    text: paymentText.wait_message,
                    type: "success",
                    confirmButtonText: "OK"
                });

                $.post("index.php?route=payment/authorizenet_access/session&v=<?php echo $authorizenet_access_mode ?>&fp=" + getFp(), {}, function (data) {
                    swal.close();
                    $("#loading").hide();
                    console.log(data);
                    if (data.result) {
                        fbq('track', 'InitiateCheckout');
                        $('#pay-authorizenet-access-token').val(data.id);
                        $('#pay-authorizenet-access-form')[0].submit();
                    } else {
                        clicked = false;
                        swal({
                            title: 'Error',
                            text: data.error[0].message,
                            type: 'warning',
                            confirmButtonText: "OK"
                        });
                    }
                });
            });
        })
    </script>
<?php endif; ?>