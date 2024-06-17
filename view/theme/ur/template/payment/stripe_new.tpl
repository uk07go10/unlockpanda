<?php if($stripe_enabled): ?>

    <link rel="stylesheet" href="https://checkout.stripe.com/v3/checkout/button.css"/>
    <script src="https://checkout.stripe.com/checkout.js"></script>
    <button id="pay-stripe" class="stripe-button-el">
        <span style="display: block; min-height: 30px;"><?php echo $stripe_payment_button_text ?></span>
    </button>
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
        
        function stripeSendToBackend(token) {
            $("#loading").show();
            swal({
                title: paymentText.wait_header,
                text: paymentText.wait_message,
                type: "success",
                confirmButtonText: "OK"
            });
            $.post("index.php?route=payment/stripe/charge&v=<?php echo $stripe_mode ?>&fp=" + getFp(), token, function(data) {
                console.log(data);
                if(data.result) {
                    swal.close();
                    window.location = "index.php?route=main/checkout/success&st=Completed"
                } else {
                    clicked = false;
                    $("#loading").hide();
                    swal({
                        title: 'Error',
                        text: data.error.message,
                        type: 'warning',
                        confirmButtonText: "OK"
                    });
                }
            });
        }
        
        $(function() {
            var handler = StripeCheckout.configure({
                key: "<?php echo $stripe_publishable_key ?>",
                image: "/image/payment_logo.jpg",
                name: "UnlockRiver.com",
                description: "<?php echo $description ?>",
                email: "<?php echo $email ?>",
                zipCode: "true",
                amount: "<?php echo $amount ?>",
                currency: "<?php echo $currency ?>",
                locale: "<?php echo $language ?>",
                allowRememberMe: false,
                bitcoin: <?php echo ($stripe_enabled_bitcoin ? "true" : "false") ?>,
                alipay: <?php echo ($stripe_enabled_alipay ? "true" : "false") ?>,
                token: stripeSendToBackend,
                billingAddress: <?php echo ($stripe_require_address ? "true" : "false") ?>
            });

            $("#pay-stripe").click(function(e) {
                if(!$('#terms-agree').is(':checked')) {
                    swal({
                        title: paymentText.header,
                        text: paymentText.message,
                        type: "warning",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                clicked = true;
                $("#loading").show();
                e.preventDefault();
                handler.open({
                    closed: function() {
                        clicked = false;
                        $("#loading").hide();
                    }
                });
            });

            $(window).on("popstate", function() {
                clicked = false;
                handler.close();
            });
        });

    </script>
<?php endif; ?>