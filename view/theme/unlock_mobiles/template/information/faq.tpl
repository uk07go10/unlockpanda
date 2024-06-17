<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
        <?php echo $content_top; ?>
        <div class="top_content">
            <div class="content_top">
				<div class="description" style="max-height: 5000px;">
					<div>
						<h1><?php echo $heading_title; ?></h1>
							<div>
								<?php if ($faqs) { ?>
									<ol class="faq">
									<?php $id = 1; foreach ($faqs as $faq) { ?>
										<li><a href="index.php?route=information/faq#<?php echo $id;?>"><?php echo $faq['q']; ?></a></li>	
									<?php $id++; } ?>
									</ol>
									<?php $ida = 1; foreach ($faqs as $faq) { ?>
								<div class="testimonial" id="<?php echo $ida;?>">    
									<div class="inner">
										<div class="float_left"><b style="font-size: 30px;">Q:</b></div>
										<div class="float_right" style="width: 90%"><?php echo $faq['q']; ?></div>
										<div class="clear"></div>
										<hr style="color: #dddddd"/>
										<div class="float_left"><b style="font-size: 30px;">A:</b></div>
										<div class="float_right" style="width: 90%"><?php echo $faq['a']; ?></div>
										<div class="clear"></div>
									</div>
									<div class="clear"></div>
								</div>
									   
									   
									<?php $ida++; } ?>
							  
	<!--                            <div class="pagination"><?php echo $pagination; ?></div>-->
								<?php }else{ ?>
								<div class="warning">No FAQs!</div>
								<?php } ?>
							</div>
					</div>
				</div>
            </div>
        </div>
        <div id="content_page">
            <div class="content_top">
                <div class="buttons">
					<div class="right"><a href="<?php echo $continue; ?>" class="button"><span class="round_corners_small"><?php echo $button_continue; ?></span></a></div>
				</div>
            </div>
        </div>
        <?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?> 