<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<h1><?php echo $heading_title; ?></h1>
	<div><?php // echo $page_content['heading_content']; ?></div>
	<?php if ($testimonials) { ?>
		<div class="hreview-aggregate" style="display: none;">
			<span class="type">business</span>
			<div class="item vcard">
				<a class="url fn org" href="<?php echo $website['website']; ?>"><?php echo $website['company']; ?></a>
				<div class="tel"><?php echo $website['telephone']; ?></div>
				<div class="adr">
					<div class="street-address"><?php echo $website['address']; ?></div>
					<span class="locality"><?php echo $website['city']; ?></span>
					<span class="region"><?php echo $website['state']; ?></span>, <span class="postal-code"><?php echo $website['postal_code']; ?></span>
					<div class="country-name"><?php echo $website['country']; ?></div>
				</div>
			   <span class="rating">
				  <span class="average"><?php echo $website['rating']; ?></span> out of <span class="best">5</span>
			   </span>
			   based on <span class="count"><?php echo $website['total']; ?></span> reviews.
			</div>
		</div>
	<?php } ?>
	<div class="buttons">
		<div class="right">
		<a href="index.php?route=product/testimonial#write" class="button"><span><?php echo 'Write Testimonials'; ?></span></a>
		</div>
	</div>
	<div class="middle">
		<?php if ($testimonials) { ?>
			<?php foreach ($testimonials as $testimonial) { ?>
				<table class="content" width="100%">
					<tr>
						<td style="text-align: left;">
							<div class="hreview">
								<span class="summary"><?php echo $testimonial['title']; ?></span><?php if ($testimonial['rating']) { ?>
									<abbr class="rating" title="<?php echo $testimonial['rating'] ?>"></abbr>

										<span><img src="catalog/view/theme/default/image/stars-<?php echo $testimonial['rating'] . '.png'; ?>" alt="<?php echo $testimonial['rating_text']; ?>"/></span>
									<?php } ?>
								<blockquote class="blockquote-reverse">
								<p class="testimonialp">
									<?php echo $testimonial['description']; ?>
								</p>
									<footer>
									</footer>
								</blockquote>
							</div>
						</td>
					</tr>
				</table>
			<?php } ?>
			<?php if ( isset($pagination)) { ?>
				<div class="pagination"><?php echo $pagination;?></div>
			<?php }?>
		<?php } ?>
	</div>
	<?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>