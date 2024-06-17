<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="box-product">
        <?php $i = 1; ?>
        <?php foreach ($products as $product) { ?>
                <div class="product_container <?php if($i % 3 == 0){ echo "last_box_item"; }  ?>">
                    <div class="inner">
                            <h2><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h2>
                            <?php if ($product['thumb']) { ?>
                            <div class="image float_left"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
                            <?php } ?>
                            <div class="float_left" style="width: 160px;margin-left: 15px;" >
                                <div style="height: 110px; overflow: hidden; font-size: 11px; line-height: 17px;"> <?php echo $product['description']; ?></div>
                                <div class="clear"></div>
                                <div class="cart float_right">
                                    <a href="<?php echo $product['href']; ?>" class="button"><span class="round_corners_small"><?php echo 'Unlock Now'; ?></span></a>
                                </div>
                            </div>
                    </div>
                </div>
                <?php $i++; ?>
        <?php } ?>
    </div>
  </div>
  <div class="clear"></div>
</div>
<div class="clear"></div>
