<?php echo $header; ?>
<?php if(isset($ga)): ?>
    <script>
        ga('require', 'ecommerce');
        ga('ecommerce:addTransaction', {
            'id': '<?php echo $ga['id']; ?>',
            'revenue': '<?php echo $ga['total']; ?>'
        });

        <?php foreach($ga['products'] as $product): ?>
        <?php
            $name = $product['name'];
            $name = explode(" -", $name);
            $name = $name[0];
        ?>
        ga('ecommerce:addItem', {
            'id': '<?php echo $ga['id']; ?>',
            'name': '<?php echo $name; ?>',
            'sku': '<?php echo $product['product_id']; ?>',
            'category': '<?php echo $product['carrier'] ?>',
            'price': '<?php echo $product['price'] ?>',
            'quantity': '<?php echo $product['quantity'] ?>'
        });
        <?php endforeach ?>
        ga('ecommerce:send');
    </script>
<?php endif; ?>
<?php echo $column_left; ?>
<?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
        <div class="top_content">
                <div class="content_top">
                    <div class="description">
                           <!-- <h1><?php echo $heading_title; ?></h1>-->
                            <?php echo $text_message; ?>
                            
							<div id="promotion">
							<?php //Get Promotion Order Success Information Page
								$query = $this->db->query("SELECT information_id, description FROM " . DB_PREFIX . "information_description WHERE information_id = '28' AND language_id = " . (int)$this->config->get('config_language_id'));
								if ($query->num_rows) {
									echo  html_entity_decode($query->row['description'], ENT_QUOTES, 'UTF-8');
								}
							?>	
                    </div>
                    
                </div>
        </div>
  
  <?php echo $content_bottom; ?>
<script type="text/javascript">
	function email_subscribe(){
	$.ajax({
			type: 'post',
			url: 'index.php?route=module/newslettersubscribe/promosubscribe',
			dataType: 'html',
            data:$("#subscribe").serialize(),
			beforeSend: function() {
			$('#subscribe_email').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
			},
			complete: function() {
				$('.wait').remove();
			},
			success: function (html) {
				eval(html);
			}}); 
	}
</script>
</div>
<?php echo $footer; ?>