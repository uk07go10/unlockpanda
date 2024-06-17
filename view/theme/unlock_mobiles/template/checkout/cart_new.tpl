<?php echo $header; ?>
    <div class="container"><?php echo $column_left; ?><?php echo $column_right; ?>
        <div id="content"><?php echo $content_top; ?>
            <div id="content_page">
                <div class="content_top" >
                    <h1><?php echo $heading_title; ?></h1>
                    <?php if ($attention) { ?>
                        <div class="attention"><?php echo $attention; ?></div>
                    <?php } ?>
                    <?php if ($success) { ?>
                        <div class="success"><?php echo $success; ?></div>
                    <?php } ?>
                    <?php if ($error_warning) { ?>
                        <div class="warning"><?php echo $error_warning; ?></div>
                    <?php } ?>
                    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="basket">
                        <div class="cart-info">
                            <table>
                                <thead>
                                <tr>
                                    <td class="remove"><?php echo $column_action; ?></td>
                                    <td class="image"><?php echo $column_image; ?></td>
                                    <td class="name"><?php echo $column_name; ?></td>
                                    <td class="quantity"><?php echo $column_model; ?></td>
                                    <td class="image"><?php echo $column_price; ?></td>
                                    <td class="total"><?php echo $column_total; ?></td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($products as $product) { ?>
                                    <tr>
                                        <td class="remove"><a href="<?php echo $product['remove']; ?>"  class="button" style="color: #CD2626; text-decoration: underline"><?php echo $column_remove; ?></td>
                                        <td class="image" style="vertical-align: middle;"><?php if ($product['thumb']) { ?>
                                                <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
                                            <?php } ?></td>
                                        <td class="name"><a href="<?php echo $product['href']; ?>"><b><?php echo $product['name']; ?></b></a>
                                            <?php if (!$product['stock']) { ?>
                                                <span class="stock">***</span>
                                            <?php } ?>
                                            <div>
                                                <span class="desc float_left" style="max-width: 490px;"><?php echo $product['description']; ?></span>
                                                <div class="clear"></div>
                                                <span class="desc float_left">IMEI:&nbsp;<?php echo $product['imei'] ?></span>
                                                <div class="clear"></div>
                                                <span class="desc float_left">Carrier:&nbsp;<?php echo $product['carrier'] ?></span>
                                            </div>

                                        </td>
                                        <td class="quantity"><?php echo $product['category']; ?></td>
                                        <td class="price"><?php echo $product['price']; ?></td>
                                        <td class="total"><?php echo $product['total']; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php //foreach ($vouchers as $voucher) { ?>
                                <!--                        <tr>
                        <td class="remove"><input type="checkbox" name="voucher[]" value="<?php // echo $voucher['key']; ?>" /></td>
                        <td class="image"></td>
                        <td class="name"><?php // echo $voucher['description']; ?></td>
                        <td class="model"></td>
                        <td class="quantity">1</td>
                        <td class="price"><?php // echo $voucher['amount']; ?></td>
                        <td class="total"><?php // echo $voucher['amount']; ?></td>
                        </tr>-->
                                <?php //} ?>
                                </tbody>
                            </table>
                            <div class="estshipping"><?php echo $text_deltime; ?><?php echo $product['delivery_time']; ?> </div>
                        </div>
                    </form>
                    <div class="cart-module">
                        <?php foreach ($modules as $module) { ?>
                            <?php echo $module; ?>
                        <?php } ?>
                    </div>
                    <div class="cart-total">
                        <?php //Get Cart Important Notes Information Page
                        $query = $this->db->query("SELECT information_id, description FROM " . DB_PREFIX . "information_description WHERE information_id = '27' AND language_id = " . (int)$this->config->get('config_language_id'));
                        if ($query->num_rows) {
                            echo  html_entity_decode($query->row['description'], ENT_QUOTES, 'UTF-8');
                        }
                        ?>
                        <table style="">
                            <?php foreach ($totals as $total) { ?>
                                <tr>
                                    <td class="right"><b class="float_left iprice"><?php echo $total['title']; ?>:</b> <span class="float_right iprice"><?php echo $total['text']; ?></span></td>
                                </tr>
                            <?php } ?>
                            <tr id="paypal_payment">
                                <td colspan="2">
                                    <input type="hidden" name="agree" value="1" id="agree" />
                                        <span class="cartchecks" style="margin-left: 27px">
                                                <?php echo $text_agree; ?>
                                            <a alt="Terms & Conditions" href="index.php?route=information/information/info&amp;information_id=5" class="fancybox" style="font-size:14px!important;">
                                                <b><?php echo $text_terms; ?></b>
                                            </a>
                                        </span>
                                    <br /><br />
                                    <input type="checkbox" name="newsletter" value="1" id="newsletter" checked />
                                        <span class="cartchecks">
                                            <?php echo $text_newsletter; ?></a>
                                        </span>
                                    <br /><br />
                                    <?php if($delivery_time_notice) { ?>
                                        <input type="checkbox" name="delivery_time" value="1" id="delivery_time" checked>
                                        <span class="cartchecks"><?php echo $delivery_time_notice ?></span>
                                    <?php } else { ?>
                                        <input type="hidden" name="delivery_time" value="1" id="delivery_time">
                                    <?php } ?>
                                    <span class="cartchecks" style="margin: 0 0 0 25px; font-weight: bold;"><?php echo $text_currency ?></span>
                                </td>
                            </tr>
                            <script>
                                window.dataInFlight = false;
                                window.newsletterSubmitted = false;

                                function getFp() {
                                    var value = Cookies.get("fp");
                                    if(typeof(value) == "undefined") {
                                        return "false";
                                    }
                                    return value;
                                }

                                try {
                                    new Fp2().get(function (result) {
                                        console.log(result);
                                        Cookies.set("fp", result);
                                    });
                                }
                                catch (e) {
                                    Bugsnag.notifyException(e);
                                }
                            </script>
                            <tr id="paypal_payment">
                                <td colspan="2" class="last">
                                    <span class="payoption"><?php echo $text_payoption; ?></span>
                                    <?php //echo $dalpay_checkout; ?><!--<br /><b>Or</b><br />-->
                                    <?php echo $ppstandard; ?>
                                </td>
                            </tr>
                            <?php if($stripe_enabled): ?>
                                <tr>
                                    <td colspan="2" class="last">
                                        or
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="last">
                                        <?php echo $stripe ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td colspan="2" class="last">
                                    <p id="loading" style="display: none;">
                                        Loading..
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div style="display:none;"><?php echo $newslettersubscribe; ?></div>
                    <div class="buttons">
                        <!--                    <div class="left"><a onclick="$('#basket').submit();" class="button"><span class="round_corners_small">--><?php //echo $button_update; ?><!--</span></a></div>-->
                        <!--                <div class="right"><a href="<?php echo $checkout; ?>" class="button"><span><?php echo $button_checkout; ?></span></a></div>-->
                        <!--                <div class="right">-->
                        <!--                    <a onclick="validateTerms();" href="--><?php //// echo $continue; ?><!--#" class="button "><span class="round_corners_small">--><?php //echo $button_shopping; ?><!--</span></a>-->
                        <!--                </div>-->
                    </div>
                    <?php echo $content_bottom; ?>

                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript"><!--
        $('.cart-module .cart-heading').bind('click', function() {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
            } else {
                $(this).addClass('active');
            }

            $(this).parent().find('.cart-content').slideToggle('slow');
        });
        $('#authorize_payment').hide();
        /*
         $('#alt_payment').toggle(function() {
         $('#paypal_payment').hide();
         $('#authorize_payment').show();
         },
         function() {
         $('#paypal_payment').show();
         $('#authorize_payment').hide();
         });
         */
        //-->

    </script>
<?php echo $footer; ?>