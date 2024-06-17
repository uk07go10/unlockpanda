<?php echo $header; ?>
<?php echo $column_left; ?>
<?php // echo $column_right; ?>
<!--<script type="text/javascript" src="catalog/view/javascript/jquery/jquery.prettyPhoto.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/unlock_mobiles/stylesheet/prettyPhoto.css" media="screen" />-->
<div id="content">
    <?php // echo $content_top; ?>
<!--<h1 style="display: none;"><?php echo $heading_title; ?></h1>-->
    <div class="top_content">
        <div class="content_top">
            <?php echo $content_top; ?>
<!--             <img src="<?php echo $this->model_tool_image->resize('data/banner.png', 882, 380) ?>" alt="Banner"/>-->
        </div>
    </div>
    <div id="content_page">
        <div class="content_top">
            <div class="float_left">
                    <div class="gallery clearfix">
                            <a href="<?php echo 'http://www.youtube.com/watch?v=frQGF-Jnp6k' ?>" rel="prettyPhoto[movies]" title="">
                                    <img src="<?php echo 'http://img.youtube.com/vi/frQGF-Jnp6k/0.jpg' ?>" width="180" height="130" alt="" />
                            </a>
                    </div>			
            </div>
            <div class="float_left" style="margin-left: 30px; width: 670px; text-align: justify">
                <h1>Cell Phone Unlocking Fully Explained</h1>  
                <p>
                    Unlocking your Phone online has never been so easy! Cheapest prices & Best Customer Service guaranteed on every purchase. What is it? Remote Unlocking your cell phone allows you to use it on any GSM network in the US, Canada, and Overseas! The process of Mep unlocking is totally safe, legal & will not void your warranty. Once unlocked, you have the freedom to switch GSM carriers whenever you like, all while keeping the same phone.
                </p>
            </div>
            <div class="clear"></div>
            
            <div style="width: 750px; margin-top: 30px;" ><img src="<?php echo $this->model_tool_image->resize('data/steps.png', 726, 59) ?>" alt="" /></div>
            <div class="box">
                <div class="home_instructions">
                    <div class="column float_left">
                        <h2>Simple Instructions</h2>
                        <div class="float_left information_content">
                            Our instructions are easy to follow and only require that you be able to enter the code unlocking number on your keypad. If you can dial a telephone number, you can unlock via code.
                        </div>
                        <img src="<?php echo $this->model_tool_image->resize('data/instr1.jpg', 70, 70) ?>" alt="" />
                    </div>
                    <div class="column float_left">
                        <h2>Fast Checkout</h2>
                        <div class="float_left information_content">
                            We have been perfecting our process for the last seven years to make sure that you have the most time-efficient and hassle free experience possible. Our turnaround times are the best in the industry.
                        </div>
                        <img src="<?php echo $this->model_tool_image->resize('data/instr2.jpg', 70, 70) ?>" alt="" />
                    </div>
                    <div class="column float_left">
                        <h2>Over 2 Million Unlocks</h2>
                        <div class="float_left information_content">
                            We unlocked over 2 million mobile phones in 165 countries. By combining a reliable product with easy to follow instructions, we have arrived at a proven formula.
                        </div>
                        <img src="<?php echo $this->model_tool_image->resize('data/instr3.jpg', 70, 70) ?>" alt="" />
                    </div>
                    <div class="column float_left">
                        <h2>What else i should know?</h2>
                        <div class="float_left information_content">
                            Once you Unlock your phone it will never re-lock.Unlocking does not void your warranty.No technical knowledge needed.Unlocking will not damage your phone.
                        </div>
                        <img src="<?php echo $this->model_tool_image->resize('data/instr4.jpg', 70, 70) ?>" alt="" />
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
            <?php echo $content_bottom; ?>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div> 
<div class="clear"></div>
<script type="text/javascript" charset="utf-8">    
		$(document).ready(function(){        
			$("area[rel^='prettyPhoto']").prettyPhoto();
			$(".gallery:first a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square',slideshow:5000, autoplay_slideshow: false });
			$(".gallery:gt(0) a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'fast',slideshow:10000, hideflash: true });
	});
	</script>
<?php echo $footer; ?>