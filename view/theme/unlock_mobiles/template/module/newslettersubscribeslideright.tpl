<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/fancybox/newslettersubscribesideboxright.css" media="screen" />


<div class="panelrightsubscribeslideright">
 <div align="center">




 <form name="subscribeslideright" id="subscribeslideright"   >
<table id="subscribeslideright" border="0" cellpadding="0" cellspacing="0" style="width: 419px;"><tbody>
<tr><td><img alt="" src="<?php echo $popupheaderimage; ?>" align="middle" style="width: 419px; height: 172px;" /></td></tr>
<tr><td><p style="text-align: center;"><span style="color:#c2c2c2;"><?php echo $popupline1; ?><br /><?php echo $popupline2; ?></span></p></td></tr>
<tr>

<td>

     <P ALIGN="CENTER"><b><?php echo $entry_email; ?></b><br /><input type="text" size="30" value="" name="subscribe_email" id="subscribe_email">
   

<br /><b><?php echo $entry_name; ?></b><br /><input type="text" size="30" value="" name="subscribe_name" id="subscribe_name"> 
<br />


   <?php 
     for($ns=1;$ns<=$option_fields;$ns++) {
     $ns_var= "option_fields".$ns;
   ?>
   
      <?php 
       if($$ns_var!=""){
         echo("<b>".$$ns_var."&nbsp;</b>");

         echo('</br><input type="text" value="" size="30" name="option'.$ns.'" id="option'.$ns.'"><br />');
       
}
      ?>
     
   
   <?php 
     }
   ?>
</td>
</tr>





   <tr>
     <td align="center">
<a class="button2" onclick="email_subscribeslr()"><span><?php echo $entry_button; ?></span></a><?php if($option_unsubscribe) { ?>
          <a class="button2" onclick="email_unsubscribeslr()" style="margin-top:4px;"><span><?php echo $entry_unbutton; ?></span></a>
      <?php } ?>    
         
 


     <P ALIGN="CENTER" id="subscribe_result"></p></td>
   </tr>

</tbody></table>  </form>

 




</div></div>
<a class="triggerrightsubscribeslideright" href="#"><?php echo $heading_title; ?></a>
<script language="javascript">
	
function email_subscribeslr(){
	$.ajax({
			type: 'post',
			url: 'index.php?route=module/newslettersubscribe/subscribe',
			dataType: 'html',
            data:$("#subscribeslideright").serialize(),
			success: function (html) {
				eval(html);
			}}); 
}
function email_unsubscribeslr(){
	$.ajax({
			type: 'post',
			url: 'index.php?route=module/newslettersubscribe/unsubscribe',
			dataType: 'html',
            data:$("#subscribeslideright").serialize(),
			success: function (html) {
				eval(html);
			}}); 
}
     
</script>




<script type="text/javascript">
$(document).ready(function(){
	$(".triggerrightsubscribeslideright").click(function(){
		$(".panelrightsubscribeslideright").toggle("fast");
		$(this).toggleClass("active");
		return false;
	});
});
</script>




