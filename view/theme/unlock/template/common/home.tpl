<?php echo $header; ?>
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/unlock/stylesheet/jquery.scombobox.css">
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/unlock/stylesheet/tooltipster/tooltipster.css">
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/unlock/stylesheet/tooltipster/themes/tooltipster-shadow.css">
    <script type="text/javascript" src="catalog/view/javascript/jquery/jquery.easing.min.js"></script>
    <script type="text/javascript" src="catalog/view/javascript/jquery/jquery.scombobox.js"></script>
    <script type="text/javascript" src="catalog/view/javascript/jquery/jquery.tooltipster.min.js"></script>
    <div id="contentfoo">
    <div class="pd_unlock">
        <div class="container">
            <div class="row">
                <div class="pd_unlock_wraper">
                    <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                        <div class="pd_unlock_left">
                            <div class="pd_title"><h1><?php echo $text_header; ?></h1>
                            </div>
                            <div class="pd_video_wraper">
                                <div class="pd_video_container" style="margin-top: 20px;">
                                    <!--<iframe src="https://www.youtube.com/embed/QqQrKMAVTYg?feature=player_embedded" allowfullscreen="" id="fitvid677904"></iframe>-->
                                    <iframe src="//fast.wistia.net/embed/iframe/tgppyeco4f" allowtransparency="true"
                                            frameborder="0" scrolling="no" class="wistia_embed" name="wistia_embed"
                                            allowfullscreen mozallowfullscreen webkitallowfullscreen
                                            oallowfullscreen msallowfullscreen width="635" height="353"></iframe>
                                    <script src="//fast.wistia.net/assets/external/E-v1.js" async></script>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <div class="pd_unlock_right">
                            <div class="pd_unlock_form">
                                <h4 class="pd_for_heading text-capitalize"><?php echo $text_starthere; ?></h4>

                                <div class="pd_form_icon"><i class="fa fa-lock"></i></div>
                                <div class="pd_form_data">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
                                        <select name="carrier" id="select-carrier" form="carform">
                                            <option value="-1"><?php echo $text_selectcarrier; ?></option>
                                            <?php foreach ($carriers as $carrier) { ?>
                                                <option data-metadata="<?php echo (isset($carrier['metadata']) ? $carrier['metadata'] : '') ?>"
                                                        <?php if ($carrier['manufacturer_id'] == $carrier_id) { ?>
                                                            selected="selected"
                                                        <?php } ?>
                                                        value="<?php echo $carrier['manufacturer_id'] ?>"><?php echo html_entity_decode($carrier['name']) ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
                                        <select name="model" id="select-manufacturer" form="carform">
                                            <option value="-1"><?php echo $text_selectmanufacturer; ?></option>
                                        </select>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
                                        <select name="model" id="select-model" form="carform">
                                            <option value="-1"><?php echo $text_selectmodel; ?></option>
                                        </select>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="imei"
                                                   placeholder="<?php echo $text_imei; ?>">

                                            <div class="tool_tip">
                                                <div style="display: none;" class="arrow_box" id="popup4">
                                                    <?php echo $text_imei_helper; ?>
                                                </div>
                                                <a href="javascript:void(0)"><img width="35" height="28" alt=""
                                                                                  src="image/info-icon.png"
                                                                                  data-id="popup4"></a></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <input type="email" class="form-control" id="email"
                                                   placeholder="<?php echo $text_email?>">

                                            <div class="tool_tip">
                                                <div style="display: none;" class="arrow_box" id="popup5">
                                                    <?php echo $text_email_helper; ?>
                                                </div>
                                                <a href="javascript:void(0)"><img width="35" height="28" alt=""
                                                                                  src="image/info-icon.png"
                                                                                  data-id="popup5"></a></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="col-lg-6 form-group" id="phone_thumb">
                                        </div>
                                        <div class="col-lg-6 form-group" id="phone_price">
                                            <div class="price">
                                            </div>
                                            <div class="btn btn-primary btn-lg" id="unlock_now_button"
                                                 value="<?php echo $text_unlocknow; ?>"><?php echo $text_unlocknow; ?><span
                                                    class="glyphicon glyphicon-arrow-right"
                                                    aria-hidden="true"></span></div>
                                        </div>
                                        <div class="col-lg-6 form-group" id="loading" style="display: none;">
                                        </div>
                                    </div>
                                    <div id="errors" style="text-transform: none;"
                                         class="float_left field_input"></div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <p><?php echo $text_mustbecorrect; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--pd_unlock End-->
    <div class="pd_unlock_step">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="pd_top_heading">
                        <h2><?php echo $text_unlock_steps_header ?></h2>

                        <div class="pd_border"><i class="fa fa-circle"></i></div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="pd_top_text text-center"><?php echo $text_unlock_steps_subheader; ?></div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="pd_step">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pd_inner_stepdiv">
                            <div class="pd_step_img">
                                <div class="pd_step_pic">
                                    <img src="image/how_it_works/clipboard.png" class="img-responsive" alt="" style="width: 45%; margin-bottom: 10px;">
                                </div>
                                <div class="pd_step_name">
                                    <h5><?php echo $text_unlock_step_1 ?></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pd_inner_stepdiv">
                            <div class="pd_step_img">
                                <div class="pd_step_pic">
                                    <img src="image/how_it_works/credit_card.png" class="img-responsive" alt="" style="width: 75%">
                                </div>
                                <div class="pd_step_name">
                                    <h5><?php echo $text_unlock_step_2 ?></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pd_inner_stepdiv">
                            <div class="pd_step_img">
                                <div class="pd_step_pic">
                                    <img src="image/how_it_works/mail.png" class="img-responsive" alt="" style="width: 40%; margin-bottom: 15px;">
                                </div>
                                <div class="pd_step_name">
                                    <h5><?php echo $text_unlock_step_3 ?></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pd_inner_stepdiv">
                            <div class="pd_step_img">
                                <div class="pd_step_pic">
                                    <img src="image/how_it_works/phone.png" class="img-responsive" alt="" style="width: 35%">
                                </div>
                                <div class="pd_step_name">
                                    <h5><?php echo $text_unlock_step_4 ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="pd_step_content">
                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                            <div class="pd_unlock_img">
                                <img class="img-responsive" src="image/unlock/iPhone.png" alt="">
                            </div>
                        </div>

                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                            <div class="pd_facebook_post">
                                <div class="fb-like-box" data-href="https://www.facebook.com/UnlockPanda"
                                     data-width="361" data-height="327" data-colorscheme="light"
                                     data-show-faces="true" data-header="true" data-stream="false"
                                     data-show-border="true"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--pd_unlock_step End-->
    <div class="pd_client">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="pd_top_heading">
                        <h2><?php echo $text_client_testimonials; ?></h2>

                        <div class="pd_border"><i class="fa fa-circle"></i></div>
                    </div>
                </div>
                <div class="pd_slider">
                    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

                        <!-- Wrapper for slides -->
                        <div class="carousel-inner" role="listbox">

                            <?php if (isset($testimonials) && is_array($testimonials)): ?>
                            <?php foreach ($testimonials as $key => $testimonial): ?>
                            <?php if ($key % 2 == 0): ?>
                            <div class="item<?php echo ($key == 0 ?  " active" : ""); ?>">
                                <?php endif; ?>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="pd_client_content">
                                        <div class="pd_client_slider">
                                            <div class="pd_slider_content">
                                                <blockquote class="blockquote-reverse">
                                                    <span class="pd_quote"><i class="fa fa-quote-left"></i></span>

                                                    <p><?php echo $testimonial['description'] ?></p>
                                                    <footer><span><?php echo $testimonial['name'] ?></span> <cite
                                                            title="Source Title">
                                                            <p><?php echo $testimonial['city'] ?></p></cite>
                                                    </footer>
                                                </blockquote>
                                            </div>
<!--                                            <div class="pd_slider_img">-->
<!--                                                <img src="image/unlock/01.png" class="img-responsive" alt="">-->
<!--                                            </div>-->
                                        </div>
                                    </div>
                                </div>
                                <?php if ($key % 2 == 1): ?>
                                </div>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Controls -->
                            <a class="left carousel-control" href="#carousel-example-generic" role="button"
                               data-slide="prev">
                                <i class="fa fa-angle-left"></i>
                            </a>
                            <a class="right carousel-control" href="#carousel-example-generic" role="button"
                               data-slide="next">
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </div>

                    </div>
                    <!--pd slider End-->
                </div>
            </div>
        </div>
        <!--pd_client End-->
    </div>

    <div id="dialog-duplicate" title="Duplicate detected" style="display: none;">
        <p>
            <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
            <span id="dialog-duplicate-content">It seems that you already have exactly the same unlock configuration present in your cart.
                        Do you want to proceed to checkout instead of adding the same unlock configuration to your cart?
            </span>
        </p>
    </div>
    <div id="dialog-cdma" title="Error" style="display: none;">
        <p>
            <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
            <span id="dialog-cdma-content">It seems that you already have exactly the same unlock configuration present in your cart.
                        Do you want to proceed to checkout instead of adding the same unlock configuration to your cart?
            </span>
        </p>
    </div>
    <script>

        function beforeOpen() {
            if(this.scombobox("val") == -1) {
                this.scombobox("val", "");
            }
            this.scombobox("rebuildDict");
        }

        function beforeClose () {
            if(this.scombobox("val") == "") {
                this.scombobox("val", "-1");
            }
        }

        $(function() {

            var options = {
                fullMatch: true,
                highlight: false,
                sortAsc: false,
                sort: false,
                filterIgnoreCase: true,
                hideSeparatorsOnSearch: true,
                beforeOpen: beforeOpen,
                beforeClose: beforeClose
            };

            $("#select-carrier, #select-manufacturer, #select-model").scombobox($.extend({}, options, {
                afterClose: function() {
                    setTimeout(function() {
                        var $select = $("#select-carrier");
                        var carrierText = $select
                            .find(".scombobox-list")
                            .find(".scombobox-hovered")
                            .text();
                    }, 500);
                }
            }));

            var valueHandler = function (element, callback) {
                return function () {
                    setTimeout(function () {
                        var value = element.scombobox("val");
                        if (value == '' || value < 0) {
                            element.parents('#inputdiv').find('span.wrong').removeClass('right');
                        } else {
                            element.parents('#inputdiv').find('span.wrong').addClass('right');
                        }
                        if(typeof(callback) == "function") {
                            callback(value);
                        }
                    }, 250);
                }
            };

            var $carrierSelect = $('#select-carrier');
            var $brandSelect = $('#select-manufacturer');
            var $modelSelect = $('#select-model');

            $carrierSelect.scombobox("change", function() {
                $brandSelect.scombobox("disabled", true);
                $modelSelect.scombobox("disabled", true);
                valueHandler($carrierSelect, function() {
                    $.get('index.php?route=common/header/ajaxGetBrands&json=true&carrier_id=' + $carrierSelect.scombobox("val"), function(data) {
                        $brandSelect.scombobox("fill", data);
                        $brandSelect.scombobox("disabled", false);
                        $modelSelect.scombobox("disabled", false);
                        valueHandler($brandSelect)();
                         $modelSelect.scombobox("fill", [{
                          value: "-1", text: "<?php echo $text_selectmodel; ?>"
                         }]);
                        valueHandler($modelSelect)();
                    });
                })();
            });

            $brandSelect.scombobox("change", function () {
                $modelSelect.scombobox("disabled", true);
                valueHandler($brandSelect, function () {
                    $.get('index.php?route=common/header/ajaxGetProducts&json=true&category_id=' + $brandSelect.scombobox("val") + "&carrier_id=" + $carrierSelect.scombobox("val"), function (data) {
                        $modelSelect.scombobox("fill", data);
                        $modelSelect.scombobox("disabled", false);
                        valueHandler($modelSelect)();

                        $modelSelect.scombobox("change", function () {
                            valueHandler($modelSelect, function() {
                                if($modelSelect.scombobox("val") == -1) {
                                    $("#phone_price").find(".price").html("");
                                    $("#phone_thumb").html("<img src=\"/image/default_phone_en.png\" height=\"100\">");
                                    return;
                                }
                                $.getJSON('index.php?route=common/header/ajaxGetProduct&prod_id=' + $modelSelect.scombobox("val"), function (json) {
                                    var price = $.number(json.price, 2);
                                    var reg_price = Number(price) + 12;
                                    var deliver_time = json.delivery_time;
                                    var phone_img;

                                    if (json.image !== null && json.image !== undefined) {
                                        if (json.image.length == 0) {
                                            phone_img = 'image/no_image.jpg';
                                        } else {
                                            phone_img = 'image/' + json.image;
                                        }
                                    } else {
                                        phone_img = 'image/no_image.jpg';
                                    }

                                    //$('#phone_price .price').html('<span class="delivery">Delivery Time: ' + deliver_time + '</span><br /><span class="reg_price">regular: $' + reg_price + '</span><br /><span class="delivery">Special Price: </span>$' + price);
                                    $('#phone_price .price').html('<span class="delivery"><?php echo 'Delivery Time:'; ?> ' + deliver_time + '</span><br /><span class="delivery"><?php echo 'Best Price:'; ?> </span>US$' + price);
                                    $('#phone_thumb').html('<div class="imgthumb"><img src="' + phone_img + '" height="150" width="120"/></div>');
                                });
                            })();
                        });

                    });
                })();
            });
        });
    </script>
    <script type="text/javascript">
        $('document').ready(function () {
            $('.tool_tip img').hover(function () {
                tt_id = $(this).data("id");
                $('#' + tt_id).fadeIn(200);
            }, function () {
                $('#' + tt_id).fadeOut(200);
            });

            var submitClicked = false;

            $('#unlock_now_button').click(function (e) {
                addToCartHeader(
                    $('#select-carrier').scombobox('val'),
                    $('#select-manufacturer').scombobox('val'),
                    $('#select-model').scombobox('val'),
                    $('#imei').val(),
                    $('#email').val(),
                    'en',
                    function() {
                        submitClicked = true;
                        $("#loading").text("Loading..").show();
                    }, function () {
                        submitClicked = false;
                    });
                e.preventDefault();
            })

        });
    </script>
<?php echo $footer; ?>