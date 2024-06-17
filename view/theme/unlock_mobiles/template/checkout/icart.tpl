<?php echo $header; ?>
<style>
	.cart-total{
	border-top: none !important;
}
</style>
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
                        <td class="remove"><?php echo $column_remove; ?></td>
                        <td class="image"><?php echo $column_image; ?></td>
                        <td class="name"><?php echo $column_name; ?></td>
                        <td class="price"><?php echo $column_price; ?></td>
                        <td class="total"><?php echo $column_total; ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product) { ?>
                        <tr>
                        <td class="remove"><input type="checkbox" name="remove[<?php echo $product['key'] ?>]" value="<?php echo $product['key']. '__' . $product['imei'] . '__' . $product['carrier_id'] ; ?>" /></td>
                        <td class="image"><?php if ($product['thumb']) { ?>
                            <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
                            <?php } ?></td>
                        <td class="name"><a href="<?php echo $product['href']; ?>"><b><?php echo $product['name']; ?></b></a>
                            <?php if (!$product['stock']) { ?>
                            <span class="stock">***</span>
                            <?php } ?>
                            <div>
                                <span class="desc float_left">IMEI:&nbsp;<?php echo $product['imei'] ?></span>
                                <div class="clear"></div>
                                <span class="desc float_left">Carrier:&nbsp;<?php echo $product['carrier'] ?></span>
                            </div>
                        </td>
                        <td class="price"><?php echo $product['price']; ?></td>
                        <td class="total"><?php echo $product['total']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    </table>
                </div>
                </form>
				<div class="buttons">
                    <div class="left"><a onclick="$('#basket').submit();" class="button"><span class="round_corners_small"><?php echo $button_update; ?></span></a></div>
                </div>
				<div style="border-top:1px solid #DDDDDD;overflow: auto;">
						<div id="shipping-address">
						<div class="iphoneshipping"><b>Shipping Cost:</b>Worldwide FREE Shipping - <?php echo $delivery_time; ?> </div>	
						  <div class="checkout-heading">Shipping Address:</div>
						  <div class="checkout-content"></div>
						</div>
						<div class="cart-total">
							<table style="">
							<?php foreach ($totals as $total) { ?>
								<tr>
									<td class="right"><b class="float_left iprice"><?php echo $total['title']; ?>:</b> <span class="float_right iprice"><?php echo $total['text']; ?></span></td>
								</tr>
							<?php } ?>
								<tr id="paypal_payment">
									<td colspan="2">
										<input type="checkbox" name="newsletter" value="1" id="newsletter" checked /><span class="cartchecks">I want to receive order updates, discounts and newsletter.</a></span><br /><br />
									   <input type="checkbox" name="agree" value="1" id="agree" /><span class="cartchecks">I have read and agree to the <a alt="Terms & Conditions" href="index.php?route=information/information/info&amp;information_id=5" class="fancybox" style="font-size:14px!important;"><b>Terms & Conditions</b></a></span>
									</td>
								</tr>
								<tr id="paypal_payment">
									<td colspan="2" class="last">
									<span class="payoption">Payment Options:</span>
										<?php echo $ppstandard; ?>
									</td>
								</tr>
							</table>
						</div>
				</div>
                
                <div style="display:none;"><?php echo $newslettersubscribe; ?></div>
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

$(document).ready(function() {
//Change paypal Cancel Url
$('input[name=cancel_return]').val('https://www.unlockriver.com/index.php?route=checkout/icart');
// Shipping Address

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
									}
});
});
</script> 
<?php echo $footer; ?>