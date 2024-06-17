<?php
$dt = new DateTime('America/New_York');
$dt_format = $dt->format('d-m');

$valentines = (in_array($dt_format, array('14-02')) ? true: false);
if($valentines) {
	?>
	<div>
		<div class="cart-heading active"><?php echo $heading_title; ?></div>
		<div class="cart-content" id="coupon"><?php echo $entry_coupon; ?>&nbsp;
			<input type="text" name="coupon" value="<?php echo $coupon; ?>" />
			&nbsp;<a id="button-coupon" class="button"><span><?php echo $button_coupon; ?></span></a><span id="couponerr"></span></div>
	</div>
	<script type="text/javascript"><!--
		$('#button-coupon').bind('click', function() {
			$.ajax({
				type: 'POST',
				url: 'index.php?route=total/coupon/calculate',
				data: $('#coupon :input'),
				dataType: 'json',
				beforeSend: function() {
					$('.success, .warning').remove();
					$('#button-coupon').attr('disabled', true);
					$('#button-coupon').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
				},
				complete: function() {
					$('#button-coupon').attr('disabled', false);
					$('.wait').remove();
				},
				success: function(json) {
					if (json['error']) {
						$('#couponerr').html(json['error']);
					}

					if (json['redirect']) {
						location = json['redirect'];
					}
				}
			});
		});
		//--></script>
<?php
}
?>