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
                        <?php if ($error_warning) { ?>
                        <div class="warning"><?php echo $error_warning; ?></div>
                        <?php } ?>
                        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="forgotten">
                            <p><?php echo $text_email; ?></p>
                            <h2><?php echo $text_your_email; ?></h2>
                            <div class="content">
                            <table class="form">
                                <tr>
                                <td><?php echo $entry_email; ?></td>
                                <td><input type="text" class="input_field" name="email" value="" /></td>
                                </tr>
                            </table>
                            </div>
                            <div class="buttons">
<!--                            <div class="left"><a href="<?php echo $back; ?>" class="button"><span><?php echo $button_back; ?></span></a></div>-->
                                <div class="right"><a onclick="$('#forgotten').submit();" class="button"><span class="round_corners_small"><?php echo $button_continue; ?></span></a></div>
                            </div>
                        </form>
                        <?php echo $content_bottom; ?>
                </div>
        </div>

</div>
<?php echo $footer; ?>