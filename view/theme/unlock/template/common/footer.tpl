<div>
    <div class="pd_footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="pd_footer_wraper">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="pd_foter_content">
                                <a href="index.html"><img src="image/unlock/f-logo.png" class="img-responsive"
                                                          alt=""></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="pd_foter_content">
                                <div class="pd_heading">
                                    <h3><?php echo $text_information; ?></h3>
                                </div>
                                <ul class="pd_quick">
                                    <?php foreach ($informations as $information) { ?>
                                        <li>
                                            <a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="pd_foter_content">
                                <div class="pd_heading">
                                    <h3><?php echo $text_service; ?></h3>
                                </div>
                                <div class="pd_data">
                                    <ul class="pd_quick">
                                        <li><a href="mailto:<?php echo $this->config->get('config_email') ?>"><?php echo $this->config->get('config_email') ?></a></li>
                                        <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
                                        <li><a href="<?php echo $this->url->link('information/faq'); ?>"><?php echo $text_codenot; ?></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="pd_foter_content">
                                    <div class="pd_heading">
                                        <h3><?php echo $text_get_social; ?></h3>
                                    </div>
                                    <div class="pd_social">
                                        <ul>
                                            <li><a href="https://www.facebook.com/UnlockPanda" class="pd_facebook"
                                                   target="_blank"><i class="fa fa-facebook"></i></a></li>
                                            <li><a href="#" class="pd_twitter" target="_blank"><i
                                                        class="fa fa-twitter"></i></a></li>
                                            <li><a href="#" class="pd_google" target="_blank"><i
                                                        class="fa fa-google-plus"></i></a></li>
                                            <li><a href="#" class="pd_rss" target="_blank"><i class="fa fa-rss"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="pd_heading">
                                        <h3><?php echo $text_accept; ?></h3>
                                    </div>
                                    <div class="pd_pay_img">
                                        <ul>
                                            <li><a href="#"><img src="image/unlock/pp_logo.png"
                                                                 class="img-responsive" alt="" style="height:30px;"></a></li>
                                            <li><a href="#"><img src="image/unlock/payment_gateway1.png"
                                                                 class="img-responsive" alt=""></a></li>
                                            <li><a href="#"><img src="image/unlock/payment_gateway4.png"
                                                                 class="img-responsive" alt=""></a></li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div><!--pd_footer End-->
    <div class="pd_copyright">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="pd_copyright_wraper">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="pd_copyright_link">
                                <p>&copy; Copyright 2016 <a href="#">UnlockPanda.com</a></p>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="pd_copyright_page">
                                <ul>
                                    <li>
                                        <a href="<?php echo $this->url->link('information/information&information_id=5'); ?>">
                                            <?php echo $text_terms_conditions; ?>
                                        </a></li>
                                    <li>|</li>
                                    <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="pd_top">
                        <i class="fa fa-angle-up"></i>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script type="text/javascript" src="catalog/view/javascript/jquery/bootstrap.js"></script>
</body></html>