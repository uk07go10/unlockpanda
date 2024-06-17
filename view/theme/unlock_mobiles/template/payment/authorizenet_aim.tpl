<h2><?php echo $text_credit_card; ?></h2>
<div id="payment_authorize">
  <table class="form" style="margin-bottom: 0px;">
    <tr>
          <td><?php echo 'Email:'; ?></td>
          <td><input type="email" id="payer_email" name="payer_email" value="" /></td>
      </tr>
    <tr>
      <td><?php echo $entry_cc_owner; ?></td>
      <td><input type="text" name="cc_owner" value="" id="cc_owner" /></td>
    </tr>
    <tr>
      <td><?php echo $entry_cc_number; ?></td>
      <td><input type="text" name="cc_number" value="" id="cc_number"/></td>
    </tr>
    <tr>
      <td><?php echo $entry_cc_expire_date; ?></td>
      <td><select name="cc_expire_date_month" id="cc_expire_date_month">
          <?php foreach ($months as $month) { ?>
          <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
          <?php } ?>
        </select>
        /
        <select name="cc_expire_date_year" id="cc_expire_date_year">
          <?php foreach ($year_expire as $year) { ?>
          <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
          <?php } ?>
        </select></td>
    </tr>
  </table>
  <div class="clear"></div>
  <div style="border-left: 1px solid #BEBCB7; border-right: 1px solid #BEBCB7; border-bottom: 1px solid #BEBCB7; padding: 4px;">
      <div class="float_left" style="margin: 3px 0 8px;"><?php echo 'Security Code (CVV2):' ?></div>
      <div class="float_right"><input type="text" name="cc_cvv2" value="" size="3" /></div>
	  <div class="clear"></div>
	</div>
        <input type="hidden" name="total" value="" />
</div>
<div class="buttons" style="background: none">
  <div class="right"><a id="confirm_authorize" class="button"><span><?php echo $button_confirm; ?></span></a></div>
</div>
<script type="text/javascript"><!--//
    var total = $('input[name=amount_1]').val();
    $('input[name=total]').val(total);
$('#confirm_authorize').bind('click', function() {
	$.ajax({
		type: 'POST',
		url: 'index.php?route=payment/authorizenet_aim/send',
		data: $('#payment_authorize :input'),
		dataType: 'json',		
		beforeSend: function() {
			$('#confirm_authorize').attr('disabled', true);
			
			$('#payment_authorize').before('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		success: function(json) {
        alert('success');
            $('#payer_email').css('background-color', 'none');
            $('#cc_owner').css('background-color', '#fffec9');
            $('#cc_number').css('background-color', '#fffec9');
            $('#cc_expire_date_month').css('background-color', '#fffec9');
            $('#cc_expire_date_year').css('background-color', '#fffec9');
			if (json['error']) {
                if(json['error']['payer_email']) {
                    $('#payer_email').css('background-color', '#fffec9');
                }
                if(json['error']['cc_owner']) {
                    $('#cc_owner').css('background-color', '#fffec9');
                }
                    if(json['error']['cc_number']) {
                    $('#cc_number').css('background-color', '#fffec9');
                }
                    if(json['error']['cc_expire_date_month']) {
                    $('#cc_expire_date_month').css('background-color', '#fffec9');
                }
                    if(json['error']['cc_expire_date_year']) {
                    $('#cc_expire_date_year').css('background-color', '#fffec9');
                }
				
				$('#confirm_authorize').attr('disabled', false);
			}
			
			$('.attention').remove();
			
			if (json['success']) {
				location = json['success'];
			}
		}
	});
});
//--></script>