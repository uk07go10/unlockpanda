<?php echo $header; ?>
<?php echo $column_left; ?>
<?php // echo $column_right; ?>

<div id="content">
    <?php // echo $content_top; ?>
<!--<h1 style="display: none;"><?php echo $heading_title; ?></h1>-->
    <div class="top_content">
        <div class="content_top">
            <?php echo $content_top; ?>
<!--             <img src="<?php echo $this->model_tool_image->resize('data/banner.png', 882, 380) ?>" alt="Banner"/>-->
        </div>
    </div>
    <div id="content_page">
        <div class="content_top">

                <h1 style="color: #0A98CA; margin-bottom: 3px;"><?php echo $text_unlock; ?></h1>  
				<div class="float_left" style="width: 60%">
					<h2 style="color: #767676 ; font-size: 15px; margin-bottom: 25px;"><?php echo $text_software; ?></h2>
					<div class="clear"></div>
					<div class="float_left" style="width: 29%;">
						<div class="float_right"><h1 style="color: #0A98CA;font-weight: bold;font-size: 42px">1</h1></div>
						<div class="float_right"><img src="<?php echo get_image_dir() . 'data/phones12.png' ?>" width="110" height="83" alt="" /></div>
						<div class="float_left"><p style="font-size: 13px; font-weight: bold; margin: 14px; text-align: center"><?php echo $text_selectform; ?></p></div>
					</div>
					<div class="float_left" style="width: 29%; margin-left: 20px;">
						<div class="float_right"><h1 style="color: #0A98CA;font-weight: bold;font-size: 42px">2</h1></div>
						<div class="float_right"><img src="<?php echo get_image_dir() . 'data/step2.png' ?>" width="110" height="93" alt="" /></div>
						<div class="float_left"><p style="font-size: 13px; font-weight: bold; margin: 14px; text-align: center"><?php echo $text_weemail; ?></p></div>
					</div>
					<div class="float_left" style="width: 29%; margin-left: 20px;">
						<div class="float_right"><h1 style="color: #0A98CA;font-weight: bold;font-size: 42px">3</h1></div>
						<div class="float_right"><img src="<?php echo get_image_dir() . 'data/step3.png' ?>" width="110" height="93" alt="" /></div>
						<div class="float_left"><p style="font-size: 13px; font-weight: bold; margin: 14px; text-align: center"><?php echo $text_entercode; ?></p></div>
					</div>
					<div class="clear"></div>
					<p style="width: 100%; font-size: 11px;" class="float_left">
						<?php echo $text_afterunlock; ?>
					</p>
					<div class="clear"></div>
					<div class="float_left" style="width: 100%; text-align: center">
					<?php if($this->session->data['language'] == 'en') { ?>
						<iframe src="//fast.wistia.net/embed/iframe/0ldbhscgop" allowtransparency="true" frameborder="0" scrolling="no" class="wistia_embed" name="wistia_embed" allowfullscreen mozallowfullscreen webkitallowfullscreen oallowfullscreen msallowfullscreen width="520" height="321"></iframe><script src="//fast.wistia.net/assets/external/E-v1.js" async></script>
					<?php } else { ?>
						<iframe src="//fast.wistia.net/embed/iframe/9s7q2fsdmf" allowtransparency="true" frameborder="0" scrolling="no" class="wistia_embed" name="wistia_embed" allowfullscreen mozallowfullscreen webkitallowfullscreen oallowfullscreen msallowfullscreen width="520" height="293"></iframe><script src="//fast.wistia.net/assets/external/E-v1.js" async></script>
					<?php } ?>	
					</div>
				</div>
				<div class="float_right" style="display: block">
						<div id="header_form" class="round_corners" style="display: block">
								<div class="form_content">
									<h1><?php echo $text_formhead; ?></h1>
									<div id="inputdiv" class="float_left field_input">
										<span class="wrong"></span>
										<div class="field">
											<div id="select_carrier">
												<select name="carrier" id="default-usage-select1">
													<option value=""><?php echo $text_formcarrier; ?></option>
													<?php foreach ($manufacturers as $manufacturer) { ?>
														<option value="<?php echo $manufacturer['manufacturer_id'] ?>"><?php echo $manufacturer['name'] ?></option>
													<?php } ?>
												</select>
											</div>
										</div>
										<div class="tool_tip">
											<div id="popup1" class="arrow_box" style="display: none;"><?php echo $text_selcarrier; ?></div>
											<a href="javascript:void(0)"><img img data-id="popup1" src="<?php echo $this->model_tool_image->resize('info-icon.png', 35, 28) ?>" alt="" /></a>
										</div>
									</div>
									<div id="inputdiv" class="float_left field_input">
										<span class="wrong"></span>
										<div class="field">
											<div id="select_category">
												<select name="category" id="default-usage-select2">
														<option value=""><?php echo $text_formmanufact; ?></option>
														<!--<option value="">^ Select Carrier to view</option>-->
														<?php if ($categories) { ?>
														<?php foreach ($categories as $category) { ?>
															<option value="<?php echo $category['category_id'] ?>"><?php echo $category['name'] ?></option>
														<?php } ?>
														<?php } ?>
												</select>
											</div>
										</div>
										<div class="tool_tip">
											<div id="popup2" class="arrow_box" style="display: none;"><?php echo $text_selbrand; ?></div>
											<a href="javascript:void(0)"><img data-id="popup2" src="<?php echo $this->model_tool_image->resize('info-icon.png', 35, 28) ?>" alt="" /></a>
										</div>                            
									</div>
									<div id="inputdiv" class="float_left field_input">
										<span class="wrong"></span>
										<div class="field">                        
											<div id="select_product">
												<select name="default-usage-select3" id="default-usage-select3">
													<option value=""><?php echo $text_formmodel; ?></option>
													<option value="">^ Select Manufacture to view</option>
												</select>
											</div>
										</div>
										<div class="tool_tip">
											<div id="popup3" class="arrow_box" style="display: none;"><?php echo $text_selmodel; ?></div>
											<a href="javascript:void(0)"><img data-id="popup3" src="<?php echo $this->model_tool_image->resize('info-icon.png', 35, 28) ?>" alt="" /></a>
										</div>
									</div>
									<div id="inputdiv" class="float_left field_input">
										<span class="wrong"></span>
										<div class="field">
											<input type="text" id="imei" name="imei" value="<?php echo $text_formimei; ?>" onfocus="this.value = ''" />
										</div>
										<div class="tool_tip">
											<div id="popup4" class="arrow_box" style="display: none;"><?php echo $text_dialimei; ?></div>
											<a href="javascript:void(0)"><img data-id="popup4" src="<?php echo $this->model_tool_image->resize('info-icon.png', 35, 28) ?>" alt="" /></a>
										</div>
									</div>
									<div id="inputdiv" class="float_left field_input">
										<span class="wrong"></span>
										<div class="field">
											<input type="text" id="email" name="email" value="<?php echo $text_formemail; ?>" onfocus="this.value = ''" />
										</div>
										<div class="tool_tip">
											<div id="popup5" class="arrow_box" style="display: none;"><?php echo $text_selemail; ?></div>
											<a href="javascript:void(0)"><img data-id="popup5" src="<?php echo $this->model_tool_image->resize('info-icon.png', 35, 28) ?>" alt="" /></a>
										</div>
									</div>
									<div id="errors" style="text-transform: none;" class="float_left field_input"></div>                        
									
									<!--
									<div class="float_left field_link" id="quote">
										<span style="color: #333333;font-size: 15px;">READY TO UNLOCK</span>
									</div> -->
									<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Funlockriver&amp;width=300&amp;layout=standard&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:300px; height:35px;" allowTransparency="true"></iframe>
								</div>

								<hr class="float_left"/>


								<div class="form_footer float_left">
										<div class="float_left" id="phone_thumb">
											<img src="<?php echo '/image/default_phone_'.$this->session->data['language'].'.png' ; ?>" height="100" />
										</div>
										<div class="float_left" id="phone_price">
											<div class="price"></div>
											<input class="round_corners_small" type="submit" name="add_to_cart" id="unlock_now_button" value="<?php echo $text_unlocknow; ?>" />
										</div>
										<p class="info"><?php echo $text_pleasenote; ?></p>

								</div>
								<div class="clear"></div>
							
						</div>
				</div>
            
            <div class="clear"></div>
    
            <?php echo $content_bottom; ?>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
	<div class="legal">
		<?php //echo $text_usa; ?>
	</div>
	<div style="text-align:center; margin-bottom: 20px; "><div class="fb-page" data-href="https://www.facebook.com/UnlockRiver" data-width="950" data-height="200" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/UnlockRiver"><a href="https://www.facebook.com/UnlockRiver">Unlock River</a></blockquote></div></div></div>
