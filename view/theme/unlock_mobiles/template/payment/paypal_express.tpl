<div id="payment"></div>
<div class="buttons">
    <div class="right"><a id="button-confirm" class="button"><span><?php echo $button_confirm; ?></span></a></div>
</div>
<script type="text/javascript"><!--
    $('#button-confirm').bind('click', function() {
        $.ajax({
            type: 'POST',
            url: '<?php echo $actionDoExpressCheckoutPayment ?>',
            dataType: 'json',
            beforeSend: function() {
                $('#button-confirm').attr('disabled', true);
                $('.checkout').hide();
                $('.checkout').after("<div class=\"attention\"><img src=\"catalog/view/theme/default/image/loading.gif\" alt=\"\" /> <?php echo $text_payment_processing ?></div>");
            },
            success: function(json) {
                if (json['error']) {
                    alert(json['error']);
                    $('.checkout').show();
                    $('#button-confirm').attr('disabled', false);
                }

                $('.attention').remove();

                if (json['success']) {
                    location = json['success'];
                }
            }
        });
    });
<?php if ($skip_confirm): ?>
        $('#button-confirm').trigger('click');
    <?php
endif;
if (isset($PECheckout)):
    ?>
            //Force PayPal Express Checkout
            $(document).ready(function() {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo $actionSetExpressCheckout ?>',
                    dataType: 'json',
                    beforeSend: function() {
                        $('#confirm table').hide();
                        $('#confirm .buttons').hide();
                        $('#confirm').after("<div class=\"attention\"><img src=\"catalog/view/theme/default/image/loading.gif\" alt=\"\" /> <?php echo $text_wait; ?></div>");
                    },
                    success: function(json) {
                        if (json['error']) {
                            alert(json['error']);
                        }

                        $('.attention').remove();

                        if (json['success']) {
                            location = json['success'];
                        }
                    }
                });
            });
<?php endif ?>
    //--></script>