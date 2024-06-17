<?php if($stripe_enabled): ?>

    <link rel="stylesheet" href="https://checkout.stripe.com/v3/checkout/button.css"/>
    <script src="https://checkout.stripe.com/checkout.js"></script>
    <button id="pay-stripe" class="stripe-button-el">
        <span style="display: block; min-height: 30px;"><?php echo $stripe_payment_button_text ?></span>
    </button>
    <script>
        var orderSubmitted = false;
        function stripeSendToBackend(token) {
            window.dataInFlight = true;
            $("#loading").show();
            $.post("index.php?route=payment/stripe/charge&v=<?php echo $stripe_mode ?>", token, function(data) {
                console.log(data);
                if(data.result) {
                    window.location = "index.php?route=checkout/success&st=Completed"
                } else {
                    window.dataInFlight = false;
                    $("#loading").hide();
                    alert(data.error.message);
                }
            });
        }

        var handler = StripeCheckout.configure({
            key: "<?php echo $stripe_publishable_key ?>",
            image: "/image/payment_logo.jpg",
            name: "UnlockPanda.com",
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
            handleNewsletter();
            handler.open();
            e.preventDefault();
        });

        $(window).on("popstate", function() {
            handler.close();
        });

    </script>
<?php endif; ?>