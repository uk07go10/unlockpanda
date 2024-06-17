<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
        <div class="top_content">
            <div class="content_top">
				<div class="description" style="max-height:2000px">
					<h1><?php echo $heading_title; ?></h1>
				<div style="width:450px;display:inline-block;">
					<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="wholesale">
                        
                        <h2><?php echo $text_wholesale; ?></h2>
                        <div class="content">
                                <b><?php echo $entry_name; ?></b><br />
                                <input type="text" class="input_field" name="name" value="<?php echo $name; ?>" />
                                <br />
                                <?php if ($error_name) { ?>
                                <span class="error"><?php echo $error_name; ?></span>
                                <?php } ?>
                                <br />
                                <b><?php echo $entry_email; ?></b><br />
                                <input type="text" class="input_field" name="email" value="<?php echo $email; ?>" />
                                <br />
                                <?php if ($error_email) { ?>
                                <span class="error"><?php echo $error_email; ?></span>
                                <?php } ?>
                                <br />
                                <b><?php echo $entry_enquiry; ?></b><br />
                                <textarea class="input_field" name="enquiry" cols="40" rows="10" style="width: 99%;"><?php echo $enquiry; ?></textarea>
                                <br />
                                <?php if ($error_enquiry) { ?>
                                <span class="error"><?php echo $error_enquiry; ?></span>
                                <?php } ?>
                                <br />
								<b><?php echo $entry_selorder; ?></b><br />
                                <select name="dailyorders" style="margin:0px 0 15px 0; width: 150px;">
									<option value="1 to 5 unlock codes">1 to 5 unlock codes</option>
									<option value="6 to 20 unlock codes">6 to 20 unlock codes</option>
									<option value="21 to 60 unlock codes">21 to 60 unlock codes</option>
									<option value="more than 100 daily">more than 100 daily</option>
								</select>
                                <br />
                                <b><?php echo $entry_captcha; ?></b>
                                <br />
                                <div class="float_left" style="padding: 5px 10px 0 0;"><input type="text" class="input_field" name="captcha" value="<?php echo $captcha; ?>" /></div>
                                <div class="float_left"><img src="index.php?route=information/wholesale/captcha" alt="" /></div>
                                <?php if ($error_captcha) { ?>
                                <br><br><br>
                                <span class="error"><?php echo $error_captcha; ?></span>
                                <?php } ?>
                                <div class="clear"></div>
                                <div class="buttons">
                                    <div class="right"><a onclick="$('#wholesale').submit();" class="button"><span class="round_corners_small"><?php echo $button_continue; ?></span></a></div>
                                </div>
                        </div>
                        
                    </form>
				</div>
				<div style="width:370px;float:right;">
					<div>
						<img src="image/tech_support_icon.png" alt="" width="370" height="370" />
					</div>
				</div>
					</div>
                    <?php echo $content_bottom; ?>
			</div>
        </div>
        <div id="content_page">
                <div class="content_top" >
                    
            </div>
        </div>
</div>
<script type="text/javascript">
$('.fancybox').fancybox({
width: 640,
height: 240,
autoDimensions: false
});
</script>
<?php echo $footer; ?>