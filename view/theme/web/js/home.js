const homeText = {
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
};

$(document).ready(function () {
    const $error = $('#error-notice');
    const $carrierSelect = $('#carrier-select');
    const $brandSelect = $('#brand-select');
    const $modelSelect = $('#model-select');

    const $imei = $('#imei');
    const $email = $('#email');
    const $phone = $('#phone');

    $imei.change(function () {
        hideError();
    });

    $email.change(function () {
        hideError();
    })

    $phone.change(function () {
        hideError();
    })

    let carrierId = null
    let brandId = null;
    let modelId = null;

    $phone.intlTelInput({
        utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/13.0.2/js/utils.js',
        preferredCountries: ['us', 'mx']
    });


    $carrierSelect.select2({
        placeholder: 'Select carrier..'
    });

    $brandSelect.select2({
        placeholder: 'Select Manufacturer..'
    });

    $modelSelect.select2({
        placeholder: 'Select Model'
    });

    $brandSelect.prop('disabled', true);
    $modelSelect.prop('disabled', true);

    $carrierSelect.on('select2:select', function (e) {
        hideError();

        carrierId = e.params.data.id;

        $brandSelect.empty().trigger('change');
        $modelSelect.empty().trigger('change');
        $("#unlock-details-row").hide();
        $("#unlock-price").text('');

        $.get('index.php?route=main/ajax/brands&carrier_id=' + carrierId, function (data) {
            data.forEach(function (entry) {
                $brandSelect.append(new Option(
                    entry.text,
                    entry.value,
                    false,
                    false
                ));
            })
            $brandSelect.prop('disabled', false);
        });

    });

    $brandSelect.on('select2:select', function (e) {
        hideError();

        brandId = e.params.data.id;

        $modelSelect.empty().trigger('change');
        $("#unlock-details-row").hide();
        $("#unlock-price").text('');

        $.get('index.php?route=main/ajax/products&category_id=' + brandId + "&carrier_id=" + carrierId, function (data) {
            data.forEach(function (entry) {
                $modelSelect.append(new Option(
                    entry.text,
                    entry.value,
                    false,
                    false
                ));
            })
            $modelSelect.prop('disabled', false);
        });
    });

    $modelSelect.on('select2:select', function (e) {
        hideError();

        modelId = e.params.data.id;

        $.getJSON('index.php?route=main/ajax/product&product_id=' + modelId, function (json) {

            const price = Number(json.price).toFixed(2);
            const deliveryTime = json.delivery_time;

            $("#unlock-price").text(" for $" + price);
            $("#unlock-delivery-time").text(deliveryTime);

            $("#unlock-details-row").show();
        });
    });

    function hideError() {
        $error.hide();
    }

    function showError(text) {
        $error.text(text);
        $error.show();
    }

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

    function validateForm() {
        const invalidValues = [null, '', ' ', '0', '-1'];

        if (invalidValues.includes(carrierId)) {
            showError(homeText.error.carrier);
            return false;
        }

        if (invalidValues.includes(brandId)) {
            showError(homeText.error.manufacturer);
            return false;
        }

        if (invalidValues.includes(modelId)) {
            showError(homeText.error.model);
            return false;
        }

        if (!isIMEIValid($imei.val())) {
            showError(homeText.error.imei);
            return false;
        }

        if (!isEmailValid($email.val())) {
            showError(homeText.error.email);
            return false;
        }

        const $$phone = $('#phone');
        const phoneNumber = $$phone.intlTelInput('getNumber');
        if (phoneNumber !== '' && !$$phone.intlTelInput('isValidNumber')) {
            showError(homeText.error.phone);
            return false;
        }

        return true;
    }

    let addToCartClicked = false;

    function addToCart(force) {
        addToCartClicked = true;
        if (typeof(force) === "undefined") {
            force = false;
        }

        if (validateForm()) {
            const carrierID = carrierId;
            const categoryID = brandId;
            const productID = modelId;

            const phone = $phone.intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164);

            $.post('index.php?route=checkout/cart/update', {
                carrier_id: carrierID,
                category_id: categoryID,
                product_id: productID,
                imei: $imei.val(),
                email: $email.val(),
                phone: phone,
                force: force
            }, function (data) {
                if (data.error) {
                    Swal.fire({
                        title: homeText.error.sorry,
                        text: data.error.warning,
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                } else if (data.duplicate) {
                    Swal.fire({
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
                    Swal.fire({
                        title: homeText.delayed.title,
                        text: homeText.delayed.text,
                        icon: "warning",
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


    $('#unlock-button').click(function (e) {
        e.preventDefault();
        if (addToCartClicked) {
            return;
        }
        addToCart();
    })
});