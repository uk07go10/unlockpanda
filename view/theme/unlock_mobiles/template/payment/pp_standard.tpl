<?php if ($testmode) { ?>
<div class="warning"><?php echo $text_testmode; ?></div>
<?php } ?>
<form action="<?php echo $action; ?>" method="post" id="payment">
  <input type="hidden" name="cmd" value="_cart" />
  <input type="hidden" name="upload" value="1" />
  <input type="hidden" name="business" value="<?php echo $business; ?>" />
  <?php $i = 1; ?>
  <?php foreach ($products as $product) { ?>
        <input type="hidden" name="item_name_<?php echo $i; ?>" value="<?php echo $product['name'] . ' (carrier: ' . $product['carrier'] . ')'; ?>" />
        <input type="hidden" name="item_number_<?php echo $i; ?>" value="<?php echo $product['imei']; ?>" />
        <input type="hidden" name="amount_<?php echo $i; ?>" value="<?php echo $product['price']; ?>" />
        <input type="hidden" name="quantity_<?php echo $i; ?>" value="<?php echo $product['quantity']; ?>" />

        <?php $i++; ?>
  <?php } ?>
  <input type="hidden" name="on0_1" value="Language" />
  <?php if($lc == 'es') { ?>
	<input type="hidden" name="os0_1" value="Spanish" />
  <?php } else { ?>
	<input type="hidden" name="os0_1" value="English" />
  <?php } ?>
  <?php if ($discount_amount_cart) { ?>
  <input type="hidden" name="discount_amount_cart" value="<?php echo $discount_amount_cart; ?>" />
  <?php } ?>
  <input type="hidden" name="currency_code" value="<?php echo $currency_code; ?>" />
  <input type="hidden" name="first_name" value="<?php echo $first_name; ?>" />
  <input type="hidden" name="last_name" value="<?php echo $last_name; ?>" />
<!--  <input type="hidden" name="address1" value="<?php //echo $address1; ?>" />
  <input type="hidden" name="address2" value="<?php //echo $address2; ?>" />
  <input type="hidden" name="city" value="<?php //echo $city; ?>" />
  <input type="hidden" name="zip" value="<?php // echo $zip; ?>" />-->
  <input type="hidden" name="country" value="<?php echo $country; ?>" />
  <input type="hidden" name="address_override" value="0" />
  <input type="hidden" name="email" value="<?php echo $email; ?>" />
<!--  <input type="hidden" name="invoice" value="<?php // echo $invoice; ?>" />-->
  <input type="hidden" name="lc" value="<?php echo $lc; ?>" />
  <input type="hidden" name="rm" value="2" />
  <input type="hidden" name="no_shipping" value="1" />
  <input type="hidden" name="no_note" value="1" />
  <input type="hidden" name="charset" value="utf-8" />
  <input type="hidden" name="return" value="<?php echo $return; ?>" />
  <input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>" />
  <input type="hidden" name="cancel_return" value="<?php echo $cancel_return; ?>" />
  <input type="hidden" name="paymentaction" value="<?php echo $paymentaction; ?>" />
	<input type="hidden" name="custom" value="" />
</form>
<!--<div class="buttons">-->
	
    <a id="button-confirm" onclick="validateTerms();" >
		<img src="<?php echo get_image_dir() . 'data/paypal-button.png'; ?>" alt="Pay with PayPal" />
	</a>
                            
<!--  <div class="right"><a id="button-confirm" class="button" onclick="$('#payment').submit();"><span><?php echo $button_confirm; ?></span></a></div>-->
<!--</div>-->
<script type="text/javascript"><!--

	function handleNewsletter() {
		if (newsletter.checked && !window.newsletterSubmitted) {
			$.ajax({
				type: 'post',
				url: 'index.php?route=module/newslettersubscribe/subscribe',
				dataType: 'html',
				data: $("#subscribe").serialize(),
				success: function() {
					window.newsletterSubmitted = true;
				},
				always: function() {
					window.newsletterSubmitted = true;
				}
			});

		}
	}

	var clicked = false;
	function process() {

		$("#loading").show();

		var orderSubmitted = false;
		if(!clicked && !window.dataInFlight) {
			handleNewsletter();
			clicked = true;
			window.dataInFlight = true;
			$.ajax({
				type: 'post',
				url: 'index.php?route=payment/ppstandard/createunpaid',
				success: function(data) {
					data = $.parseJSON(data);
					console.log(data);
					if(!data.result) {
						alert("Problem encountered! If the problem persists please contact admin.");
						location.reload();
						return;
					}
					$("input[name='custom']").attr("value", data["id"]);
					orderSubmitted = true;
					submit();
				},
				always: function() {
					orderSubmitted = true;
				}
			});
		}


		function submit() {
			if((newsletter.checked && !window.newsletterSubmitted) || !orderSubmitted) {
				setTimeout(submit, 50);
				return;
			}

			$("#payment").submit();
		}
	}

function validateTerms() {
	process();
}
//--></script>  