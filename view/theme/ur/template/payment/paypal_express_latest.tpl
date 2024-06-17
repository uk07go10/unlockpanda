<div id="button-pp-container">
    <a id="button-pp-new"></a>
</div>

<script type="text/javascript" src="//www.paypalobjects.com/api/checkout.min.js"></script>
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

    text.finalizing = {
        en: {
            header: 'Finalizing payment',
            message: 'Please wait a few seconds..'
        },
        es: {
            header: 'Finalizando el pago',
            message: 'Por favor espere unos segundos..'
        }
    };

    text.payPalError = {
        en: {
            header: 'Error',
            message: 'An error occured: '
        },
        es: {
            header: 'Error',
            message: 'Ocurrió un error: '
        }
    };

    language = language || 'en';

    var paymentText = text.payment[language];
    var finalizingText = text.finalizing[language];
    var payPalErrorText = text.payPalError[language];

    $(function () {
        var validation = {
            isValid: function() {
                return $('#terms-agree').is(':checked');
            },
            onChangeCheckbox: function(handler) {
                $('#terms-agree').on('change', handler);
            },
            toggleValidationMessage: function() {
                if(!validation.isValid()) {
                    swal({
                        title: paymentText.header,
                        text: paymentText.message,
                        type: "warning",
                        confirmButtonText: "OK"
                    });
                }
            },
            toggleButton: function(actions) {
                return validation.isValid() ? actions.enable() : actions.disable();
            }
        };


        var tries = 0;
        var initializePP = function() {
            $('#button-pp-new').remove();
            $('#button-pp-container').html("<a id='button-pp-new'></a>");

            paypal.Button.render({
                env: '<?php echo $environment ?>',
                style: {
                    label: 'buynow',
                    fundingicons: true, // optional
                    branding: true, // optional
                    size: 'medium', // small | medium | large | responsive
                    shape: 'rect',   // pill | rect
                    color: 'gold'   // gold | blue | silve | black
                },
                validate: function (actions) {
                    validation.toggleButton(actions);

                    validation.onChangeCheckbox(function () {
                        validation.toggleButton(actions);
                    });
                },
                onClick: function () {
                    validation.toggleValidationMessage();
                },
                payment: function (data, actions) {

                    return paypal.request.post('<?php echo $actionSetExpressCheckout ?>').then(function (res) {
                        paypal.request.post('index.php?route=payment/generic/create_order&fp=' + getFp());
                        return res.token;
                    });
                },
                onAuthorize: function (data, actions) {
                    if (data.returnUrl) {
                        swal({
                            title: finalizingText.header,
                            text: finalizingText.message,
                            type: "success",
                            showCancelButton: false,
                            showConfirmButton: false
                        });
                        window.location = data.returnUrl;
                    } else {
                        Bugsnag.metaData = {
                            response: data
                        };
                        Bugsnag.notify('NoReturnUrl', 'Missing return URL in onAuthorize call');
                    }
                },

                onCancel: function (data, actions) {
                    return paypal.request.post('index.php?route=payment/paypal_express/cancel').then(function () {
                        return true;
                    })
                },

                onError: function (err) {
                    tries++;
                    if (err.message.indexOf('xcomponent_init') === -1 || tries > 3) {
                        Bugsnag.notifyException(err);
                    }

                    if (tries > 3) {
                        if (err.message.indexOf('xcomponent_init') === -1) {
                            swal({
                                title: payPalErrorText.header,
                                text: payPalErrorText.message + err.message,
                                type: 'error',
                                confirmButtonText: 'OK'
                            })
                        }
                    } else {
                        if (err.message.indexOf('xcomponent_init') > -1) {
                            setTimeout(initializePP, 500);
                        }
                    }
                }
            }, '#button-pp-new');
        };

        initializePP();

    });

</script>