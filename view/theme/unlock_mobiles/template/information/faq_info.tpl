<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
<div class="top_content">
            <div class="content_top">
				<div class="description" style="max-height: 5000px;">
  <h1><?php echo $heading_title; ?></h1>
  <?php echo $description; ?>
  <?php if ($faqs) { ?>
  <div class="faq-list">
    <div class="faqs-content">
		<div class="faq-block">
    <?php foreach ($faqs as $faq) { ?>
			<div>
			<div class="faq-heading" ><?php echo $faq['title']; ?></div>
			<div class="faq-content" id="<?php echo $faq['faq_id']; ?>"><?php echo $faq['description']; ?></div>
			</div>
    <?php } ?>
		</div>
    </div>
  </div>
  <div class="pagination"><?php echo $pagination; ?></div>
  <?php } else { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><span><?php echo $button_continue; ?></span></a></div>
  </div>
  <?php }?>
		</div>
    </div>
    </div>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$('.faq-block .faq-heading').bind('click', function() {
	$(".faq-content").slideUp("slow");
	$(".faq-heading").removeClass('active');

	if ($(this).parent().find('.faq-content').is(":visible")) {
		$(this).parent().find('.faq-content').slideUp('slow');
	} else {
		$(this).parent().find('.faq-content').slideDown('slow');
		$(this).addClass('active');
	}
});
//--></script>
<?php echo $footer; ?>