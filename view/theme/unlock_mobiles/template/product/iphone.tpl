<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
        <!--<div class="top_content">
            <div class="content_top">-->
                    <div class="description-iphone">
                         <h1><?php echo $heading_title; ?></h1>
                         <div class="float_left" style="text-align: right;width: 400px;">
                                <?php if ($thumb) { ?>
                                    <div class="image">
										<img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" />
										<input class="iphone-imei" type="text" id="prod_imei" name="imei" placeholder="Press *#06# to get IMEI" onfocus="this.value = ''" />
										<select name="carrier" id="default-usage-select" >
                                            <?php foreach ($manufacturers as $manufacturer) { ?>
                                                <option value="<?php echo $manufacturer['manufacturer_id'] ?>" <?php if($manufacturer['manufacturer_id'] == 21){ ?>selected<?php } ?>><?php echo $manufacturer['name'] ?></option>
                                            <?php } ?>
                                        </select>
										<div class="model">
										<select name="model" id="model" >
												<option value="iPhone 4s">iPhone 4s</option>
												<option value="iPhone 5">iPhone 5</option>
												<option value="iPhone 5s">iPhone 5s</option>
												<option value="iPhone 5c">iPhone 5c</option>
                                        </select>
										</div>
									</div>
                                <?php } ?>
                                    <div style="margin-top: 0px; clear: both; text-align: right">
                                        <span class="price">Total: <?php echo $price ?> USD</span><input type="submit" name="add_to_cart" onclick="addiPhoneToCart($('#default-usage-select').val(), '<?php echo $category_info['category_id'] ?>', '<?php echo $product_id ?>', $('#prod_imei').val(), $('#model').val() ); return false;" value="CHECKOUT" />
                                    </div>
                                    <div id="errors_p" style="margin-bottom: 10px;errors"></div>
                         </div>
                         <div class="float_right iphone_left">
									 <div style="overflow: hidden; text-align: left">
										<?php if ($description) { ?>
											<?php echo $description; ?>
										<?php } ?>
									</div>
                         </div>
                         
                         <div class="clear"></div>
                    </div>
            <!--</div>
        </div>-->
</div>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/unlock_mobiles/stylesheet/select-theme-default.css" media="screen" />
<script type="text/javascript" src="catalog/view/javascript/select.min.js"></script>
<script type="text/javascript"><!--
if ($.browser.msie && $.browser.version == 6) {
	$('.date, .datetime, .time').bgIframe();
}
//--></script> 
<script type="text/javascript">
		$(document).ready(function() {
				Select.init({selector: '#default-usage-select'})
				Select.init({selector: '#model'})
		});		
</script>
<?php echo $footer; ?>