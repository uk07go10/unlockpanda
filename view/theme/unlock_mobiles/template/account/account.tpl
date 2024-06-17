<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
        <div class="top_content">
            <div class="content_top">
                <img src="<?php echo $this->model_tool_image->resize('data/banner.png', 882, 380) ?>" alt="Banner"/>
            </div>
        </div>
        <div id="content_page">
                <div class="content_top" >
                        <h1><?php echo $heading_title; ?></h1>
                        <?php if ($success) { ?>
                        <div class="success"><?php echo $success; ?></div>
                        <?php } ?>
<!--                        <h2><?php echo $text_my_account; ?></h2>-->
                        <div class="">
                            <ul class="account_menu">
                                <li><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>
                                <li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
                                <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
                            </ul>
                        </div>
                        <?php echo $content_bottom; ?>
                </div>
        </div>
</div>
<?php echo $footer; ?> 