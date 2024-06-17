<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
        <?php echo $content_top; ?>
		<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	  </div>
        <div id="content_page">
            <div class="content_top">
                <div>
                    <h1><?php echo $heading_title; ?></h1>
					<?php if($flash) { ?>
					<div class="<?php echo $flash['type']; ?>">
						<?php echo $flash['content']; ?>
					</div>
					<?php } ?>
					<form id="order_status_form" onsubmit="return false;">
					<p style="margin-left:30px;"><?php echo $text_insert; ?><br /><?php echo $text_note; ?></p>
					<br/>
						<input type="text" name="order_id" value="" id="order_id" class="round_corners_small" style="margin-left: 30px;" />
					</form>
					<a class="button" name="check_status" id="check_status" /><?php echo $button_continue; ?></a>
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
					$('#results').html('<div class="success">Order with id \'<b>'+ $('#order_id').val() +'</b>\' is \'<b>'+ json['name'] +'</b>\'. '+ comment +'</div>');
				}
			}
		})
	})
//--></script>
<?php echo $footer; ?> 