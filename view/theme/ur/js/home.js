var text = text || {};
var addToCartClicked = false;
text.home = {
    en: {
        error: {
            carrier: 'Carrier has to be selected',
            manufacturer: 'Manufacturer has to be selected',
            model: 'Model has to be selected',
            imei: 'IMEI number is not valid',
            email: 'Email is not valid',
            phone: 'Phone number is incorrect',

            sorry: 'Sorry!'
        },
        delayed: {
            title: 'Service delayed',
            text: 'This service is delayed. If you want, you can continue your order, but beware that the ' +
                'delivery time will be longer than advertised.'
        },
        duplicate: {
            title: 'Duplicate order detected',
            text: 'We have detected that you already have this IMEI in your cart. Would you like to add it anyway?',
            button_yes: 'No, go to checkout',
            button_no: 'Yes, add to cart'
        }, lessModels: {
            hover: 'Your brand is not showing up?',
            tooltip: 'Unfortunately, CDMA carriers unlocking is limited to brands listed below - we can\'t unlock your phone if it\'s not on the list. We\'re sorry!'
        },
        emailFix: {
            'text': 'Did you mean',
            'btinternet': 'Unfortunately btinternet might reject our email. Please use another email to ensure that you receive your order.'
        }
    },
    es: {
        error: {
            carrier: 'Carrier tiene que ser seleccionado',
            manufacturer: 'El fabricante tiene que ser seleccionado',
            model: 'Modelo tiene que ser seleccionado',
            imei: 'El número IMEI no es válido',
            email: 'El correo no es válido',
            phone: 'El número de celular es incorrecto',

            sorry: 'Lo sentimos!'
        },
        delayed: {
            title: 'Servicio retrasado',
            text: 'Este servicio se retrasa. Si lo desea, puede continuar su pedido, pero tenga cuidado de ' +
                'que el plazo de entrega será más largo de lo anunciado.'
        },
        duplicate: {
            title: 'Se ha detectado una orden duplicada',
            text: 'Hemos detectado que este IMEI ya está en tu carrito de compras. Deseas agregarlo de nuevo?',
            button_yes: 'No, vaya a la caja',
            button_no: 'Si, agregar a mi carrito'
        },
        lessModels: {
            hover: 'Tu marca no está disponible?',
            tooltip: 'Desafortunadamente, para proveedores CDMA solamente se pueden desbloquear las marcas que figuran a continuación. No podemos desbloquear tu teléfono si no está en la lista. Lo sentimos.'
        },
        emailFix: {
            'text': 'Querías decir',
            'btinternet': 'Desafortunadamente btinternet puede rechazar nuestro correo. Por favor utiliza otro email para asegurar que recibirás tu orden.'
        }
    }
};

var homeText = text.home[language];

