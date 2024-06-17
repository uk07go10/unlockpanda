<?php if ($this->config->get('paypal_express_status')) { ?>
    <div id="module_paypal_express" class="box">
        <div class="box-heading"><?php echo $heading_title; ?></div>
        <div class="box-content">
            <a id="pec"><img src="<?php echo $btn_pec; ?>" alt="<?php echo $heading_title; ?>" /></a>
        </div>
    </div>
    <script type="text/javascript"><!--
        $('#pec').bind('click', function() {
            $.ajax({
                type: 'POST',
                url: '<?php echo $actionSetExpressCheckout; ?>',
                dataType: 'json',
                beforeSend: function() {
                    $('#pec').after("<div class=\"attention\"><img src=\"catalog/view/theme/default/image/loading.gif\" alt=\"\" /> <?php echo $text_wait; ?></div>");
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
        //--></script>
<?php } ?>