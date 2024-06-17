<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
        <div class="top_content">
            <div class="content_top">
                <img src="<?php echo $this->model_tool_image->resize('data/banner.png', 882, 380) ?>" alt="Banner"/>
            </div>
        </div>
        <div id="content_page">
            <div class="content_top" style="width: 50%">
                <h1><?php echo $heading_title; ?></h1>
                <?php if ($success) { ?>
                <div class="success"><?php echo $success; ?></div>
                <?php } ?>
                <?php if ($error_warning) { ?>
                <div class="warning"><?php echo $error_warning; ?></div>
                <?php } ?>
                <div class="login-content">
                    <div >
                    <h2><?php echo $text_returning_customer; ?></h2>
                    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="login">
                        <div class="content" >
                        <p><?php echo $text_i_am_returning_customer; ?></p>
                        <b><?php echo $entry_email; ?></b><br />
                        <input class="input_field" type="text" name="email" value="" />
                        <br />
                        <br />
                        <b><?php echo $entry_password; ?></b><br />
                        <input class="input_field" type="password" name="password" value="" />
                        <br />
                        <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a><br />
                        <br />
                        <a onclick="$('#login').submit();" class="button"><span class="round_corners_small"><?php echo $button_login; ?></span></a>
                        <?php if ($redirect) { ?>
                        <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
                        <?php } ?>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <?php echo $content_bottom; ?>
        </div>

</div>
<script type="text/javascript"><!--
//$('#login input').keydown(function(e) {
//	if (e.keyCode == 13) {
//		$('#login').submit();
//	}
//});
//--></script>   
<?php echo $footer; ?>