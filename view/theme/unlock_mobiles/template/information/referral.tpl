<?php echo $header; ?>
<?php echo $column_left; ?>
<?php echo $column_right; ?>

<script>

    var STATE_WAITING = 1;
    var STATE_LOGGING = 2;

    var putNotice = function(content, type) {
        var flash = $("#flash_" + type);
        flash.show().text(content);
    };

    var state = <?php echo ($already_registered ? "STATE_LOGGING" : "STATE_WAITING"); ?>;

    $(function() {
        $("#login_register").live("submit", function(e) {
            if(state == STATE_LOGGING) {
                return;
            }

            e.preventDefault();
            $.post("index.php?route=information/referral/login_register", {
                email: $("#email").val()
            }, function(response) {
                if(response.error) {
                    putNotice(response.message, "attention");
                } else if (response.already_registered) {
                    state = STATE_LOGGING;
                    putNotice(response.message, "success");
                    $("#p_password").show();

                    $("#button_continue").hide();
                    $("#button_login").show();

                } else {
                    // not registered
                    putNotice(response.message, "success");
                }
            });
        });
    });
</script>

<div id="content">
        <?php echo $content_top; ?>
        <div class="top_content">
            <div class="content_top">
				<!--<img src="<?php echo $this->model_tool_image->resize('data/banner.png', 882, 380) ?>" alt="Banner"/>-->
            </div>
        </div>
        <div id="content_page">
            <div class="content_top">
                <h1><?php echo $heading_title; ?></h1>
                <div id="flash_placeholder">
                    <div id="flash_attention" class="attention" style="width: 95%; display: none;"></div>
                    <div id="flash_success" class="success" style="width: 95%; display: none;"></div>

                    <?php if($flash): ?>
                        <div class="<?php echo $flash["type"]; ?>" style="width: 95%">
                            <?php echo $flash["content"]; ?>
                        </div>
                    <?php endif; ?>
                </div>
                    <p>
                        <?php echo $text_description; ?>
                    </p>
                    <p>
                        <?php echo $text_insert ?>
                    </p>

                <form id="login_register" action="index.php?route=information/referral/login" method="post">
                    <p>
                        <input placeholder="<?php echo $placeholder_email ?>" type="email" name="email" id="email" class="round_corners_small spaced"/>
                    </p>
                    <p id="p_password" style="display:<?php echo ($already_registered ? "block" : "none") ?>">
                        <input placeholder="<?php echo $placeholder_password ?>" type="password" name="password" id="password" class="round_corners_small spaced"/>
                    </p>
                    <p>
                        <input style="display:<?php echo ($already_registered ? "none" : "inline-block") ?>" id="button_continue" type="submit" value="<?php echo $button_continue; ?>" />
                        <input style="display:<?php echo ($already_registered ? "inline-block" : "none") ?>" class="round_corners" id="button_login" type="submit" value="<?php echo $button_login; ?>" />
                    </p>
                </form>
            </div>
        </div>
        <?php echo $content_bottom; ?>
</div>

<?php echo $footer; ?> 