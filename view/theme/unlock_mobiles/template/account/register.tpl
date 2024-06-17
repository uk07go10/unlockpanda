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
                        <p><?php echo $text_account_already; ?></p>
                        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="register">
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
                            <h2><?php echo $text_your_password; ?></h2>
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
                            
                            <?php if ($text_agree) { ?>
                            <div class="buttons">
                            <div class="right"><?php echo $text_agree; ?>
                                <?php if ($agree) { ?>
                                <input type="checkbox" name="agree" value="1" checked="checked" />
                                <?php } else { ?>
                                <input type="checkbox" name="agree" value="1" />
                                <?php } ?>
                                <a onclick="$('#register').submit();" class="button"><span class="round_corners_small"><?php echo $button_continue; ?></span></a></div>
                            </div>
                            <?php } else { ?>
                            <div class="buttons">
                            <div class="right"><a onclick="$('#register').submit();" class="button"><span><?php echo $button_continue; ?></span></a></div>
                            </div>
                            <?php } ?>
                        </form>
                        <?php echo $content_bottom; ?>
                </div>
        </div>
</div>
<script type="text/javascript"><!--
$('select[name=\'zone_id\']').load('index.php?route=account/register/zone&country_id=<?php echo $country_id; ?>&zone_id=<?php echo $zone_id; ?>');
//--></script> 
<script type="text/javascript"><!--
$('.fancybox').fancybox({
	width: 560,
	height: 560,
	autoDimensions: false
});
//--></script>  
<?php echo $footer; ?>