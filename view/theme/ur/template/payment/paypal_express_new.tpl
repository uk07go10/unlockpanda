<p style="text-align: left;">
    <input type="radio" id="pp-type-regular" name="pp-type" value="Login" checked="checked"/>
    <label for="pp-type-regular">
        <img height="40" src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/pp-acceptance-large.png" alt="PayPal Acceptance">
        <a href="javascript:window.open('https://www.paypal.com/webapps/mpp/paypal-popup','What is PayPal?','width=1200,height=700')" title="What is PayPal?" style="margin-left: 5px; text-decoration: underline">What is PayPal?</a>
    </label>
</p>
<p style="text-align: left;">
    <input type="radio" id="pp-type-card" value="Billing" name="pp-type"/>
    <label for="pp-type-card">
        <img width="250" src="/image/pp-cards.png" alt="Credit Card Badges">
    </label>
</p>

<a id="button-pp-express">
    <span id="pp-loading"><?php echo $text_wait; ?></span>
</a>
<script>
    var text = text || {};
    text.payment = {
        en: {
            header: 'Warning',
            message: 'Please accept the terms.'
        },
        es: {
            header: 'Advertencia',  
            message: 'Acepta los términos.'
        }
    };
    
    var paymentText = text.payment[language];
    
    window.paypalCheckoutReady = function () {
        $('#pp-loading').hide();

        paypal.checkout.setup('<?php echo $merchant_id ?>', {
            environment: '<?php echo $environment ?>',
            container: 'button-pp-express',
            onCancel: function() {
                clicked = false;
                $("#loading").hide();
            },
            click: function () {
                
                if(!$('#terms-agree').is(':checked')) {
                    swal({
                        title: paymentText.header,
                        text: paymentText.message,
                        type: "warning",
                        confirmButtonText: "OK"
                    });
                    return;
                }
                
                if (!clicked) {
                    $("#loading").show();
                    clicked = true;

                    paypal.checkout.initXO();

                    $.post('index.php?route=payment/generic/create_order&fp=' + getFp() + '&type=' + $('input[name=pp-type]:checked').val(), {}, function (data) {
                        if (!data.result) {
                            alert("Problem encountered! If the problem persists please contact admin.");
                            location.reload();
                            return;
                        }

                        $.post('<?php echo $actionDoExpressCheckoutPayment ?>')
                            .done(function (data) {
                                console.log(data);
                                data = $.parseJSON(data);
                                if(typeof(data.error) !== "undefined") {
                                    window.location.reload();
                                    $("#loading").hide();
                                } else {
                                    paypal.checkout.startFlow(data.token);
                                }
                            })
                            .fail(function (data) {
                                console.log(data);
                                paypal.checkout.closeFlow();
                                clicked = false;
                                $("#loading").hide();
                            });
                    });
                }
            }
        });
    };
</script>
<script src="//www.paypalobjects.com/api/checkout.js" async></script>