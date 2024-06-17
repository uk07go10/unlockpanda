<a id="button-pp-express" style="display: block; float:right;">
    <span id="pp-loading"><?php echo $text_wait; ?></span>
</a>

<script>

    var clicked = false;
    function handleNewsletter() {
    }

    window.paypalCheckoutReady = function () {
        $('#pp-loading').hide();

        paypal.checkout.setup('<?php echo $merchant_id ?>', {
            environment: '<?php echo $environment ?>',
            container: 'button-pp-express',
            click: function () {
                if(!clicked && !window.dataInFlight) {
                    clicked = true;
                    window.dataInFlight = true;
                    $("#loading").show();

                    handleNewsletter();
                    paypal.checkout.initXO();

                    $.post('index.php?route=payment/generic/create_order&fp=' + getFp(), {}, function (data) {
                        if(!data.result) {
                            alert("Problem encountered! If the problem persists please contact admin.");
                            location.reload();
                            return;
                        }

                        $.post('<?php echo $actionDoExpressCheckoutPayment ?>')
                            .done(function(data) {
                                data = $.parseJSON(data);
                                paypal.checkout.startFlow(data.token);
                            })
                            .fail(function () {
                                paypal.checkout.closeFlow();
                                clicked = false;
                                window.dataInFlight = false;
                            });
                    });
                }
                }
        });
    };
</script>
<script src="//www.paypalobjects.com/api/checkout.js" async></script>