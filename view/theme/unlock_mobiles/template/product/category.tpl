<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
        <div class="top_content">
            <div class="content_top">
                    <div class="description">
                         <h1><?php echo $heading_title; ?></h1>
                         <div style="min-height: 90px">
                            <?php if ($description) { ?>
                                <?php echo $description; ?>
                            <?php } ?>
                         </div>
                         <hr/>
                         <div class="content_top">
                             <h3 class="float_left">CHOOSE YOUR <?php echo $heading_title; ?> DEVICE:</h3>
                             <div class="float_left" style="margin-left: 30px;">
                                 <select name="product" id="default-usage-select" onchange="this.value != '' ? window.location=this.value : ''">
                                    <option value="">--PLEASE CHOOSE--</option>
                                    <?php foreach ($products as $product) { ?>
                                        <option value="<?php echo $product['href'] ?>"><?php echo $product['name'] ?></option>
                                    <?php } ?>
                                </select>
                             </div>
                         </div>
                                         <div class="clear"></div>
                    </div>

            </div>
        </div>
        <div id="content_page">
                <div class="content_top" >
                        <?php if ($products) { ?>
<!--                        <div class="product-filter">
                            <div class="display"><b><?php echo $text_display; ?></b> <?php echo $text_list; ?> <b>/</b> <a onclick="display('grid');"><?php echo $text_grid; ?></a></div>
                            <div class="limit"><b><?php echo $text_limit; ?></b>
                            <select onchange="location = this.value;">
                                <?php foreach ($limits as $limits) { ?>
                                <?php if ($limits['value'] == $limit) { ?>
                                <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                            </div>
                            <div class="sort"><b><?php echo $text_sort; ?></b>
                            <select onchange="location = this.value;">
                                <?php foreach ($sorts as $sorts) { ?>
                                <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
                                <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                            </div>
                        </div>-->
<!--                        <div class="product-compare"><a href="<?php echo $compare; ?>" id="compare_total"><?php echo $text_compare; ?></a></div>-->
                        <h2 class="manufacturer-heading"><?php echo $heading_title ?> PHONES </h2>
                        <div class="home_instructions">
                            <?php $i = 0; ?>
                            <?php foreach ($products as $product) { ?>
                                    <?php if( $i % 5 == 0 && $i != 0 ){ ?><div class="clear"></div> <?php } ?> 
                                    <div class="float_left products_list">
                                        <?php if ($product['thumb']) { ?>
                                        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
                                        <?php } ?>
                                        <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>

        <!--                                <div class="cart"><a onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button"><span><?php echo $button_cart; ?></span></a></div>-->

                                    </div>
                                    <?php $i++; ?>
                            <?php } ?>
                            <div class="clear"></div>
                        </div>
                        <div class="pagination"><?php echo $pagination; ?></div>
                        <?php } ?>
                        <?php if (!$categories && !$products) { ?>
                        <div class="content"><?php echo $text_empty; ?></div>
                        <div class="buttons">
                            <div class="right"><a href="<?php echo $continue; ?>" class="button"><span class="round_corners_small"><?php echo $button_continue; ?></span></a></div>
                        </div>
                        <?php } ?>
                        <?php echo $content_bottom; ?>
                </div>
        </div>
</div>
<script type="text/javascript"><!--
function display(view) {
	if (view == 'list') {
		$('.product-grid').attr('class', 'product-list');
		
		$('.product-list > div').each(function(index, element) {
			html  = '<div class="right">';
			html += '  <div class="cart">' + $(element).find('.cart').html() + '</div>';
			html += '  <div class="wishlist">' + $(element).find('.wishlist').html() + '</div>';
			html += '  <div class="compare">' + $(element).find('.compare').html() + '</div>';
			html += '</div>';			
			
			html += '<div class="left">';
			
			var image = $(element).find('.image').html();
			
			if (image != null) { 
				html += '<div class="image">' + image + '</div>';
			}
			
			var price = $(element).find('.price').html();
			
			if (price != null) {
				html += '<div class="price">' + price  + '</div>';
			}
					
			html += '  <div class="name">' + $(element).find('.name').html() + '</div>';
			html += '  <div class="description">' + $(element).find('.description').html() + '</div>';
			
			var rating = $(element).find('.rating').html();
			
			if (rating != null) {
				html += '<div class="rating">' + rating + '</div>';
			}
				
			html += '</div>';

						
			$(element).html(html);
		});		
		
		$('.display').html('<b><?php echo $text_display; ?></b> <?php echo $text_list; ?> <b>/</b> <a onclick="display(\'grid\');"><?php echo $text_grid; ?></a>');
		
		$.cookie('display', 'list'); 
	} else {
		$('.product-list').attr('class', 'product-grid');
		
		$('.product-grid > div').each(function(index, element) {
			html = '';
			
			var image = $(element).find('.image').html();
			
			if (image != null) {
				html += '<div class="image">' + image + '</div>';
			}
			
			html += '<div class="name">' + $(element).find('.name').html() + '</div>';
			html += '<div class="description">' + $(element).find('.description').html() + '</div>';
			
			var price = $(element).find('.price').html();
			
			if (price != null) {
				html += '<div class="price">' + price  + '</div>';
			}
			
			var rating = $(element).find('.rating').html();
			
			if (rating != null) {
				html += '<div class="rating">' + rating + '</div>';
			}
						
			html += '<div class="cart">' + $(element).find('.cart').html() + '</div>';
			html += '<div class="wishlist">' + $(element).find('.wishlist').html() + '</div>';
			html += '<div class="compare">' + $(element).find('.compare').html() + '</div>';
			
			$(element).html(html);
		});	
					
		$('.display').html('<b><?php echo $text_display; ?></b> <a onclick="display(\'list\');"><?php echo $text_list; ?></a> <b>/</b> <?php echo $text_grid; ?>');
		
		$.cookie('display', 'grid');
	}
}

view = $.cookie('display');

if (view) {
	display(view);
} else {
	display('list');
}
//--></script> 
<script type="text/javascript">
        //<![CDATA[
                $("#default-usage-select").selectbox();
        //]]>
</script>
<?php echo $footer; ?>