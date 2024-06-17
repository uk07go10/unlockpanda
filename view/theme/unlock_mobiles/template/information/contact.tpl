<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
        <div class="top_content">
            <div class="content_top">
				<div class="description" style="max-height:2000px">
					<h1><?php echo $heading_title; ?></h1>
				<div style="width:450px;display:inline-block;">
					<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="contact">
                        
                        <h2><?php echo $text_contact; ?></h2>
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
                                <b><?php echo $entry_captcha; ?></b>
                                <br />
                                <br />
                                <div class="float_left" style="padding: 5px 10px 0 0;"><input type="text" class="input_field" name="captcha" value="<?php echo $captcha; ?>" /></div>
                                <div class="float_left"><img src="index.php?route=information/contact/captcha" alt="" /></div>
                                <?php if ($error_captcha) { ?>
                                <br><br><br>
                                <span class="error"><?php echo $error_captcha; ?></span>
                                <?php } ?>
                                <div class="clear"></div>
                                <div class="buttons">
                                    <div class="right"><a onclick="$('#contact').submit();" class="button"><span class="round_corners_small"><?php echo $button_continue; ?></span></a></div>
                                </div>
                        </div>
                        
                    </form>
				</div>
				<div style="width:370px;float:right;">
					<ol>
					<li><a href="#unlockcode" class="fancybox"><?php echo $text_ques1; ?></a></li>
					<li><a href="apply-for-a-refund"><?php echo $text_ques2; ?></a></li>
					<li><a href="troubleshooting"><?php echo $text_ques3; ?></a></li>
					<li><a href="index.php?route=information/orderstatus"><?php echo $text_ques4; ?></a></li>					
					</ol>
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
		<div style="display:none;">
		<div id="unlockcode">
			<?php echo $text_ans; ?>
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