$(function () {

    var bugsnagify = function(callback) {
        if (typeof(Bugsnag) !== "undefined") {
            callback();
        }
    };

    $("#carrier-select-message-tooltip")
        .text(homeText.lessModels.hover)
        .attr("title", homeText.lessModels.tooltip)
        .tooltip("fixTitle");

    // scombobox

    function beforeOpen() {
        if (this.scombobox("val") == -1) {
            this.scombobox("val", "");
        }
        this.scombobox("rebuildDict");
    }

    function beforeClose() {
        if (this.scombobox("val") == "") {
            this.scombobox("val", "-1");
        }
    }


    var valueHandler = function (element, callback) {
        return function () {
            setTimeout(function () {
                var value = element.scombobox("val");
                if (typeof(callback) === "function") {
                    callback(value);
                }
            }, 500);
        }
    };

    var options = {
        fullMatch: true,
        highlight: false,
        sortAsc: false,
        sort: false,
        filterIgnoreCase: true,
        hideSeparatorsOnSearch: true,
        beforeOpen: beforeOpen,
        beforeClose: beforeClose
    };

    $("#carrier-select").scombobox($.extend({}, options, {
        afterClose: function() {
            setTimeout(function() {
                var show = false;
                var $select = $("#carrier-select");
                var carrierText = $select
                    .find(".scombobox-list")
                    .find(".scombobox-hovered")
                    .text().toLowerCase();

                lessModelsWarningCarriers.forEach(function(value) {
                    if(carrierText.indexOf(value) > -1) {
                        show = true;
                    }
                });

                if(show) {
                    $("#carrier-select-message").slideDown();
                } else {
                    $("#carrier-select-message").slideUp();
                }
            }, 500);
        }
    }));
    $("#brand-select").scombobox($.extend({}, options, {}));
    $("#model-select").scombobox($.extend({}, options, {}));

    var $carrierSelect = $('#carrier-select');
    var $brandSelect = $('#brand-select');
    var $modelSelect = $('#model-select');
    var $imei = $('#imei');
    var $email = $('#email');
    var $phone = $('#phone');

    $(".scombobox-display").each(function () {
        $(this).addClass("order-input form-control");
        $('<p class="help-block"></p>').insertAfter($(this));
    });

    $('<p class="help-block"></p>').insertAfter($imei);
    $('<p class="help-block"></p>').insertAfter($email);
    $('<p class="help-block"></p>').insertAfter($phone);

    $('#phone').intlTelInput({
        utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/13.0.2/js/utils.js',
        preferredCountries: ['us', 'mx']
    });

    $phone = $('#phone');

    var setError = function (field, text, nested) {
        if(typeof(nested) === 'undefined') {
            nested = false;
        }

        var parent = nested ? field.parent().parent() : field.parent();
        parent.addClass("has-error").find("p.help-block").text(text);
    };

    var resetError = function (field, nested) {
        if(typeof(nested) === 'undefined') {
            nested = false;
        }

        var parent = nested ? field.parent().parent() : field.parent();
        parent.removeClass("has-error").find("p.help-block").text('');
    };

    var resetAllErrors = function () {
        [$carrierSelect, $brandSelect, $modelSelect, $imei, $email].forEach(function (field) {
            resetError(field);
        });

        resetError($phone, true);
    };


    $carrierSelect.scombobox("change", function () {
        resetError($carrierSelect);
        $brandSelect.scombobox("disabled", true);
        $modelSelect.scombobox("disabled", true);
        $("#unlock-details-row").hide();
        valueHandler($carrierSelect, function () {
            $.get('index.php?route=main/ajax/brands&carrier_id=' + $carrierSelect.scombobox("val"), function (data) {
                $brandSelect.scombobox("fill", data);
                $modelSelect.scombobox("fill", [{
                    value: -1,
                    text: language === 'en' ? "Please select manufacturer first.." : "Selecciona la marca primero"
                }]);
                $brandSelect.scombobox("disabled", false);
                $modelSelect.scombobox("disabled", false);
                valueHandler($brandSelect)();
                valueHandler($modelSelect)();
            });
        })();
    });

    $brandSelect.scombobox("change", function () {
        resetError($brandSelect);
        $modelSelect.scombobox("disabled", true);
        $("#unlock-details-row").hide();
        valueHandler($brandSelect, function () {
            $.get('index.php?route=main/ajax/products&category_id=' + $brandSelect.scombobox("val") + "&carrier_id=" + $carrierSelect.scombobox("val"), function (data) {
                $modelSelect.scombobox("fill", data);
                $modelSelect.scombobox("disabled", false);
                valueHandler($modelSelect)();
            });

            $.get('index.php?route=main/ajax/phoneNumberAdvised&category_id=' + $brandSelect.scombobox("val") + "&carrier_id=" + $carrierSelect.scombobox("val"), function (data) {
                var $phoneBlock = $('#phone-block');
                if(data.advise) {
                    $phoneBlock.slideDown();
                } else {
                    $phoneBlock.slideUp();
                }
            });
        })();
    });

    $modelSelect.scombobox("change", function () {
        resetError($modelSelect);
        valueHandler($modelSelect, function () {
            if ($modelSelect.scombobox("val") == -1) {
                $("#unlock-details-row").hide();
                return;
            }

            $.getJSON('index.php?route=main/ajax/product&product_id=' + $modelSelect.scombobox("val"), function (json) {

                var price = Number(json.price).toFixed(2);
                var deliver_time = json.delivery_time;
                var phone_img;

                var is_valid_image = typeof(json.image) !== "undefined"
                    && json.image !== undefined
                    && json.image !== null
                    && json.image.length > 0;

                if (is_valid_image) {
                    phone_img = '/image/' + json.image;
                } else {
                    phone_img = '/image/no_image.jpg';
                }

                $("#unlock-price").text("US $" + price);
                $("#unlock-delivery-time").text(deliver_time);
                $("#unlock-phone-image").attr("src", phone_img);

                $("#unlock-details-row").show();
            });
        })();
    });

    $imei.keyup(function () {
        resetError($imei);
    });

    $phone.keyup(function() {
        resetError($phone, true);
    });

    $('#email-correction-text').text(homeText.emailFix.text);
    $('#email-correction-value').on('click', function() {
        $('#email').val($('#email-correction-value').data('value'));
        $('#email-correction-block').slideUp();
    });

    $email.keyup(function () {
        resetError($email);

        $(this).val(
            $(this).val().toLowerCase().trim().split(' ').join('')
        );

        var $emailAddress = $(this).val();
        try {
            var domain = $emailAddress.split("@")[1];
            if(domain === "btinternet.com") {
                alert(homeText.emailFix.btinternet);
                $(this).val("");
                $("#email").focus();
                return;
            }
        } catch(err) {

        }

        $(this).mailcheck({
            suggested: function(el, suggestion) {
                $('#email-correction-value')
                    .attr('data-value', suggestion.full)
                    .html(suggestion.address + '@<strong>' + suggestion.domain + '</strong>');
                $('#email-correction-block').slideDown();
            },
            empty: function(element) {
                $('#email-correction-block').slideUp();
            }
        });
    });

    function isIMEIValid(imei) {

        if (!/^[0-9]{15}$/.test(imei)) {
            return false;
        }

        var sum = 0, factor = 2, checkDigit, multipliedDigit;

        for (var i = 13, li = 0; i >= li; i--) {
            multipliedDigit = parseInt(imei.charAt(i), 10) * factor;
            sum += (multipliedDigit >= 10 ? ((multipliedDigit % 10) + 1) : multipliedDigit);
            (factor === 1 ? factor++ : factor--);
        }
        checkDigit = ((10 - (sum % 10)) % 10);

        return !(checkDigit !== parseInt(imei.charAt(14), 10))
    }

    function isEmailValid(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email) && email.length <= 96;
    }

    var validateForm = function () {
        var valid = true;
        var invalidValues = ['', ' ', '0', '-1'];

        if (invalidValues.indexOf($carrierSelect.scombobox("val")) > -1) {
            setError($carrierSelect, homeText.error.carrier);
            valid = false;
        }

        if (invalidValues.indexOf($brandSelect.scombobox("val")) > -1) {
            setError($brandSelect, homeText.error.manufacturer);
            valid = false;
        }

        if (invalidValues.indexOf($modelSelect.scombobox("val")) > -1) {
            setError($modelSelect, homeText.error.model);
            valid = false;
        }

        if (!isIMEIValid($imei.val())) {
            setError($imei, homeText.error.imei);
            valid = false;
        }

        if (!isEmailValid($email.val())) {
            setError($email, homeText.error.email);
            valid = false;
        }

        var $$phone = $('#phone');
        var phoneNumber = $$phone.intlTelInput('getNumber');
        if (phoneNumber !== '' && !$$phone.intlTelInput('isValidNumber')) {
            setError($phone, homeText.error.phone, true);
            valid = false;
        }



        return valid;
    };

    function addToCart(force) {
        addToCartClicked = true;
        if (typeof(force) === "undefined") {
            force = false;
        }

        if (validateForm()) {
            var $unlockButton = $("#unlock-button");
            var originalButtonValue = $unlockButton.html();
            $unlockButton.html('<i class="zmdi zmdi-spinner spin"></i>');
            var carrierID = $carrierSelect.scombobox("val");
            var categoryID = $brandSelect.scombobox("val");
            var productID = $modelSelect.scombobox("val");
            var phone = $phone.intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164);

            $.post('index.php?route=checkout/cart/update', {
                carrier_id: carrierID,
                category_id: categoryID,
                product_id: productID,
                imei: $imei.val(),
                email: $email.val(),
                phone: phone,
                force: force
            }, function (data) {
                bugsnagify(function() {
                    Bugsnag.user = {
                        carrier_id: carrierID,
                        category_id: categoryID,
                        product_id: productID
                    };

                    Bugsnag.notify('ProceedToCheckout', 'continue');

                });
                try {
                    data = $.parseJSON(data);
                } catch (e) {
                    bugsnagify(function() {
                        Bugsnag.notifyException(e);
                    });
                    data = {error: false};
                }

                if (data.error) {
                    $unlockButton.html(originalButtonValue);
                    swal({
                        title: homeText.error.sorry,
                        text: data.error.warning,
                        type: "warning",
                        confirmButtonText: "OK"
                    });

                    try {
                        Bugsnag.notify('FormWarning', data.error.warning);
                    } catch (e) {

                    }
                } else if (data.duplicate) {
                    swal({
                        title: homeText.duplicate.title,
                        text: homeText.duplicate.text,
                        confirmButtonText: homeText.duplicate.button_yes,
                        cancelButtonText: homeText.duplicate.button_no,
                        showCancelButton: true
                    }, function(isConfirm) {
                        if(isConfirm) {
                            window.location.href = '/index.php?route=main/checkout';
                        } else {
                            addToCart(true);
                        }
                    });
                } else if (data.delayed) {
                    swal({
                        title: homeText.delayed.title,
                        text: homeText.delayed.text,
                        type: "warning",
                        confirmButtonText: "OK"
                    }, function(isConfirm) {
                        window.location.href = '/index.php?route=main/checkout';
                    });
                }  else {
                    window.location.href = '/index.php?route=main/checkout';
                }

                addToCartClicked = false;
            })
        } else {
            addToCartClicked = false;
        }
    }

    $("#unlock-button").click(function (e) {
        e.preventDefault();
        if (addToCartClicked) {
            return;
        }
        addToCart();
    });

});