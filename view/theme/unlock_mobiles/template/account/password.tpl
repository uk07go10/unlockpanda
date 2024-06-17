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
                        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="password">
                            <h2><?php echo $text_password; ?></h2>
                            <div class="content">
                            <table class="form">
                                <tr>
                                <td><span class="required">*</span> <?php echo $entry_password; ?></td>
                                <td><input type="password" class="input_field" name="password" value="<?php echo $password; ?>" />
                                    <?php if ($error_password) { ?>
                                    <span class="error"><?php echo $error_password; ?></span>
                                    <?php } ?></td>
                                </tr>
                                <tr>
                                <td><span class="required">*</span> <?php echo $entry_confirm; ?></td>
                                <td><input type="password" class="input_field" name="confirm" value="<?php echo $confirm; ?>" />
                                    <?php if ($error_confirm) { ?>
                                    <span class="error"><?php echo $error_confirm; ?></span>
                                    <?php } ?></td>
                                </tr>
                            </table>
                            </div>
                            <div class="buttons">
                                <div class="left"><a href="<?php echo $back; ?>" class="button"><span class="round_corners_small"><?php echo $button_back; ?></span></a></div>
                            <div class="right"><a onclick="$('#password').submit();" class="button"><span class="round_corners_small"><?php echo $button_continue; ?></span></a></div>
                            </div>
                        </form>
                        <?php echo $content_bottom; ?>
                </div>
        </div>
</div>
<?php echo $footer; ?>