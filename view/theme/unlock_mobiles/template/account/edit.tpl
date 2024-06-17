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
                        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="edit">
                            <h2><?php echo $text_your_details; ?></h2>
                            <div class="content">
                            <table class="form">
                                <tr>
                                <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
                                <td><input type="text" class="input_field" name="firstname" value="<?php echo $firstname; ?>" />
                                    <?php if ($error_firstname) { ?>
                                    <span class="error"><?php echo $error_firstname; ?></span>
                                    <?php } ?></td>
                                </tr>
                                <tr>
                                <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
                                <td><input type="text" class="input_field" name="lastname" value="<?php echo $lastname; ?>" />
                                    <?php if ($error_lastname) { ?>
                                    <span class="error"><?php echo $error_lastname; ?></span>
                                    <?php } ?></td>
                                </tr>
                                <tr>
                                <td><span class="required">*</span> <?php echo $entry_email; ?></td>
                                <td><input type="text" class="input_field" name="email" value="<?php echo $email; ?>" />
                                    <?php if ($error_email) { ?>
                                    <span class="error"><?php echo $error_email; ?></span>
                                    <?php } ?></td>
                                </tr>
                                <tr>
                                <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
                                <td><input type="text" class="input_field" name="telephone" value="<?php echo $telephone; ?>" />
                                    <?php if ($error_telephone) { ?>
                                    <span class="error"><?php echo $error_telephone; ?></span>
                                    <?php } ?></td>
                                </tr>
                            </table>
                            </div>
                            <div class="buttons">
                                <div class="left"><a href="<?php echo $back; ?>" class="button"><span class="round_corners_small"><?php echo $button_back; ?></span></a></div>
                                <div class="right"><a onclick="$('#edit').submit();" class="button"><span class="round_corners_small"><?php echo $button_continue; ?></span></a></div>
                            </div>
                        </form>
                        <?php echo $content_bottom; ?>
                </div>
        </div>
</div>
<?php echo $footer; ?>