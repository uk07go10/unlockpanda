<?php echo $header; ?>
<?php echo $column_left; ?>
<?php echo $column_right; ?>

<script>
    $(function() {
        var config = {
            url: "<?php echo $referral_link?>",
            title: "<?php echo $referral_text_title ?>",
            description: "<?php echo $referral_text_description ?>",
            image: "<?php echo $referral_text_image ?>",
            ui: {
                flyout: "middle right"
            },
            networks: {
                facebook: {
                    load_sdk: true,
                    app_id: "135715593186470"
                },
                reddit: {enabled: false},
                linkedin: {enabled: false},
                email: {enabled: false}
            }
        };
       new ShareButton(config);
    });
</script>

<div id="content">
    <?php echo $content_top; ?>
    <div id="content_page">
        <div class="content_top">
            <h1 style="display:inline-block">
                <?php echo $heading_title; ?>
            </h1>
            <h4 class="login_info">
                <?php echo sprintf($text_logged_in_as, $referral_email); ?>
                <a class="logout" href="index.php?route=information/referral/logout">
                    <?php echo $text_logout; ?>
                </a>
            </h4>
            <div id="flash_placeholder">
                <?php if($flash): ?>
                    <div class="<?php echo $flash["type"]; ?>" style="width: 95%">
                        <?php echo $flash["content"]; ?>
                    </div>
                <?php endif; ?>
            </div>
            <p>
                <?php echo sprintf($text_link_description,
                    $referral_percent,
                    round($referral_percent, 2),
                    $referral_min_payout,
                    $referral_add_lock_time
                    ); ?>
            </p>

            <h2><?php echo $text_personal_link ?></h2>
            <div class="sharer">
                <div class="float_left" style="width: 12%;">
                    <share-button></share-button>
                </div>
                <div class="float_left" style="width: 30%; font-size: 14px; margin-top: 3px;">
                    <?php echo $text_or_just_send; ?>
                </div>
                <div class="float_left" style="width: 57%">
                    <input class="referral_link" type="text" disabled="disabled" value="<?php echo $referral_link; ?>">
                </div>
                <div class="clearfix"></div>
            </div>
            <p style="margin-top:30px;">
                &nbsp;
            </p>
            <h2><?php echo $text_payout; ?></h2>
            <?php if(!$referral_payout_enabled && $referral_balance > 0): ?>
                <div id="payout_amount_attention" class="attention">
                    <?php echo sprintf($text_payout_disabled, $referral_min_payout) ?>
                </div>
            <?php endif; ?>
            <p style="display:inline-block;">
                <?php echo sprintf($text_balance, round($referral_balance, 2), round($referral_balance_locked, 2)) ?>
            </p>
            <form action="index.php?route=information/referral/payout" method="post" class="float_right">
                $<input id="amount" <?php echo (!$referral_payout_enabled ? "disabled" : ""); ?> type="text" class="round_corners_small " name="amount" style="display: inline-block; width: 50px; text-align: right;" value="<?php echo round($referral_balance, 2) ?>" placeholder="<?php echo $placeholder_amount; ?>">
                <input id="payout" <?php echo (!$referral_payout_enabled ? "disabled" : ""); ?> type="submit" class="round_corners_small" style="display: inline-block" value="<?php echo $button_payout; ?>" />
            </form>
            <h2><?php echo $text_history; ?></h2>
            <?php if(!empty($referral_history)): ?>
                <table class="list spaced">
                    <thead>
                    <tr>
                        <td><?php echo $table_header_date ?></td>
                        <td><?php echo $table_header_action ?></td>
                        <td><?php echo $table_header_amount ?></td>
                        <td><?php echo $table_header_comment ?></td>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach($referral_history as $row): ?>
                            <tr>
                                <td><?php echo $row["created_date"] ?></td>
                                <td>
                                    <?php echo isset(
                                        ${"table_action_" . $row["action"]}) ?
                                        ${"table_action_" . $row["action"]} : $row["action"]  ?>
                                </td>
                                <td><?php echo ($row["value"] > 0 ?
                                        "+ $" . round($row["value"], 2) :
                                        "- $" . round(-$row["value"], 2)) ?></td>
                                <td>
                                    <?php echo isset(
                                        ${"table_explain_" . $row["action"]}) ?
                                        ${"table_explain_" . $row["action"]} : ""  ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <?php echo $text_no_history; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php echo $content_bottom; ?>
</div>

<?php echo $footer; ?>