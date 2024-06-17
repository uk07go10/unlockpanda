<?php if (isset($_SERVER['HTTP_USER_AGENT']) && !strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6')) echo '<?xml version="1.0" encoding="UTF-8"?>'. "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<meta property="og:title" content="<?php echo $title?>">
<meta property="og:type" content="website">
<meta property="og:image" content="<?php echo $base ?>image/quick_safe_legal.png">
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<meta property="og:description" content="<?php echo $description; ?>">
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/unlock_mobiles/stylesheet/stylesheet.css" />
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<link href='//fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui.min.js"></script>
<!--<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />-->
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/external/jquery.cookie.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.pack.js"></script>


<!--[if IE]>
<script type="text/javascript" src="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4-iefix.js"></script>
<![endif]--> 

<script type="text/javascript" src="catalog/view/javascript/common.js"></script>

<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie7.css" />
<![endif]-->
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie6.css" />
<script type="text/javascript" src="catalog/view/javascript/DD_belatedPNG_0.0.8a-min.js"></script>
<script type="text/javascript">
DD_belatedPNG.fix('#logo img');
</script>
<![endif]-->
<?php echo $google_analytics; ?>
<?php echo $zendesk; ?>
<?php echo $crazyegg; ?>
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
</head>
<body>
<div id="container">
<div id="header">
        <?php if ($logo) { ?>
        <div id="logo"><a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a></div>
        <?php } ?>
        <?php if (count($languages) > 1) { ?>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
            <div id="language"><?php echo $text_language; ?><br />
            <?php foreach ($languages as $language) { ?>
            &nbsp;<img width="16px" height="11px" src="image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" onclick="$('input[name=\'language_code\']').attr('value', '<?php echo $language['code']; ?>').submit(); $(this).parent().parent().submit();" />
            <?php } ?>
            <input type="hidden" name="language_code" value="" />
            <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
            </div>
        </form>
        <?php } ?>
		<div id="trustpilot">
			<img style="margin-top: -10px;" src="<?php echo $this->model_tool_image->resize('ssl-security-badge.png', 120, 120) ?>"/>
		</div>
		<div id="moneyback">
			<img width="103px" height="103px" src="<?php echo $base . 'image/data/moneyback.png' ?>" alt="" />
		</div>
        <div id="cart">
           <div class="heading">
				<div class="ycart"><?php echo $text_cart; ?><a href="<?php echo $cart; ?>"><span id="cart_total"><?php echo $text_items; ?></span></a></div>
				<div class="fcart"><a href="<?php echo $cart ?>"><?php echo $text_checkout; ?></a></div>
			</div>
        </div>
        
        <div class="links">
            <style>
                #header .links a {
                    padding: 11px 7px !important;;
                }
            </style>
            <a href="<?php echo $home; ?>" class="first"><?php echo $text_home; ?></a>
            <a href="<?php echo $how_it_works; ?>" id="wishlist_total"><?php echo $text_howitworks; ?></a>
            <!--<a href="javascript: void(0);" id="show_manufacturers"><?php echo 'Manufacturers'; ?></a>-->
            <a href="<?php echo $this->url->link('product/testimonial', '', 'SSL'); ?>"><?php echo $text_testimonials; ?></a>
			<!--<a href="<?php echo $contact; ?>"><?php echo 'Contact us'; ?></a>-->
			<a href="<?php echo $this->url->link('information/faq', '', 'SSL'); ?>"><?php echo $text_faq; ?></a>
			<a href="<?php echo $this->url->link('information/orderstatus', '', 'SSL'); ?>"><?php echo $text_orderstatus; ?></a>
			<a href="<?php echo $this->url->link('information/information&information_id=8'); ?>"><?php echo $text_codeintructions; ?></a>
			<a href="<?php echo $this->url->link('information/information&information_id=21'); ?>"><?php echo $text_troubleshooting; ?></a>
			<a href="https://www.unlockriver.com/blog/">BLOG</a>
			<!--<a href="worldwide-iphone-unlock" style="text-transform:none;!important;"><?php echo 'iPHONE HARDWARE UNLOCK'; ?></a>-->
        </div>
        <div id="menu" style="display: none">
            <ul>
                <?php if ($categories) { ?>
                        <?php foreach ($categories as $category) { ?>
                        <li>
                            <a href="<?php echo $category['href']; ?>" title="<?php echo $category['fullname'] ?>"><?php echo $category['name']; ?></a>
                        </li>
                        <?php } ?>
                <?php }else{ ?>
                        <li>No Manufacturers  </li>
                <?php }?>
            </ul>
        </div>
</div>

    <?php
        $dt = new DateTime('America/New_York');
        $dt_format = $dt->format('d-m');

        $valentines = (in_array($dt_format, array('14-02')) ? true: false);
    ?>
    <?php if($valentines): ?>
        <?php if($this->session->data['language'] == 'en'): ?>
            <img src="download/valentines_en.png" style="width: 980px; margin-bottom: -3px;">
        <?php else: ?>
            <img src="download/valentines_es.png" style="width: 980px; margin-bottom: -3px;">
        <?php endif; ?>
    <?php endif; ?>
