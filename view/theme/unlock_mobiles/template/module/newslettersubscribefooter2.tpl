<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/fancybox/newslettersubscribefooter2.css" media="screen" />



<div class="footer-container">
    <div class="footer">
        <div class="footer-top">
            <div class="newsletter">
    <form name="subscribef2" id="subscribef2"   >

        <div class="block-content">
            <h5><?php echo $heading_title; ?></h5>
            <div class="input-box">

<input class="input-text" id="subscribe_name" name="subscribe_name" size="30" onblur="if(this.value=='')this.value=this.defaultValue;" onfocus="if(this.value==this.defaultValue)this.value='';" type="text" value="<?php echo $entry_name; ?>" />
<input class="input-text" id="subscribe_email" name="subscribe_email" size="30" onblur="if(this.value=='')this.value=this.defaultValue;" onfocus="if(this.value==this.defaultValue)this.value='';" type="text" value="<?php echo $entry_email; ?>" />
</div>
            <div class="actions">
<a class="button" onclick="email_subscribefooter2()"><span><?php echo $entry_button; ?></span></a>
<b align="center" id="subscribef_result"></b>
                        

 </div>
        </div>
    </form>
   
</div>

   
 
<script language="javascript">
	function email_subscribefooter2(){
	$.ajax({
			type: 'post',
			url: 'index.php?route=module/newslettersubscribe/subscribefooter',
			dataType: 'html',
            data:$("#subscribef2").serialize(),
			success: function (html) {
				eval(html);
			}}); 
}

</script>
        
        </div>
