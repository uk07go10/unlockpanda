<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <h1><?php echo $heading_title; ?></h1>
    <?php if (isset($error_warning)) { ?>
        <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="checkout">
        <div id="checkout">
            <div class="checkout-heading"><?php echo $text_checkout_option; ?></div>
            <div class="checkout-content"></div>
        </div>
        <?php if (!$logged) { ?>
            <div id="payment-address">
                <div class="checkout-heading"><span><?php echo $text_checkout_account; ?></span></div>
                <div class="checkout-content"></div>
            </div>
        <?php } else { ?>
            <div id="payment-address">
                <div class="checkout-heading"><span><?php echo $text_checkout_payment_address; ?></span></div>
                <div class="checkout-content"></div>
            </div>
        <?php } ?>
        <?php if ($shipping_required) { ?>
            <div id="shipping-address">
                <div class="checkout-heading"><?php echo $text_checkout_shipping_address; ?></div>
                <div class="checkout-content"></div>
            </div>
            <div id="shipping-method">
                <div class="checkout-heading"><?php echo $text_checkout_shipping_method; ?></div>
                <div class="checkout-content"></div>
            </div>
        <?php } ?>
        <div id="payment-method">
            <div class="checkout-heading"><?php echo $text_checkout_payment_method . ': ' . $payment_title; ?></div>
            <div class="checkout-content"></div>
        </div>
        <div id="confirm">
            <div class="checkout-heading"><?php echo $text_checkout_confirm; ?></div>
            <div class="checkout-content"></div>
        </div>
    </div>
    <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
    $('.checkout-heading a').live('click', function() {
        $('.checkout-content').slideUp('slow');
        $(this).parent().parent().find('.checkout-content').slideDown('slow');
    });

    function getShippingOrConfirm(lastContent) {
<?php if ($shipping_required && !isset($this->session->data['shipping_method'])): ?>
            $.ajax({
                url: 'index.php?route=checkout/shipping',
                dataType: 'json',
                success: function(json) {
                    if (json['redirect']) {
                        location = json['redirect'];
                    }
                    if (json['output']) {
                        $('#shipping-method .checkout-content').html(json['output']);
                    }
                    if (lastContent) {
                        $.each(lastContent, function(i, value) {
                            $.ajax({
                                url: 'index.php?route=' + value,
                                dataType: 'json',
                                success: function(json) {
                                    if (json['redirect']) {
                                        location = json['redirect'];
                                    }
                                    if (json['output']) {
                                        $(i + ' .checkout-content').slideUp('slow');
                                        $(i + ' .checkout-content').html(json['output']);
                                        $(i + ' .checkout-heading a').remove();
                                        $(i + ' .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
                                    }
                                }
                            });
                        });
                    }
                    $('#shipping-method .checkout-content').slideDown('slow');
                }
            });
<?php else: ?>
            $.ajax({
                url: 'index.php?route=checkout/shipping',
                dataType: 'json',
                success: function(json) {
                    if (json['redirect']) {
                        location = json['redirect'];
                    }
                    if (json['output']) {
                        $('#shipping-method .checkout-content').html(json['output']);
                        $('#shipping-method .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
                    }
                }
            });

            $.ajax({
                url: 'index.php?route=checkout/confirm',
                dataType: 'json',
                success: function(json) {
                    if (json['redirect']) {
                        location = json['redirect'];
                    }
                    if (json['output']) {
                        $('#confirm .checkout-content').html(json['output']);
                    }
                    if (lastContent) {
                        $.each(lastContent, function(i, value) {
                            $.ajax({
                                url: 'index.php?route=' + value,
                                dataType: 'json',
                                success: function(json) {
                                    if (json['redirect']) {
                                        location = json['redirect'];
                                    }
                                    if (json['output']) {
                                        $(i + ' .checkout-content').slideUp('slow');
                                        $(i + ' .checkout-content').html(json['output']);
                                        $(i + ' .checkout-heading a').remove();
                                        $(i + ' .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
                                    }
                                }
                            });
                        });
                    }
                    $('#confirm .checkout-content').slideDown('slow');
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError);
                }
            });
<?php endif ?>
    }

<?php if (!$logged): ?>
        $(document).ready(function() {
    <?php if ($address_already_exists): ?>
                    var list = {"#checkout" : "checkout/login", "#payment-address" : "checkout/guest", "#shipping-address" : "checkout/guest/shipping"}; //, "#shipping-method" : "checkout/shipping"
                    getShippingOrConfirm(list);
    <?php else: ?>
                    $.ajax({
                        url: 'index.php?route=checkout/login',
                        dataType: 'json',
                        success: function(json) {
                            if (json['redirect']) {
                                location = json['redirect'];
                            }
                            if (json['output']) {
                                $('#checkout .checkout-content').html(json['output']);
                            }

                            $('#checkout .checkout-content').slideDown('slow');
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
    <?php endif ?>
            });
<?php else: ?>
        $(document).ready(function() {
    <?php if ($address_already_exists): ?>
                    var list = {"#checkout" : "checkout/login", "#payment-address" : "checkout/address/payment", "#shipping-address" : "checkout/address/shipping"}; //, "#shipping-method" : "checkout/shipping"
                    getShippingOrConfirm(list);
    <?php else: ?>
                    $.ajax({
                        url: 'index.php?route=checkout/address/payment',
                        dataType: 'json',
                        success: function(json) {
                            if (json['redirect']) {
                                location = json['redirect'];
                            }
                            if (json['output']) {
                                $('#payment-address .checkout-content').html(json['output']);

                                $('#payment-address .checkout-content').slideDown('slow');
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
    <?php endif ?>
            });
<?php endif ?>

    // Checkout
    $('#button-account').live('click', function() {
        $.ajax({
            url: 'index.php?route=checkout/pec_checkout/' + $('input[name=\'account\']:checked').attr('value'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-account').attr('disabled', true);
                $('#button-account').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
            },
            complete: function() {
                $('#button-account').attr('disabled', false);
                $('.wait').remove();
            },
            success: function(json) {
                var jsonOld = json;
                $.ajax({
                    url: 'index.php?route=checkout/' + $('input[name=\'account\']:checked').attr('value'),
                    dataType: 'json',
                    beforeSend: function() {
                        $('#button-account').attr('disabled', true);
                        $('#button-account').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
                    },
                    complete: function() {
                        $('#button-account').attr('disabled', false);
                        $('.wait').remove();
                    },
                    success: function(json) {
                        $('.warning, .error').remove();
                        if (json['redirect']) {
                            location = json['redirect'];
                        }

                        if (json['output']) {
                            $('#payment-address .checkout-content').html(json['output']);
                        }
                        if (jsonOld) {
                            $.each(jsonOld, function(i, value) {
                                $('*[name="' + i + '"]').val(value);
                                if (i == 'country_id') {
                                    $('#payment-address select[name=\'country_id\']').trigger('change');
                                    alert('<?php echo addslashes($text_use_paypal_data); ?>');
                                }
                            });
                        }

                        $.ajax({
                            url: 'index.php?route=checkout/pec_checkout/session_method',
                            dataType: 'html',
                            success: function() {
                                $('#checkout .checkout-content').slideUp('slow');

                                $('#payment-address .checkout-content').slideDown('slow');

                                $('.checkout-heading a').remove();

                                $('#checkout .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
                            }
                        });
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    // Register
    $('#button-register').live('click', function() {
        $.ajax({
            url: 'index.php?route=checkout/register',
            type: 'post',
            data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'password\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-register').attr('disabled', true);
                $('#button-register').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
            },
            complete: function() {
                $('#button-register').attr('disabled', false);
                $('.wait').remove();
            },
            success: function(json) {
                $('.warning, .error').remove();

                if (json['error']) {
                    if (json['error']['warning']) {
                        $('#payment-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                        $('.warning').fadeIn('slow');
                    }

                    if (json['error']['firstname']) {
                        $('#payment-address input[name=\'firstname\'] + br').after('<span class="error">' + json['error']['firstname'] + '</span>');
                    }

                    if (json['error']['lastname']) {
                        $('#payment-address input[name=\'lastname\'] + br').after('<span class="error">' + json['error']['lastname'] + '</span>');
                    }

                    if (json['error']['email']) {
                        $('#payment-address input[name=\'email\'] + br').after('<span class="error">' + json['error']['email'] + '</span>');
                    }

                    if (json['error']['telephone']) {
                        $('#payment-address input[name=\'telephone\'] + br').after('<span class="error">' + json['error']['telephone'] + '</span>');
                    }

                    if (json['error']['company_id']) {
                        $('#payment-address input[name=\'company_id\'] + br').after('<span class="error">' + json['error']['company_id'] + '</span>');
                    }

                    if (json['error']['tax_id']) {
                        $('#payment-address input[name=\'tax_id\'] + br').after('<span class="error">' + json['error']['tax_id'] + '</span>');
                    }

                    if (json['error']['address_1']) {
                        $('#payment-address input[name=\'address_1\'] + br').after('<span class="error">' + json['error']['address_1'] + '</span>');
                    }

                    if (json['error']['city']) {
                        $('#payment-address input[name=\'city\'] + br').after('<span class="error">' + json['error']['city'] + '</span>');
                    }

                    if (json['error']['postcode']) {
                        $('#payment-address input[name=\'postcode\'] + br').after('<span class="error">' + json['error']['postcode'] + '</span>');
                    }

                    if (json['error']['country']) {
                        $('#payment-address select[name=\'country_id\'] + br').after('<span class="error">' + json['error']['country'] + '</span>');
                    }

                    if (json['error']['zone']) {
                        $('#payment-address select[name=\'zone_id\'] + br').after('<span class="error">' + json['error']['zone'] + '</span>');
                    }

                    if (json['error']['password']) {
                        $('#payment-address input[name=\'password\'] + br').after('<span class="error">' + json['error']['password'] + '</span>');
                    }

                    if (json['error']['confirm']) {
                        $('#payment-address input[name=\'confirm\'] + br').after('<span class="error">' + json['error']['confirm'] + '</span>');
                    }
                } else {
<?php if ($shipping_required): ?>
                        var shipping_address = $('#payment-address input[name=\'shipping_address\']:checked').attr('value');

                        if (shipping_address) {
                            $.ajax({
                                url: 'index.php?route=checkout/shipping',
                                dataType: 'json',
                                success: function(json) {
                                    if (json['redirect']) {
                                        location = json['redirect'];
                                    }
                                    if (json['output']) {
                                        $('#shipping-method .checkout-content').html(json['output']);

                                        $('#payment-address .checkout-content').slideUp('slow');

                                        $('#shipping-method .checkout-content').slideDown('slow');

                                        $('#checkout .checkout-heading a').remove();
                                        $('#payment-address .checkout-heading a').remove();
                                        $('#shipping-address .checkout-heading a').remove();
                                        $('#shipping-method .checkout-heading a').remove();
                                        $('#payment-method .checkout-heading a').remove();

                                        $('#shipping-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
                                        $('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');

                                        $.ajax({
                                            url: 'index.php?route=checkout/address/shipping',
                                            dataType: 'json',
                                            success: function(json) {
                                                if (json['redirect']) {
                                                    location = json['redirect'];
                                                }

                                                if (json['output']) {
                                                    $('#shipping-address .checkout-content').html(json['output']);
                                                }
                                            },
                                            error: function(xhr, ajaxOptions, thrownError) {
                                                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                            }
                                        });
                                    }
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                }
                            });
                        } else {
                            $.ajax({
                                url: 'index.php?route=checkout/address/shipping',
                                dataType: 'json',
                                success: function(json) {
                                    if (json['redirect']) {
                                        location = json['redirect'];
                                    }

                                    if (json['output']) {
                                        $('#shipping-address .checkout-content').html(json['output']);

                                        $('#payment-address .checkout-content').slideUp('slow');

                                        $('#shipping-address .checkout-content').slideDown('slow');

                                        $('#checkout .checkout-heading a').remove();
                                        $('#payment-address .checkout-heading a').remove();
                                        $('#shipping-address .checkout-heading a').remove();
                                        $('#shipping-method .checkout-heading a').remove();
                                        $('#payment-method .checkout-heading a').remove();

                                        $('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
                                    }
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                }
                            });
                        }
<?php else: ?>
                        $.ajax({
                            url: 'index.php?route=checkout/pec_checkout/session_method',
                            dataType: 'html',
                            success: function() {
                                $.ajax({
                                    url: 'index.php?route=checkout/confirm',
                                    dataType: 'json',
                                    success: function(json) {
                                        if (json['redirect']) {
                                            location = json['redirect'];
                                        }
                                        if (json['output']) {
                                            $('#confirm .checkout-content').html(json['output']);

                                            $('#payment-address .checkout-content').slideUp('slow');

                                            $('#confirm .checkout-content').slideDown('slow');

                                            $('#checkout .checkout-heading a').remove();
                                            $('#payment-address .checkout-heading a').remove();
                                            $('#confirm .checkout-heading a').remove();

                                            $('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
                                        }
                                    },
                                    error: function(xhr, ajaxOptions, thrownError) {
                                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                    }
                                });
                            }
                        });
<?php endif ?>
                    $.ajax({
                        url: 'index.php?route=checkout/address/payment',
                        dataType: 'json',
                        success: function(json) {
                            if (json['redirect']) {
                                location = json['redirect'];
                            }

                            if (json['output']) {
                                $('#payment-address .checkout-content').html(json['output']);

                                $('#payment-address .checkout-heading span').html('<?php echo $text_checkout_payment_address; ?>');
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    // Guest
    $('#button-guest').live('click', function() {
        $.ajax({
            url: 'index.php?route=checkout/guest',
            type: 'post',
            data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'checkbox\']:checked, #payment-address select'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-guest').attr('disabled', true);
                $('#button-guest').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
            },
            complete: function() {
                $('#button-guest').attr('disabled', false);
                $('.wait').remove();
            },
            success: function(json) {
                $('.warning, .error').remove();

                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    if (json['error']['warning']) {
                        $('#payment-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                        $('.warning').fadeIn('slow');
                    }

                    if (json['error']['firstname']) {
                        $('#payment-address input[name=\'firstname\'] + br').after('<span class="error">' + json['error']['firstname'] + '</span>');
                    }

                    if (json['error']['lastname']) {
                        $('#payment-address input[name=\'lastname\'] + br').after('<span class="error">' + json['error']['lastname'] + '</span>');
                    }

                    if (json['error']['email']) {
                        $('#payment-address input[name=\'email\'] + br').after('<span class="error">' + json['error']['email'] + '</span>');
                    }

                    if (json['error']['telephone']) {
                        $('#payment-address input[name=\'telephone\'] + br').after('<span class="error">' + json['error']['telephone'] + '</span>');
                    }

                    if (json['error']['company_id']) {
                        $('#payment-address input[name=\'company_id\'] + br').after('<span class="error">' + json['error']['company_id'] + '</span>');
                    }

                    if (json['error']['tax_id']) {
                        $('#payment-address input[name=\'tax_id\'] + br').after('<span class="error">' + json['error']['tax_id'] + '</span>');
                    }

                    if (json['error']['address_1']) {
                        $('#payment-address input[name=\'address_1\'] + br').after('<span class="error">' + json['error']['address_1'] + '</span>');
                    }

                    if (json['error']['city']) {
                        $('#payment-address input[name=\'city\'] + br').after('<span class="error">' + json['error']['city'] + '</span>');
                    }

                    if (json['error']['postcode']) {
                        $('#payment-address input[name=\'postcode\'] + br').after('<span class="error">' + json['error']['postcode'] + '</span>');
                    }

                    if (json['error']['country']) {
                        $('#payment-address select[name=\'country_id\'] + br').after('<span class="error">' + json['error']['country'] + '</span>');
                    }

                    if (json['error']['zone']) {
                        $('#payment-address select[name=\'zone_id\'] + br').after('<span class="error">' + json['error']['zone'] + '</span>');
                    }
                } else {
<?php if ($shipping_required): ?>
                        var shipping_address = $('#payment-address input[name=\'shipping_address\']:checked').attr('value');

                        if (shipping_address) {
                            $.ajax({
                                url: 'index.php?route=checkout/shipping',
                                dataType: 'json',
                                success: function(json) {
                                    if (json['redirect']) {
                                        location = json['redirect'];
                                    }

                                    if (json['output']) {
                                        $('#shipping-method .checkout-content').html(json['output']);

                                        $('#payment-address .checkout-content').slideUp('slow');

                                        $('#shipping-method .checkout-content').slideDown('slow');

                                        $('#payment-address .checkout-heading a').remove();
                                        $('#shipping-address .checkout-heading a').remove();
                                        $('#shipping-method .checkout-heading a').remove();
                                        $('#payment-method .checkout-heading a').remove();

                                        $('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
                                        $('#shipping-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');

                                        $.ajax({
                                            url: 'index.php?route=checkout/guest/shipping',
                                            dataType: 'json',
                                            success: function(json) {
                                                if (json['redirect']) {
                                                    location = json['redirect'];
                                                }

                                                if (json['output']) {
                                                    $('#shipping-address .checkout-content').html(json['output']);
                                                }
                                            },
                                            error: function(xhr, ajaxOptions, thrownError) {
                                                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                            }
                                        });
                                    }
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                }
                            });
                        } else {
                            $.ajax({
                                url: 'index.php?route=checkout/guest/shipping',
                                dataType: 'json',
                                success: function(json) {
                                    if (json['redirect']) {
                                        location = json['redirect'];
                                    }

                                    if (json['output']) {
                                        $('#shipping-address .checkout-content').html(json['output']);

                                        $('#payment-address .checkout-content').slideUp('slow');

                                        $('#shipping-address .checkout-content').slideDown('slow');

                                        $('#payment-address .checkout-heading a').remove();
                                        $('#shipping-address .checkout-heading a').remove();
                                        $('#shipping-method .checkout-heading a').remove();
                                        $('#payment-method .checkout-heading a').remove();

                                        $('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
                                    }
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                }
                            });
                        }
<?php else: ?>
                        $.ajax({
                            url: 'index.php?route=checkout/pec_checkout/session_method',
                            dataType: 'html',
                            success: function() {
                                $.ajax({
                                    url: 'index.php?route=checkout/confirm',
                                    dataType: 'json',
                                    success: function(json) {
                                        if (json['redirect']) {
                                            location = json['redirect'];
                                        }

                                        if (json['output']) {
                                            $('#confirm .checkout-content').html(json['output']);

                                            $('#payment-address .checkout-content').slideUp('slow');

                                            $('#confirm .checkout-content').slideDown('slow');

                                            $('#payment-address .checkout-heading a').remove();
                                            $('#confirm .checkout-heading a').remove();

                                            $('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
                                        }
                                    },
                                    error: function(xhr, ajaxOptions, thrownError) {
                                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                    }
                                });
                            }
                        });
<?php endif ?>
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    // Payment Address
    $('#payment-address #button-address').live('click', function() {
        $.ajax({
            url: 'index.php?route=checkout/address/payment',
            type: 'post',
            data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'password\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-payment-address').attr('disabled', true);
                $('#button-payment-address').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
            },
            complete: function() {
                $('#button-payment-address').attr('disabled', false);
                $('.wait').remove();
            },
            success: function(json) {
                $('.warning, .error').remove();

                if (json['error']) {
                    if (json['error']['warning']) {
                        $('#payment-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                        $('.warning').fadeIn('slow');
                    }

                    if (json['error']['firstname']) {
                        $('#payment-address input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
                    }

                    if (json['error']['lastname']) {
                        $('#payment-address input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
                    }

                    if (json['error']['telephone']) {
                        $('#payment-address input[name=\'telephone\']').after('<span class="error">' + json['error']['telephone'] + '</span>');
                    }

                    if (json['error']['company_id']) {
                        $('#payment-address input[name=\'company_id\']').after('<span class="error">' + json['error']['company_id'] + '</span>');
                    }

                    if (json['error']['tax_id']) {
                        $('#payment-address input[name=\'tax_id\']').after('<span class="error">' + json['error']['tax_id'] + '</span>');
                    }

                    if (json['error']['address_1']) {
                        $('#payment-address input[name=\'address_1\']').after('<span class="error">' + json['error']['address_1'] + '</span>');
                    }

                    if (json['error']['city']) {
                        $('#payment-address input[name=\'city\']').after('<span class="error">' + json['error']['city'] + '</span>');
                    }

                    if (json['error']['postcode']) {
                        $('#payment-address input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
                    }

                    if (json['error']['country']) {
                        $('#payment-address select[name=\'country_id\']').after('<span class="error">' + json['error']['country'] + '</span>');
                    }

                    if (json['error']['zone']) {
                        $('#payment-address select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone'] + '</span>');
                    }
                } else {
                    $.ajax({
                        url: 'index.php?route=checkout/pec_checkout/session_method',
                        dataType: 'html',
                        success: function() {
<?php if ($shipping_required): ?>
                                $.ajax({
                                    url: 'index.php?route=checkout/address/shipping',
                                    dataType: 'json',
                                    success: function(json) {
                                        if (json['redirect']) {
                                            location = json['redirect'];
                                        }

                                        if (json['output']) {
                                            $('#shipping-address .checkout-content').html(json['output']);

                                            $('#payment-address .checkout-content').slideUp('slow');

                                            $('#shipping-address .checkout-content').slideDown('slow');

                                            $('#payment-address .checkout-heading a').remove();
                                            $('#shipping-address .checkout-heading a').remove();
                                            $('#shipping-method .checkout-heading a').remove();
                                            $('#payment-method .checkout-heading a').remove();

                                            $('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
                                        }
                                    },
                                    error: function(xhr, ajaxOptions, thrownError) {
                                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                    }
                                });
<?php else: ?>
                                $.ajax({
                                    url: 'index.php?route=checkout/pec_checkout/session_method',
                                    dataType: 'html',
                                    success: function() {
                                        $.ajax({
                                            url: 'index.php?route=checkout/confirm',
                                            dataType: 'json',
                                            success: function(json) {
                                                if (json['redirect']) {
                                                    location = json['redirect'];
                                                }

                                                if (json['output']) {
                                                    $('#confirm .checkout-content').html(json['output']);

                                                    $('#payment-address .checkout-content').slideUp('slow');

                                                    $('#confirm .checkout-content').slideDown('slow');

                                                    $('#payment-address .checkout-heading a').remove();
                                                    $('#confirm .checkout-heading a').remove();

                                                    $('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
                                                }
                                            },
                                            error: function(xhr, ajaxOptions, thrownError) {
                                                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                            }
                                        });
                                    }
                                });
<?php endif ?>
                        }
                    });

                    $.ajax({
                        url: 'index.php?route=checkout/address/payment',
                        dataType: 'json',
                        success: function(json) {
                            if (json['redirect']) {
                                location = json['redirect'];
                            }

                            if (json['output']) {
                                $('#payment-address .checkout-content').html(json['output']);
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    // Shipping Address
    $('#shipping-address #button-address').live('click', function() {
        $.ajax({
            url: 'index.php?route=checkout/address/shipping',
            type: 'post',
            data: $('#shipping-address input[type=\'text\'], #shipping-address input[type=\'password\'], #shipping-address input[type=\'checkbox\']:checked, #shipping-address input[type=\'radio\']:checked, #shipping-address select'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-shipping-address').attr('disabled', true);
                $('#button-shipping-address').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
            },
            complete: function() {
                $('#button-shipping-address').attr('disabled', false);
                $('.wait').remove();
            },
            success: function(json) {
                $('.warning, .error').remove();

                if (json['error']) {
                    if (json['error']['warning']) {
                        $('#shipping-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                        $('.warning').fadeIn('slow');
                    }

                    if (json['error']['firstname']) {
                        $('#shipping-address input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
                    }

                    if (json['error']['lastname']) {
                        $('#shipping-address input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
                    }

                    if (json['error']['email']) {
                        $('#shipping-address input[name=\'email\']').after('<span class="error">' + json['error']['email'] + '</span>');
                    }

                    if (json['error']['telephone']) {
                        $('#shipping-address input[name=\'telephone\']').after('<span class="error">' + json['error']['telephone'] + '</span>');
                    }

                    if (json['error']['address_1']) {
                        $('#shipping-address input[name=\'address_1\']').after('<span class="error">' + json['error']['address_1'] + '</span>');
                    }

                    if (json['error']['city']) {
                        $('#shipping-address input[name=\'city\']').after('<span class="error">' + json['error']['city'] + '</span>');
                    }

                    if (json['error']['postcode']) {
                        $('#shipping-address input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
                    }

                    if (json['error']['country']) {
                        $('#shipping-address select[name=\'country_id\']').after('<span class="error">' + json['error']['country'] + '</span>');
                    }

                    if (json['error']['zone']) {
                        $('#shipping-address select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone'] + '</span>');
                    }
                } else {
                    $.ajax({
                        url: 'index.php?route=checkout/pec_checkout/session_method',
                        dataType: 'html',
                        success: function() {
                            $.ajax({
                                url: 'index.php?route=checkout/shipping',
                                dataType: 'json',
                                success: function(json) {
                                    if (json['redirect']) {
                                        location = json['redirect'];
                                    }

                                    if (json['output']) {
                                        $('#shipping-method .checkout-content').html(json['output']);

                                        $('#shipping-address .checkout-content').slideUp('slow');

                                        $('#shipping-method .checkout-content').slideDown('slow');

                                        $('#shipping-address .checkout-heading a').remove();
                                        $('#shipping-method .checkout-heading a').remove();
                                        $('#payment-method .checkout-heading a').remove();

                                        $('#shipping-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');

                                        $.ajax({
                                            url: 'index.php?route=checkout/address/shipping',
                                            dataType: 'json',
                                            success: function(json) {
                                                if (json['redirect']) {
                                                    location = json['redirect'];
                                                }

                                                if (json['output']) {
                                                    $('#shipping-address .checkout-content').html(json['output']);
                                                }
                                            },
                                            error: function(xhr, ajaxOptions, thrownError) {
                                                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                            }
                                        });
                                    }
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                }
                            });
                        }
                    });
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    // Guest Shipping
    $('#button-guest-shipping').live('click', function() {
        $.ajax({
            url: 'index.php?route=checkout/guest/shipping',
            type: 'post',
            data: $('#shipping-address input[type=\'text\'], #shipping-address select'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-guest-shipping').attr('disabled', true);
                $('#button-guest-shipping').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
            },
            complete: function() {
                $('#button-guest-shipping').attr('disabled', false);
                $('.wait').remove();
            },
            success: function(json) {
                $('.error').remove();

                if (json['error']) {
                    if (json['error']['firstname']) {
                        $('#shipping-address input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
                    }

                    if (json['error']['lastname']) {
                        $('#shipping-address input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
                    }

                    if (json['error']['address_1']) {
                        $('#shipping-address input[name=\'address_1\']').after('<span class="error">' + json['error']['address_1'] + '</span>');
                    }

                    if (json['error']['city']) {
                        $('#shipping-address input[name=\'city\']').after('<span class="error">' + json['error']['city'] + '</span>');
                    }

                    if (json['error']['postcode']) {
                        $('#shipping-address input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
                    }

                    if (json['error']['country']) {
                        $('#shipping-address select[name=\'country_id\']').after('<span class="error">' + json['error']['country'] + '</span>');
                    }

                    if (json['error']['zone']) {
                        $('#shipping-address select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone'] + '</span>');
                    }
                } else {
                    $.ajax({
                        url: 'index.php?route=checkout/pec_checkout/session_method',
                        dataType: 'html',
                        success: function() {
                            $.ajax({
                                url: 'index.php?route=checkout/shipping',
                                dataType: 'json',
                                success: function(json) {
                                    if (json['redirect']) {
                                        location = json['redirect'];
                                    }

                                    if (json['output']) {
                                        $('#shipping-method .checkout-content').html(json['output']);

                                        $('#shipping-address .checkout-content').slideUp('slow');

                                        $('#shipping-method .checkout-content').slideDown('slow');

                                        $('#shipping-address .checkout-heading a').remove();
                                        $('#shipping-method .checkout-heading a').remove();
                                        $('#payment-method .checkout-heading a').remove();

                                        $('#shipping-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
                                    }
                                }
                            });
                        }
                    });
                }
            }
        });
    });

    $('#button-shipping').live('click', function() {
        $.ajax({
            url: 'index.php?route=checkout/shipping',
            type: 'post',
            data: $('#shipping-method input[type=\'radio\']:checked, #shipping-method textarea'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-shipping-method').attr('disabled', true);
                $('#button-shipping-method').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
            },
            complete: function() {
                $('#button-shipping-method').attr('disabled', false);
                $('.wait').remove();
            },
            success: function(json) {
                $('.warning, .error').remove();
                if (json['error']) {
                    if (json['error']['warning']) {
                        $('#shipping-method .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                        $('.warning').fadeIn('slow');
                    }
                } else {
                    $.ajax({
                        url: 'index.php?route=checkout/pec_checkout/session_method',
                        dataType: 'html',
                        success: function() {
                            $.ajax({
                                url: 'index.php?route=checkout/confirm',
                                dataType: 'json',
                                success: function(json) {
                                    if (json['redirect']) {
                                        location = json['redirect'];
                                    }

                                    if (json['output']) {
                                        $('#confirm .checkout-content').html(json['output']);

                                        $('#shipping-method .checkout-content').slideUp('slow');

                                        $('#confirm .checkout-content').slideDown('slow');

                                        $('#shipping-method .checkout-heading a').remove();

                                        $('#shipping-method .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
                                    }
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    alert(thrownError);
                                }
                            });
                        }
                    });
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });
    //--></script>
<?php echo $footer; ?>