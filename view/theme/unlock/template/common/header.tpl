<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>?v=3" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link href='//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,700,600,800' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="catalog/view/theme/unlock/stylesheet/bootstrap.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/unlock/stylesheet/stylesheet.css" />
<link rel="stylesheet" href="catalog/view/theme/unlock/stylesheet/font-awesome.css" media="screen"/>
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.11.2.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.js"></script>
<!--<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui.min.js"></script>-->
    <script   src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"   integrity="sha256-xNjb53/rY+WmG+4L6tTl9m6PpqknWZvRt0rO1SRnJzw="   crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.11.4/themes/blitzer/jquery-ui.css" />
<!--<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />-->
<script type="text/javascript" src="catalog/view/javascript/common.js"></script>
<script type="text/javascript" src="catalog/view/javascript/combined.js"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<!--[if IE 7]> 
<link rel="stylesheet" type="text/css" href="catalog/view/theme/unlock/stylesheet/ie7.css" />
<![endif]-->
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/unlock/stylesheet/ie6.css" />
<script type="text/javascript" src="catalog/view/javascript/DD_belatedPNG_0.0.8a-min.js"></script>
<script type="text/javascript">
DD_belatedPNG.fix('#logo img');
</script>
<![endif]-->
    <script type="text/javascript" src="catalog/view/javascript/js.cookie.js"></script>
    <script type="text/javascript" src="catalog/view/javascript/fp2.min.js"></script>
<?php echo $zendesk; ?>
<?php if (isset($stores) && $stores) { ?>
<script type="text/javascript"><!--
$(document).ready(function() {
<?php foreach ($stores as $store) { ?>
$('body').prepend('<iframe src="<?php echo $store; ?>" style="display: none;"></iframe>');
<?php } ?>
});
//--></script>
<?php } ?>
<?php echo $google_analytics; ?>
</head>
<body>
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
          fjs.parentNode.insertBefore(js, fjs);
         }(document, 'script', 'facebook-jssdk'));
    </script>
    <div style="background-color: #EDEEEF;">
        <div class="pd_header" ><!--pd_header End-->
            <div class="container">
                <div class="row">
                    <div class="pd_header_wraper">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <div class="row">
                                <?php if ($logo) { ?>
                                    <div class="pd_logo">
                                        <a href="<?php echo $home; ?>"><img class="img-responsive" src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="pd_right_section">
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                <div class="row">
                                    <div class="pd_title_text">
                                        <div class="quick">
                                            <img src="image/unlock/quick.png" class="img-responsive" alt="">
                                        </div>
	                                    <div class="moneyback">
                                            <?php echo $text_moneyback; ?>
	                                    </div>
                                        <div class="moneyback">
                                            <form action="<?php echo $this->url->link("common/home") ?>" method="post" enctype="multipart/form-data">
                                                <div>Language<br>
                                                    &nbsp;<img width="16px" height="11px" style="cursor:pointer;" src="image/flags/gb.png" alt="English" title="English" onclick="$('input[name=\'language_code\']').attr('value', 'en').submit(); $(this).parent().parent().submit();">
                                                    &nbsp;<img width="16px" height="11px" style="cursor:pointer;" src="image/flags/es.png" alt="Español" title="Español" onclick="$('input[name=\'language_code\']').attr('value', 'es').submit(); $(this).parent().parent().submit();">
                                                    <input type="hidden" name="language_code" value="">
                                                    <input id="language-redirect" type="hidden" name="redirect" value="<?php echo $this->url->link("common/home") ?>">
                                                    <script>
                                                        $(function() {
                                                            $("#language-redirect").val(window.location.href);
                                                        });
                                                    </script>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="row">
                                    <div class="pd_title_img">
                                        <a href="#"><img src="image/unlock/pp_logo.png" class="img-responsive" alt=""></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pd_menu_wraper">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <nav class="navbar navbar-default">
                                        <!--<div class="container-fluid">-->
                                        <!-- Brand and toggle get grouped for better mobile display -->
                                        <div class="navbar-header">
                                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                                    data-target="#bs-example-navbar-collapse-1">
                                                <span class="sr-only"><?php echo $text_toggle_navigation; ?></span>
                                                <span class="icon-bar"></span>
                                                <span class="icon-bar"></span>
                                                <span class="icon-bar"></span>
                                            </button>
                                        </div>

                                        <!-- Collect the nav links, forms, and other content for toggling -->
                                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                            <ul class="nav navbar-nav">
<!--                                                <li class=""><a href="/">--><?php //echo $text_home; ?><!--</a></li>-->
                                                <li><a href="/how-it-works" class="text-capitalize"><?php echo $text_howitworks; ?></a>
                                                </li>
                                                <li><a href="<?php echo $this->url->link('product/testimonial'); ?>"
                                                       class="text-capitalize"><?php echo $text_testimonials; ?></a></li>
                                                <li><a href="<?php echo $this->url->link('information/faq'); ?>"
                                                       class="text-capitalize"><?php echo $text_faq; ?></a></li>
                                                <li><a href="<?php echo $this->url->link('information/orderstatus'); ?>"
                                                       class="text-capitalize"><?php echo $text_orderstatus; ?></a></li>
                                                <li>
                                                    <a href="<?php echo $this->url->link('information/information&information_id=8'); ?>"
                                                       class="text-capitalize"><?php echo $text_codeinstructions; ?></a></li>
                                                <li>
                                                    <a href="<?php echo $this->url->link('information/information&information_id=9'); ?>"
                                                       class="text-capitalize"><?php echo $text_troubleshooting; ?></a></li>
                                                <li>
                                                    <div id="cart">
                                                        <a href="<?php echo $this->url->link('checkout/cart'); ?>"><span
                                                                class="pd_img"><img
                                                                    src="catalog/view/theme/unlock/image/bag.png" alt=""
                                                                    class="img-responsive"></span><span class="pd_item"
                                                                                                        id="cart-total"><?php echo $cart_items_count; ?>
                                                                item</span></a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div><!--navbar-collapse-->
                                        <!--</div>--><!--container-fluid-->
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--pd_header End-->
        <?php if (isset($error) && $error) { ?>
            <div class="warning"><?php echo $error ?><img src="catalog/view/theme/unlock/image/close.png" alt=""
                                                          class="close"/></div>
        <?php } ?>
        <div id="notification">
        </div>
        <?php
        $dt = new DateTime('America/New_York');
        $dt_format = $dt->format('d-m');

        $thanksgiving = in_array($dt_format, array('23-11', '24-11', '25-11'));
        ?>
        <?php if($thanksgiving && false): ?>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12" style="padding-left: 0">
                    <?php if($this->session->data['language'] == 'en'): ?>
                        <img src="image/cyber_monday_en.png" style="width: 100%">
                    <?php else: ?>
                        <img src="image/cyber_monday_es.png" style="width: 100%">
                    <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

