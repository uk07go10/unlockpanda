<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
        <?php echo $content_top; ?>
        <div class="top_content">
            <div class="content_top">
				<!--<img src="<?php echo $this->model_tool_image->resize('data/banner.png', 882, 380) ?>" alt="Banner"/>-->
            </div>
        </div>
        <div id="content_page">
            <div class="content_top">
                <div>
                    <h1><?php echo $heading_title; ?></h1>
					<form id="order_status_form" onsubmit="return false;">
					<p style="margin-left:30px; font-family: Tahoma; font-size: 14px;"><?php echo $text_insert; ?><br /><?php echo $text_note; ?></p>
					<br/>
						<input type="text" name="order_id" value="" id="order_id" class="round_corners_small" style="margin-left: 30px;border: 1px solid #4B2E71; width: 130px; padding: 7px;" />
					</form>
					<input class="round_corners" type="submit" name="check_status" value="<?php echo $button_continue; ?>" id="check_status" />
                </div>
				<br/><br/>
                <div id="results">
                </div>
            </div>
        </div>
        <?php echo $content_bottom; ?>
</div>
<script type="text/javascript"><!--//
	$('#check_status').live('click', function(){
		$.ajax({
			url: '<?php echo $action ?>',
			data: $('#order_status_form').serialize(),
			dataType: 'json',
			type: 'POST',
			success: function(json){
				if(json == 'Order not found'){
					$('#results').html('<div class="warning">Order with id \'<b>'+ $('#order_id').val() +'</b>\' was not found! Please make sure you have the right id.</div>');
				} else{
					
					comment = (json['comment'] != null) ? '('+ json['comment'] + ')' : '';
					comment = comment.replace(/(\r\n|\n\r|\r|\n)/g, "<br>");
					$('#results').html('<div class="success">Order with id \'<b>'+ $('#order_id').val() +'</b>\' is \'<b>'+ json['name'] +'</b>\'. <br>'+ comment +'</div>');
				}
			}
		})
	})
//--></script>
<?php echo $footer; ?> 