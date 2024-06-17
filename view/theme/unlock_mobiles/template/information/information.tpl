<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
        <div class="top_content">
            <div class="content_top">
				<div class="description" style="max-height: 10000px;">
					 <h1><?php echo $heading_title; ?></h1>
							<?php echo $description; ?>
				</div>
            </div>
        </div>
        <div id="content_page">
                <div class="content_top" >
                       <!--<div class="buttons">
								<div class="right"><a href="<?php echo $continue; ?>" class="button"><span class="round_corners_small"><?php echo $button_continue; ?></span></a></div>
							</div>-->
                        <?php echo $content_bottom; ?>
                </div>
        </div>
</div>                
<?php echo $footer; ?>