<?php echo $header; ?>
<div class="container"><?php echo $column_left; ?><?php echo $column_right; ?>
  <div id="content"><?php echo $content_top; ?>
        <div class="top_content">
            <div class="content_top">
                <img src="<?php echo $this->model_tool_image->resize('data/banner.png', 882, 380) ?>" alt="Banner"/>
            </div>
        </div>
        <div id="content_page">
                <div class="content_top" >
                <h1><?php echo $heading_title; ?></h1>
                <div class="content"><?php echo $text_error; ?></div>
                <div class="buttons">
                    <div class="right"><a href="<?php echo $continue; ?>" class="button"><span class="round_corners_small"><?php echo $button_continue; ?></span></a></div>
                </div>
                <?php echo $content_bottom; ?>
                </div>
        </div>
  </div>
</div>
<?php echo $footer; ?>