<div class="box">
	<div id="hometestimonials">
		<span class="button-testimonial"><?php if ($testimonial_title=="") echo ""; else echo $testimonial_title; ?></span><span class="line"></span>
		<h3 class="testimonial-head"><?php echo $t_head; ?></h3>
		<div class="testimonial-box">
			<div>
				<div class="t-image"><img width="50px" height="50px" alt="logo" src="<?php echo get_image_dir() . 't1.png'?>"></div>
				<div class="t-title"><?php echo $t_name1; ?></div>
				<div class="t-desc"><?php echo $t_desc1; ?></div>
			</div>
			<div>
				<div class="t-image"><img width="50px" height="50px" alt="logo" src="<?php echo get_image_dir() . 't2.png'?>"></div>
				<div class="t-title"><?php echo $t_name2; ?></div>
				<div class="t-desc"><?php echo $t_desc2; ?></div>
			</div>
			<div>
				<div class="t-image"><img width="50px" height="50px" alt="logo" src="<?php echo get_image_dir() . 't3.png'?>"></div>
				<div class="t-title"><?php echo $t_name3; ?></div>
				<div class="t-desc"><?php echo $t_desc3; ?></div>
			</div>
			<div>
				<div class="t-image"><img width="50px" height="50px" alt="logo" src="<?php echo get_image_dir() . 't4.png'?>"></div>
				<div class="t-title"><?php echo $t_name4; ?></div>
				<div class="t-desc"><?php echo $t_desc4; ?></div>
			</div>
		<?php //foreach ($testimonials as $testimonial) { ?>
			<!--<div>
				<div class="t-image"><img alt="logo" src="http://www.unlockriver.com/image/logo-small.png"></div>
				<div class="t-title"><?php //echo $testimonial['name'] . ", ".$testimonial['city']; ?></div>
				<div class="t-desc"><?php //echo '"'.$testimonial['description'].'"'; ?></div>
			</div>-->
		<?php //} ?>	
		</div>
    </div>
	<!-- Trustpilot TrustBox 2.0 -->
	<script async type="text/javascript" src="//widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js"></script>
	<div class="trustpilot-widget" data-locale="en-US" data-template-id="53aa8912dec7e10d38f59f36" data-businessunit-id="544819a600006400057b1620" data-style-height="130" data-style-width="100%" data-stars="4,5"></div>
	<!-- Trustpilot TrustBox 2.0 -->
</div>

