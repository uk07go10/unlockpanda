<div class="slideshow">
  <div id="slideshow<?php echo $module; ?>" class="nivoSlider" style="width: <?php echo $width; ?>px; height: <?php echo $height; ?>px;">
    <?php foreach ($banners as $banner) { ?>
    <?php if ($banner['link']) { ?>
    <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" /></a>
    <?php } else { ?>
    <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" />
    <?php } ?>
    <?php } ?>
  </div>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#slideshow<?php echo $module; ?>').nivoSlider({
		effect:'fade', //Specify sets like: 'fold,fade,sliceDown'
		slices:5,
		animSpeed:500, //Slide transition speed
		pauseTime:5000,
		startSlide:0, //Set starting Slide (0 index)
		directionNav:false, //Next &amp; Prev
		directionNavHide:false, //Only show on hover
		controlNav:false, //1,2,3...
		controlNavThumbs:true, //Use thumbnails for Control Nav
        	controlNavThumbsFromRel:false, //Use image rel for thumbs
		controlNavThumbsSearch: '.jpg', //Replace this with...
		controlNavThumbsReplace: '_thumb.jpg', //...this in thumb Image src
		keyboardNav:true, //Use left &amp; right arrows
		pauseOnHover:true, //Stop animation while hovering
		manualAdvance:false, //Force manual transitions
		captionOpacity:1.0, //Universal caption opacity
		beforeChange: function(){},
		afterChange: function(){},
		slideshowEnd: function(){} //Triggers after all slides have been shown
	});
});
--></script>