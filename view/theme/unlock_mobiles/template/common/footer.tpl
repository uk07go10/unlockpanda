<div id="footer">
        <div class="content_top">
            <div class="column firstcolumn">
				 <h3><?php echo $text_information; ?></h3>
                <ul>
					<li><a href="https://www.unlockriver.com/blog/">Blog</a></li>
                    <?php foreach ($informations as $information) { ?>
                        <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
                    <?php } ?>
					<li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
                    <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
                </ul>
            </div>
            <div class="column">
				<h3><?php echo $text_extra; ?></h3>	
				<div id="follow_us">  
				<a target="_blank" class="tiptip" href="https://www.facebook.com/UnlockRiver"><img width="36px" height="36px" title="Facebook" alt="Facebook" src="catalog/view/theme/unlock_mobiles/image/follow_us/f_logo.png"></a>
				<a target="_blank" class="tiptip" href="https://twitter.com/RiverUnlock"><img width="36px" height="36px" title="Twitter" alt="Twitter" src="catalog/view/theme/unlock_mobiles/image/follow_us/t_logo.png"></a>
				<a target="_blank" class="tiptip" href="https://plus.google.com/u/0/b/117821364857551097551/117821364857551097551/about"><img width="36px" height="36px" title="Google+" alt="Google+" src="catalog/view/theme/unlock_mobiles/image/follow_us/g_logo.png"></a>
				<a target="_blank" class="tiptip" href="https://pinterest.com/unlockrivercom/"><img width="36px" height="36px" title="Pinterest" alt="Pinterest" src="catalog/view/theme/unlock_mobiles/image/follow_us/p_logo.png"></a>			   
<!--				<a target="_blank" class="tiptip" href="https://www.youtube.com/user/UnlockBlackberryFast"><img width="36px" height="36px" title="YouTube" alt="YouTube" src="catalog/view/theme/unlock_mobiles/image/follow_us/y_logo.png"></a>-->
				</div>
            </div>
            <!--<div class="column float_right">
                <img style="margin-top: 20px;" src="<?php echo $this->model_tool_image->resize('data/sitelock.png', 164, 98); ?>" alt="SiteLock" />
            </div>-->
			<div class="column float_right lastcolumn">
				<h3><?php echo $text_service; ?></h3>
					<ul>
					<li><a href="mailto:<?php echo $this->config->get('config_email'); ?>"><?php echo $this->config->get('config_email'); ?></a></li>	
					<li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
					<li><a href="index.php?route=information/wholesale"><?php echo $text_wholesale; ?></a></li>
                    <li><a href="index.php?route=information/faq"><?php echo $text_codenot; ?></a></li>
					</ul>
            </div>
        </div>
		<div id="footer_cr">
		<div class="container">
		<div class="row" id="footer_cr_content">
			<div class="span4">
			<span class="h3"><?php echo $text_accept; ?></span>
			<div id="payment_logos">
				<img width="53px" height="31px" title="PayPal" alt="PayPal" src="catalog/view/theme/unlock_mobiles/image/payment/payment_image_paypal.png">
				<img title="Visa" alt="Visa" src="catalog/view/theme/unlock_mobiles/image/payment/payment_image_visa.png">
				<img width="53px" height="31px" title="MasterCard" alt="MasterCard" src="catalog/view/theme/unlock_mobiles/image/payment/payment_image_mastercard.png">
				<img width="53px" height="31px" title="American Express" alt="American Express" src="catalog/view/theme/unlock_mobiles/image/payment/payment_image_american_express.png">			  
				<img width="92px" height="32px" title="BitCoin" alt="BitCoin" src="catalog/view/theme/unlock_mobiles/image/payment/payment_image_bitcoin.png">
				<img width="95px" height="32px" title="AliPay" alt="AliPay" src="catalog/view/theme/unlock_mobiles/image/payment/payment_image_alipay.png">
			</div>
			</div>

		</div>
		</div>
		</div>
		<div id="powered">
			<span style="float:left;"><?php echo $powered; ?></span><span style="float:right;"><a href="http://www.unlockriver.com">UnlockRiver.com</a></span>
		</div>
</div>

</div>

<script type="text/javascript" src="catalog/view/javascript/combined.js"></script>

<!-- begin olark code --><script data-cfasync="false" type='text/javascript'>/*{literal}<![CDATA[*/
window.olark||(function(c){var f=window,d=document,l=f.location.protocol=="https:"?"https:":"http:",z=c.name,r="load";var nt=function(){f[z]=function(){(a.s=a.s||[]).push(arguments)};var a=f[z]._={},q=c.methods.length;while(q--){(function(n){f[z][n]=function(){f[z]("call",n,arguments)}})(c.methods[q])}a.l=c.loader;a.i=nt;a.p={0:+new Date};a.P=function(u){a.p[u]=new Date-a.p[0]};function s(){a.P(r);f[z](r)}f.addEventListener?f.addEventListener(r,s,false):f.attachEvent("on"+r,s);var ld=function(){function p(hd){hd="head";return["<",hd,"></",hd,"><",i,' onl' + 'oad="var d=',g,";d.getElementsByTagName('head')[0].",j,"(d.",h,"('script')).",k,"='",l,"//",a.l,"'",'"',"></",i,">"].join("")}var i="body",m=d[i];if(!m){return setTimeout(ld,100)}a.P(1);var j="appendChild",h="createElement",k="src",n=d[h]("div"),v=n[j](d[h](z)),b=d[h]("iframe"),g="document",e="domain",o;n.style.display="none";m.insertBefore(n,m.firstChild).id=z;b.frameBorder="0";b.id=z+"-loader";if(/MSIE[ ]+6/.test(navigator.userAgent)){b.src="javascript:false"}b.allowTransparency="true";v[j](b);try{b.contentWindow[g].open()}catch(w){c[e]=d[e];o="javascript:var d="+g+".open();d.domain='"+d.domain+"';";b[k]=o+"void(0);"}try{var t=b.contentWindow[g];t.write(p());t.close()}catch(x){b[k]=o+'d.write("'+p().replace(/"/g,String.fromCharCode(92)+'"')+'");d.close();'}a.P(2)};ld()};nt()})({loader: "static.olark.com/jsclient/loader0.js",name:"olark",methods:["configure","extend","declare","identify"]});
/* custom configuration goes here (www.olark.com/documentation) */
olark.identify('5068-885-10-2715');/*]]>{/literal}*/</script><noscript><a href="https://www.olark.com/site/5068-885-10-2715/contact" title="Contact us" target="_blank">Questions? Feedback?</a> powered by <a href="https://www.olark.com?welcome" title="Olark live chat software">Olark live chat software</a></noscript><!-- end olark code -->

</body></html>