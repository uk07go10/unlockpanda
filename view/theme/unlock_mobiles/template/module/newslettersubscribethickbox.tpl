<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/fancybox/newslettersubscribepopup.css" media="screen" />
<style>
#fancybox-wrap {
z-index: 999999 !important;
}
</style>
<div class="box">

  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content" style="text-align: center;">

<a href="#subscribeformthickbox" title="Newsletter Subscribe" class="fancybox"><?php echo($text_subscribe); ?> </a>
 
   </div>
</div>

<script type="text/javascript" src="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.pack.js">
</script><link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

<div style="display:none">
<form id="subscribeformthickbox" name="subscribeformthickbox" method="post" action="">
<div class="newspopup">
<img alt="" src="<?php echo $popupheaderimage; ?>" />
<p><?php echo $popupline1; ?><br />
<?php echo $popupline2; ?></p>
<div style="text-align:center"> 
		<input type="text" id="subscribe_name" name="subscribe_name" placeholder="<?php echo $entry_name; ?>">
		
		<input type="text" id="subscribe_email" name="subscribe_email" placeholder="<?php echo $entry_email; ?>">
		</div>
	
 


<p>
<?php 
     for($ns=1;$ns<=$option_fields;$ns++) {
     $ns_var= "option_fields".$ns;
   ?>

      <?php 
       if($$ns_var!=""){
         echo($$ns_var."&nbsp;");

         echo('<input type="text" value="" size="50" name="option'.$ns.'" id="option'.$ns.'">');
echo "<br />";
       }
      ?>
     
   <?php 
     }
   ?>
   
</p><div style="text-align:center"> 
		
		<input type="submit" value="<?php echo $entry_button; ?>">
</div>


</div>




</form>
</div>


<script type="text/javascript">

$('.fancybox').fancybox({   'scrolling'        : 'no',   'titleShow'        : false  });


 



$("#subscribeformthickbox").bind("submit", function() {
  $.post('<?php echo $home; ?>', { subscribe_email: $('#subscribeformthickbox input[name="subscribe_email"]').val(), subscribe_name: $('#subscribeformthickbox input[name="subscribe_name"]').val()}, function(data) {
      if (data) {
        if (data.type == 'success') {
          $('#subscribeformthickbox input[name="subscribe_email"]').val('');
          $('#subscribeformthickbox input[name="subscribe_name"]').val('');
        }
        $('#subscribeformthickbox').before('<div class="' + data.type + '">' + data.message + '</div>');
        $('div.' + data.type).delay(3000).slideUp(400, function(){if($(this).hasClass('success')){$.fancybox.close();}$(this).remove();});
      } else {
        $('#subscribeformthickbox input[name="subscribe_email"]').focus();
      }
    }, "json");

  return false;
});



</script>