</div>

<!-- Facebook POPUP LikeBox With Timer Code Start -->
 <script language="javascript">
/*$(document).ready(function() {
$().socialTrafficPop({
// Configure display of popup
title: "TO CONTINUE PLEASE SUBSCRIBE & LIKE",
message: "",
closeable: false,
advancedClose: false,
opacity: '0.50',
// Configure URLs and Twitter
google_url: "",
fb_url: "",
twitter_user: "",
twitter_method: "follow",
// Set timers
timeout: 20,
wait: "1",
});
});*/
</script> 
<!-- Facebook POPUP LikeBox With Timer Code End -->

<div class="clear"></div>

<script type="text/javascript" charset="utf-8"> 
	 $('document').ready(function(){
		$("#header_form :input").change(function(e) {
		   var elemId = "#"+e.target.id;
		   if($(elemId).val()==''){
				$(elemId).parents('#inputdiv').find('span.wrong').removeClass('right');
				//$(elemId).addClass('wrong');
		   } else {
				$(elemId).parents('#inputdiv').find('span.wrong').addClass('right');
				//$(elemId).addClass('right');
		   }
		});
        // show tool tips
        $('.tool_tip img').hover(
            function() {
                tt_id = $(this).data("id");
                $('#' + tt_id).fadeIn(200);
            },
            function() {
                $('#' + tt_id).fadeOut(200);
            }
        );
     
        $('#default-usage-select2').change( function() { 
            $('#select_product').load('index.php?route=common/header/ajaxGetProducts&category_id=' + this.value, '', function(response, status, xhr){
				if (status == "error") {
					var msg = "Sorry but there was an error: ";
					$("#errors").html(msg + xhr.status + " " + xhr.statusText);
				}
				
                $('#default-usage-select3').change( function() {
                    $.getJSON('index.php?route=common/header/ajaxGetProduct&prod_id=' + this.value, function (json) {
                        var price = $.number(json.price, 2);
                        var reg_price = Number(price) + 12;
                        var deliver_time = json.delivery_time;
                        
                        if (json.image !== null && json.image !== undefined) {
                            if (json.image.length == 0) {
                                var phone_img = 'image/no_image.jpg';
                            } else {
                                var phone_img = 'image/' + json.image;
                            }                            
                        } else {
                            var phone_img = 'image/no_image.jpg';
                        }
                        
                        //$('#phone_price .price').html('<span class="delivery">Delivery Time: ' + deliver_time + '</span><br /><span class="reg_price">regular: $' + reg_price + '</span><br /><span class="delivery">Special Price: </span>$' + price);
						$('#phone_price .price').html('<span class="delivery"><?php echo $text_deltime; ?> ' + deliver_time + '</span><br /><span class="delivery"><?php echo $text_formprice; ?> </span>$' + price);
                        $('#phone_thumb').html('<img src="' + phone_img + '" height="100" />');
                    });

					if(this.value==''){
							$('#default-usage-select3').parents('#inputdiv').find('span.wrong').removeClass('right');
					} else {
							$('#default-usage-select3').parents('#inputdiv').find('span.wrong').addClass('right');
					}
                });
            });
        });
		
		$('#default-usage-country').change( function() {
			country = $("#default-usage-country option:selected").text();
            $('#select_carrier').load('index.php?route=common/header/ajaxGetCarriers&country_name=' + encodeURIComponent(country), '', function(response, status, xhr){
				if (status == "error") {
					var msg = "Sorry but there was an error: ";
					$("#errors").html(msg + xhr.status + " " + xhr.statusText);
				}
				
				$('#default-usage-select1').change( function() {
					/*manufacturer_id = $("#default-usage-select1 option:selected").val();
					$('#select_category').load('index.php?route=common/header/ajaxGetManufacturers&manufacturer_id=' + encodeURIComponent(manufacturer_id), '', function(response, status, xhr){
						if (status == "error") {
							var msg = "Sorry but there was an error: ";
							$("#errors").html(msg + xhr.status + " " + xhr.statusText);
						}
					});*/
					if(this.value==''){
							$('#default-usage-select1').parents('#inputdiv').find('span.wrong').removeClass('right');
					} else {
							$('#default-usage-select1').parents('#inputdiv').find('span.wrong').addClass('right');
					}
				});
            });
        });

        $('#unlock_now_button').click( function(e) {
            addToCartHeader($('#default-usage-select1').val(), $('#default-usage-select2').val(), $('#default-usage-select3').val(), $('#imei').val(), $('#email').val(), '<?php echo $lang; ?>' );
            e.preventDefault;
        })
    })
</script>
	
	
<?php echo $footer; ?>