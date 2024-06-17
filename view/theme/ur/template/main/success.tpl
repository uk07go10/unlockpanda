<!DOCTYPE html><html class="no-js" lang="en"><head><?= $scripts ?>
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
<?php endif; ?><script>fbq('track', 'Purchase', {
    value: '<?= $ga['total'] ?>',
    currency: 'USD'
});
</script></head></html><body><?= isset($header) ? $header : "" ?>
<?= isset($header_form) ? $header_form : "" ?><section class="page-wrapper mt-50" id="page-content"><!-- SHOP SECTION START--><div class="shop-section mb-80"><div class="container" style="color: #434343 !important"><div class="row"><div class="col-md-12"><?= $text_message; ?></div></div><div class="row"><div class="col-md-12"><?= $text_promo; ?></div></div></div></div><!-- SHOP SECTION END--><?= $footer ?>  </section></body>