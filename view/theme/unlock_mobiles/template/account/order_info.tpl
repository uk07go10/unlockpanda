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
                        <table class="list">
                            <thead>
                            <tr>
                                <td class="left" colspan="2"><?php echo $text_order_detail; ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="left" style="width: 50%;"><?php if ($invoice_no) { ?>
                                <b><?php echo $text_invoice_no; ?></b> <?php echo $invoice_no; ?><br />
                                <?php } ?>
                                <b><?php echo $text_order_id; ?></b> #<?php echo $order_id; ?><br />
                                <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?></td>
                                <td class="left">
                                    <b><?php echo $text_payment_method; ?></b> <?php echo $payment_method; ?><br />
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="order">
                            <table class="list">
                            <thead>
                                <tr>
                                <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
                                <td class="left"><?php echo $column_name; ?></td>
                                <td class="left"><?php echo 'IMEI'; ?></td>
                                <td class="left"><?php echo 'Carrier'; ?></td>
                                <td class="right"><?php echo $column_quantity; ?></td>
                                <td class="right"><?php echo $column_price; ?></td>
                                <td class="right"><?php echo $column_total; ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product) { ?>
                                <tr>
                                <td style="text-align: center; vertical-align: middle;"><?php if ($product['selected']) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $product['order_product_id']; ?>" checked="checked" />
                                    <?php } else { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $product['order_product_id']; ?>" />
                                    <?php } ?></td>
                                <td class="left">
                                    <?php echo $product['name']; ?><input type="hidden" name="" value="" />
                                </td>
                                <td class="left"><?php echo $product['imei']; ?></td>
                                <td class="left"><?php echo $product['carrier']; ?></td>
                                <td class="right"><?php echo $product['quantity']; ?></td>
                                <td class="right"><?php echo $product['price']; ?></td>
                                <td class="right"><?php echo $product['total']; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <?php foreach ($totals as $total) { ?>
                                <tr>
                                <td colspan="5"></td>
                                <td class="right"><b><?php echo $total['title']; ?>:</b></td>
                                <td class="right"><?php echo $total['text']; ?></td>
                                </tr>
                                <?php } ?>
                            </tfoot>
                            </table>
                            <div class="buttons">
<!--                            <div class="right"><?php echo $text_action; ?>
                                <select name="action" onchange="$('#order').submit();">
                                <option value="" selected="selected"><?php echo $text_selected; ?></option>
                                <option value="reorder"><?php echo $text_reorder; ?></option>
                                <option value="return"><?php echo $text_return; ?></option>
                                </select>
                            </div>-->
                            </div>
                        </form>
                        
                        <?php if ($histories) { ?>
                        <h2><?php echo $text_history; ?></h2>
                        <table class="list">
                            <thead>
                            <tr>
                                <td class="left"><?php echo $column_date_added; ?></td>
                                <td class="left"><?php echo $column_status; ?></td>
                                <td class="left"><?php echo $column_comment; ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($histories as $history) { ?>
                            <tr>
                                <td class="left"><?php echo $history['date_added']; ?></td>
                                <td class="left"><?php echo $history['status']; ?></td>
                                <td class="left"><?php echo $history['comment']; ?></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <?php } ?>
                        <div class="buttons">
                            <div class="right"><a href="<?php echo $continue; ?>" class="button"><span class="round_corners_small"><?php echo $button_continue; ?></span></a></div>
                        </div>
                        <?php echo $content_bottom; ?>
                </div>
        </div>
</div>
<?php echo $footer; ?> 