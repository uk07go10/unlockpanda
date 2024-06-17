<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
        <div class="top_content">
            <div class="content_top">
                    <div class="description">
                         <h1><?php echo $heading_title; ?></h1>
                         <div class="float_left" style="width: 40%; text-align: center">
                                <?php if ($thumb) { ?>
                                    <div class="image"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" /></div>
                                <?php } ?>
                                    
                                <p style="font-weight: bold">Delivery Time: <?php echo $delivery_time ?></p>
                                
                                <div style="min-height: 100px; max-height: 150px; overflow: hidden; background-color: #86729f; color: #fff; padding: 15px; text-align: left">
                                    <?php if ($description) { ?>
                                        <?php echo $description; ?>
                                    <?php } ?>
                                </div>
                                
                         </div>
                         <div class="float_right product_left round_corners" style="margin-right: 20px;  ">
                             <div class="content_top">
                                 
                                    <h1>BEGIN UNLOCKING HERE</h1>
                                    <div  style="padding: 15px;">
                                        <h2 class="float_left">1.</h2> 
                                        <div class="float_left" style="width: 80%" >
                                            <select name="carrier" id="default-usage-select" >
                                            <option value="">Locked Carrier For Your Phone</option>
                                            <?php foreach ($manufacturers as $manufacturer) { ?>
                                                <option value="<?php echo $manufacturer['manufacturer_id'] ?>"><?php echo $manufacturer['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div style="padding: 15px; clear: both; text-align: left">
                                        <h2 class="float_left">2.</h2> 
                                        <input style="margin-left: 30px;border: 1px solid #4B2E71; width: 230px; border-radius: 5px 5px 5px 5px; padding: 7px;"  class="round_corners_small" type="text" id="prod_imei" name="imei" value="ENTER PHONE IMEI - DIAL *#60#" onfocus="this.value = ''" />
                                    </div>
                                    
                                    <div style="margin-left: 30px; clear: both; text-align: left">
                                        <h4 class="float_left">Our Price:</h4> <span class="price"><?php echo $price ?></span>
                                    </div>
                                    <div style="margin-top: 20px;">
                                        <input class="round_corners" type="submit" name="add_to_cart" onclick="addToCart($('#default-usage-select').val(), '<?php echo $category_info['category_id'] ?>', '<?php echo $product_id ?>', $('#prod_imei').val() ); return false;" value="UNLOCK NOW" />
                                    </div>
                                    <div id="errors_p" style="margin-bottom: 10px;errors"></div>

                             </div>
                         </div>
                         
                         <div class="clear"></div>
                    </div>
            </div>
        </div>
        <div id="content_page">
                <div class="content_top" >
                    <?php if ($products) { ?>
                            <div class="box">
                                        <div class="box-heading"><?php echo 'Related Products'; ?></div>
                                        <div class="box-content">
                                            <div class="box-product">
                                                <?php $i = 1; ?>
                                                <?php foreach ($products as $product) { ?>
                                                        <div class="product_container <?php if($i % 3 == 0){ echo "last_box_item"; }  ?>">
                                                            <div class="inner">
                                                                    <h2><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h2>
                                                                    <?php if ($product['thumb']) { ?>
                                                                    <div class="image float_left"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
                                                                    <?php } ?>
                                                                    <div class="float_left" style="width: 160px;margin-left: 15px;" >
                                                                        <div style="height: 110px; overflow: hidden; font-size: 11px; line-height: 17px;"> <?php echo $product['description']; ?></div>
                                                                        <div class="clear"></div>
                                                                        <div class="cart float_right">
                                                                            <a href="<?php echo $product['href']; ?>" class="button"><span class="round_corners_small"><?php echo 'Unlock Now'; ?></span></a>
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                        </div>
                                                        <?php $i++; ?>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                            </div>
                            <div class="clear"></div>
                    <?php } ?>

                <?php echo $content_bottom; ?>
                </div>
        </div>
</div>

<script type="text/javascript"><!--
$('#button-cart').bind('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/update',
		type: 'post',
		data: $('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea'),
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, information, .error').remove();
			
			if (json['error']) {
				if (json['error']['warning']) {
					$('#notification').html('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
				
					$('.warning').fadeIn('slow');
				}
				
				for (i in json['error']) {
					$('#option-' + i).after('<span class="error">' + json['error'][i] + '</span>');
				}
			}	 
						
			if (json['success']) {
				$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
					
				$('.success').fadeIn('slow');
					
				$('#cart_total').html(json['total']);
				
				$('html, body').animate({ scrollTop: 0 }, 'slow'); 
			}	
		}
	});
});
//--></script>

<script type="text/javascript"><!--
if ($.browser.msie && $.browser.version == 6) {
	$('.date, .datetime, .time').bgIframe();
}
//--></script> 
<!--<script type="text/javascript">
        //<![CDATA[
                $("#default-usage-select").selectbox();
        //]]>
</script>-->
<?php echo $footer; ?>