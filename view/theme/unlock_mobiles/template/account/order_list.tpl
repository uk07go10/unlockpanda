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
                        <?php if ($orders) { ?>
                            <div class="content">
                                <?php foreach ($orders as $order) { ?>
                                <div class="order-list">
                                    <div class="order-id"><b><?php echo $text_order_id; ?></b> #<?php echo $order['order_id']; ?></div>
                                    <div class="order-status"><b><?php echo $text_status; ?></b> <?php echo $order['status']; ?></div>
                                    <div class="order-content">
                                    <div><b><?php echo $text_date_added; ?></b> <?php echo $order['date_added']; ?><br />
                                        <b><?php echo $text_products; ?></b> <?php echo $order['products']; ?></div>
                                    <div><b><?php echo $text_customer; ?></b> <?php echo $order['name']; ?><br />
                                        <b><?php echo $text_total; ?></b> <?php echo $order['total']; ?></div>
                                    <div class="order-info"><a href="<?php echo $order['href']; ?>" class="button"><span class="round_corners_small"><?php echo $button_view; ?></span></a></div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        <div class="pagination"><?php echo $pagination; ?></div>
                        <?php } else { ?>
                        <div class="content"><?php echo $text_empty; ?></div>
                        <?php } ?>
                        <div class="buttons">
                            <div class="right"><a href="<?php echo $continue; ?>" class="button"><span class="round_corners_small"><?php echo $button_continue; ?></span></a></div>
                        </div>
                        <?php echo $content_bottom; ?>
                </div>
        </div>
</div>
<?php echo $footer; ?>