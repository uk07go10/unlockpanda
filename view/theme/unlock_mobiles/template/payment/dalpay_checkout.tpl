<form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" id="checkoutfrm">
  <input type="hidden" name="mer_id" value="<?php echo $mer_id; ?>" />
  <input type="hidden" name="pageid" value="<?php echo $pageid; ?>" />
  <input type="hidden" name="next_phase" value="paydata" />
  <input type="hidden" name="pay_type" value="Visa" />
  <input type="hidden" name="langcode" value="<?php echo $language; ?>" />
  <input type="hidden" name="valuta_code" value="<?php echo $currency_code; ?>" /> 
  <input type="hidden" name="cust_name" value="<?php echo $cust_name; ?>" />
  <input type="hidden" name="cust_country_code" value="<?php echo $cust_country_code; ?>" />
  <input type="hidden" name="cust_email" value="<?php echo $cust_email; ?>" />
  <input type="hidden" name="cust_phone" value="<?php echo $cust_phone; ?>" />
  <?php $i = 1; ?>
  <?php $sub_total = 0; ?>
  <?php foreach ($products as $product) { ?>
  <input type="hidden" name="item<?php echo $i; ?>_desc" value="<?php echo $product['name']; ?>" />
  <input type="hidden" name="item<?php echo $i; ?>_price" value="<?php echo $product['price']; ?>" />
  <input type="hidden" name="item<?php echo $i; ?>_qty" value="<?php echo $product['quantity']; ?>" />
  <?php $sub_total += ($product['price'] * $product['quantity']); ?>
  <?php $i++; ?>
  <?php } ?>
  <?php if ($this->cart->hasShipping()) { ?>
  <input type="hidden" name="item<?php echo $i; ?>_desc" value="<?php echo $shipping_method; ?>" />
  <input type="hidden" name="item<?php echo $i; ?>_price" value="<?php echo $shipping_cost; ?>" />
  <input type="hidden" name="item<?php echo $i; ?>_qty" value="1" />
  <?php $sub_total += $shipping_cost; ?>
  <?php } ?>
  <?php $price_difference = $sub_total - $total; ?>
  <?php $price_difference = round($price_difference, 2);  ?>
  <?php if ($price_difference > 0) { ?>
  <input type="hidden" name="sales_discount_amount" value="<?php echo $price_difference; ?>" />
  <?php } else if ($price_difference < 0) { ?>
  <input type="hidden" name="item<?php echo $i; ?>_desc" value="Surcharge" />
  <input type="hidden" name="item<?php echo $i; ?>_price" value="<?php echo -$price_difference ?>" />
  <input type="hidden" name="item<?php echo $i; ?>_qty" value="1" />
  <?php $i++; ?> 
  <?php } ?>
  <input type="hidden" name="user1" value="<?php echo $user1; ?>" />
  <input type="hidden" name="user2" value="<?php echo $user2; ?>" />
  <input type="hidden" name="user3" value="<?php echo $user3; ?>" />
  <input type="hidden" name="user4" value="<?php echo $user4; ?>" />
</form>
<!--<div class="buttons">
  <table>
    <tr>
      <td align="left"><a onclick="location = '<?php echo str_replace('&', '&amp;', $back); ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
      <td align="right"><a onclick="$('#checkoutfrm').submit();" class="button"><span><?php echo $button_confirm; ?></span></a></td>
    </tr>
  </table>
</div>-->
 <a id="button-confirm" onclick="validateDalpayTerms();" title="Checkout with Dalpay"><img src="<?php echo HTTPS_IMAGE . 'data/dalpay_checkout.png'; ?>" alt="Checkout with Dalpay" /></a>
<script type="text/javascript"><!--
function validateDalpayTerms(){
	terms = document.getElementById('agree');
	newsletter = document.getElementById('newsletter');
	if(!terms.checked){
		alert("Please accept the Terms");
		terms.focus();
		return false;
	}else if(newsletter.checked){
		$.ajax({
			type: 'post',
			url: 'index.php?route=module/newslettersubscribe/subscribe',
			dataType: 'html',
            data:$("#subscribe").serialize(),
			success: function (html) {
				//eval(html);
				$('#checkoutfrm').submit();
			}}); 
		
	} else {
		$('#checkoutfrm').submit();
	}
}
//--></script>  