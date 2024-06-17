<?php echo $header; ?>
<?php echo $column_left; ?>
<?php // echo $column_right; ?>

    <link rel="stylesheet" type="text/css" href="catalog/view/theme/unlock_mobiles/stylesheet/jquery.scombobox.css">
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/unlock_mobiles/stylesheet/tooltipster/tooltipster.css">
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/unlock_mobiles/stylesheet/tooltipster/themes/tooltipster-shadow.css">
    <link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/jquery-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/jquery-ui.structure.min.css" />
    <link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/jquery-ui.theme.min.css" />
    <script type="text/javascript" src="catalog/view/javascript/jquery/jquery.easing.min.js"></script>
    <script type="text/javascript" src="catalog/view/javascript/jquery/jquery.scombobox.js"></script>
    <script type="text/javascript" src="catalog/view/javascript/jquery/jquery.tooltipster.min.js"></script>

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

                <h1 style="color: #0A98CA; margin-bottom: 3px;"><?php echo $text_unlock; ?></h1>
                <div class="float_left" style="width: 60%">
                    <h2 style="color: #767676 ; font-size: 15px; margin-bottom: 25px;"><?php echo $text_software; ?></h2>
                    <div class="clear"></div>
                    <div class="float_left" style="width: 29%;">
                        <div class="float_right"><h1 style="color: #0A98CA;font-weight: bold;font-size: 42px">1</h1></div>
                        <div class="float_right"><img src="<?php echo get_image_dir() . 'data/phones12.png' ?>" width="110" height="83" alt="" /></div>
                        <div class="float_left"><p style="font-size: 13px; font-weight: bold; margin: 14px; text-align: center"><?php echo $text_selectform; ?></p></div>
                    </div>
                    <div class="float_left" style="width: 29%; margin-left: 20px;">
                        <div class="float_right"><h1 style="color: #0A98CA;font-weight: bold;font-size: 42px">2</h1></div>
                        <div class="float_right"><img src="<?php echo get_image_dir() . 'data/step2.png' ?>" width="110" height="93" alt="" /></div>
                        <div class="float_left"><p style="font-size: 13px; font-weight: bold; margin: 14px; text-align: center"><?php echo $text_weemail; ?></p></div>
                    </div>
                    <div class="float_left" style="width: 29%; margin-left: 20px;">
                        <div class="float_right"><h1 style="color: #0A98CA;font-weight: bold;font-size: 42px">3</h1></div>
                        <div class="float_right"><img src="<?php echo get_image_dir() . 'data/step3.png' ?>" width="110" height="93" alt="" /></div>
                        <div class="float_left"><p style="font-size: 13px; font-weight: bold; margin: 14px; text-align: center"><?php echo $text_entercode; ?></p></div>
                    </div>
                    <div class="clear"></div>
                    <p style="width: 100%; font-size: 11px;" class="float_left">
                        <?php echo $text_afterunlock; ?>
                    </p>
                    <div class="clear"></div>
                    <script>
                        $(function() {
                            setTimeout(function() {
                                try {
                                    var wistia = document.getElementById("video_player").wistiaApi;
                                    if(typeof (wistia) !== "undefined") {
                                        wistia.bind("play", function () {
                                            $("#how_work").hide();
                                        });
                                        wistia.bind("pause", function () {
                                            $("#how_work").show();
                                        });
                                    }
                                } catch (err) {
                                    console.log("The video didn't loaded properely.");
                                }
                            }, 2000);
                        });
                    </script>
                    <div class="float_left" style="width: 100%; text-align: center; margin-top: 30px;">
                        <?php if($this->session->data['language'] == 'en') { ?>
                        <img id="how_work" src="image/watch_video_en_new_new.png" style="position:absolute; width:315px; margin-top: -45px; margin-left: -50px;"/>
                            <iframe id="video_player" src="//fast.wistia.net/embed/iframe/0ldbhscgop" allowtransparency="true" frameborder="0" scrolling="no" class="wistia_embed" name="wistia_embed" allowfullscreen mozallowfullscreen webkitallowfullscreen oallowfullscreen msallowfullscreen width="520" height="321"></iframe><script src="//fast.wistia.net/assets/external/E-v1.js" async></script>
                        <?php } else { ?>
                        <img id="how_work" src="image/watch_video_es_new_new.png" style="position:absolute; width:315px; margin-top: -38px; margin-left: -50px;"/>
                            <iframe id="video_player" src="//fast.wistia.net/embed/iframe/9s7q2fsdmf" allowtransparency="true" frameborder="0" scrolling="no" class="wistia_embed" name="wistia_embed" allowfullscreen mozallowfullscreen webkitallowfullscreen oallowfullscreen msallowfullscreen width="520" height="293"></iframe><script src="//fast.wistia.net/assets/external/E-v1.js" async></script>
                        <?php } ?>
                    </div>
                </div>

                <script>

                    var lessModelsWarningCarriers = <?php echo (isset($config_less_models) && is_string($config_less_models) && strlen($config_less_models) > 0 ? "'" . addslashes($config_less_models) . "'.split(',')" : "[]"); ?>;
                    var modelsNoticeCarriers = <?php echo (isset($config_models_notice) && is_string($config_models_notice) && strlen($config_models_notice) > 0 ? "'" . addslashes($config_models_notice) . "'.split(',')" : "[]"); ?>;

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

                        $("#select_less_models_box, #select_models_notice_box").tooltipster({
                            contentAsHTML: true
                        });

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

                        $("#default-usage-select1, #default-usage-select3").scombobox($.extend({}, options, {
                            afterClose: function() {
                                setTimeout(function() {
                                    var hide = true;
                                    var $select = $("#default-usage-select1");
                                    var carrierText = $select
                                        .find(".scombobox-list")
                                        .find(".scombobox-hovered")
                                        .text().toLowerCase();
                                    $("#select_less_models_box").hide();
                                    $("#select_models_notice_box").hide();
                                    lessModelsWarningCarriers.forEach(function(value) {
                                        if (carrierText.indexOf(value.trim()) > -1) {
                                            $("#select_less_models_box").show();
                                            hide = false;
                                        }
                                    });
                                    modelsNoticeCarriers.forEach(function(value) {
                                        if(carrierText.indexOf(value.trim()) > -1) {
                                            $("#select_models_notice_box").show();
                                            hide = false;
                                        }
                                    });

                                    if (hide) {
                                        $("#info_box").slideUp();
                                    }
                                }, 500);
                            }
                        }));

                        $("#default-usage-select2").scombobox($.extend({}, options, {
                            afterOpen: function() {
                                setTimeout(function() {
                                    var show = false;
                                    var $select = $("#default-usage-select1");
                                    var carrierText = $select
                                        .find(".scombobox-list")
                                        .find(".scombobox-hovered")
                                        .text().toLowerCase();

                                    $("#select_less_models_box").hide();
                                    $("#select_models_notice_box").hide();

                                    lessModelsWarningCarriers.forEach(function(value) {
                                        if (carrierText.indexOf(value) > -1) {
                                            $("#select_less_models_box").show();
                                            show = true;
                                        }
                                    });
                                    modelsNoticeCarriers.forEach(function(value) {
                                        if (carrierText.indexOf(value) > -1) {
                                            $("#select_models_notice_box").show();
                                            show = true;
                                        }
                                    });

                                    if (show) {
                                        $("#info_box").slideDown();
                                    }
                                }, 500);
                            }
                        }));

                        $("#noaction").remove();
                    });
                </script>
                <div class="float_right" style="display: block">
                    <div id="header_form" class="round_corners" style="display: block">
                        <div id="noaction" style="width: 100%; height: 100%; position: absolute;"></div>
                        <div class="form_content">
                            <h1><?php echo $text_formhead; ?></h1>
                            <div id="nojs" style="font-weight: bold; color: red;">
                                <?php if($this->session->data['language'] == 'en') { ?>
                                    Please enable JavaScript support in your browser - otherwise you will encounter problems with order creation.
                                <?php } else { ?>
                                    Por favor habilita JavaScript en tu navegador - de lo contrario puedes tener problemas al colocar tu orden
                                <?php } ?>
                            </div>
                            <script>
                                document.getElementById('nojs').style.display='none';
                            </script>
                            <div id="inputdiv" class="float_left field_input">
                                <span class="wrong"></span>
                                <div class="field">
                                    <div id="select_carrier">
                                        <select name="carrier" id="default-usage-select1">
                                            <option value="-1"><?php echo $text_formcarrier; ?></option>
                                            <?php foreach ($manufacturers as $manufacturer) { ?>
                                                <option data-metadata="<?php echo (isset($manufacturer['metadata']) ? $manufacturer['metadata'] : '') ?>" value="<?php echo $manufacturer['manufacturer_id'] ?>"><?php echo html_entity_decode($manufacturer['name']) ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="tool_tip">
                                    <div id="popup1" class="arrow_box" style="display: none;"><?php echo $text_selcarrier; ?></div>
                                    <a href="javascript:void(0)"><img data-id="popup1" src="<?php echo $this->model_tool_image->resize('info-icon.png', 35, 28) ?>" alt="" /></a>
                                </div>
                            </div>
                            <div id="info_box" class="float_left field_input" style="margin: 0; display: none;">
                                <span class="wrong" style="background-image: none; height: 20px"></span>
                                <div class="field">
                                    <div style="text-align: center;">
                                        <span id="select_less_models_box" title="<?php echo $text_less_models_text ?>" style="display: none; cursor: pointer; color: #0A98CA; text-decoration: underline; text-transform: none;"><?php echo $text_less_models_title ?></span>
                                        <span id="select_models_notice_box" title="<?php echo $text_models_notice_text ?>" style="display: none; cursor: pointer; color: #0A98CA; text-decoration: underline; text-transform: none;"><?php echo $text_models_notice_title ?></span>
                                    </div>
                                </div>
                            </div>
                            <div id="inputdiv" class="float_left field_input">
                                <span class="wrong"></span>
                                <div class="field">
                                    <div id="select_category">
                                        <select name="category" id="default-usage-select2">
                                            <option value="-1"><?php echo $text_formmanufact; ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="tool_tip">
                                    <div id="popup2" class="arrow_box" style="display: none;"><?php echo $text_selbrand; ?></div>
                                    <a href="javascript:void(0)"><img data-id="popup2" src="<?php echo $this->model_tool_image->resize('info-icon.png', 35, 28) ?>" alt="" /></a>
                                </div>
                            </div>
                            <div id="inputdiv" class="float_left field_input">
                                <span class="wrong"></span>
                                <div class="field">
                                    <div id="select_product">
                                        <select name="default-usage-select3" id="default-usage-select3">
                                            <option value="-1"><?php echo $text_formmodel; ?></option>
                                            <!--                                            <option value="">^ Select Manufacturer to view</option>-->
                                        </select>
                                    </div>
                                </div>
                                <div class="tool_tip">
                                    <div id="popup3" class="arrow_box" style="display: none;"><?php echo $text_selmodel; ?></div>
                                    <a href="javascript:void(0)"><img data-id="popup3" src="<?php echo $this->model_tool_image->resize('info-icon.png', 35, 28) ?>" alt="" /></a>
                                </div>
                            </div>
                            <div id="inputdiv" class="float_left field_input">
                                <span class="wrong"></span>
                                <div class="field">
                                    <input type="text" id="imei" name="imei" value="<?php echo $text_formimei; ?>" onfocus="this.value = ''" />
                                </div>
                                <div class="tool_tip">
                                    <div id="popup4" class="arrow_box" style="display: none;"><?php echo $text_dialimei; ?></div>
                                    <a href="javascript:void(0)"><img data-id="popup4" src="<?php echo $this->model_tool_image->resize('info-icon.png', 35, 28) ?>" alt="" /></a>
                                </div>
                            </div>
                            <div id="inputdiv" class="float_left field_input">
                                <span class="wrong"></span>
                                <div class="field">
                                    <input type="text" id="email" name="email" value="<?php echo $text_formemail; ?>" onfocus="this.value = ''" />
                                </div>
                                <div class="tool_tip">
                                    <div id="popup5" class="arrow_box" style="display: none;"><?php echo $text_selemail; ?></div>
                                    <a href="javascript:void(0)"><img data-id="popup5" src="<?php echo $this->model_tool_image->resize('info-icon.png', 35, 28) ?>" alt="" /></a>
                                </div>
                            </div>
                            <div id="errors" style="text-transform: none;" class="float_left field_input"></div>

                            <!--
                            <div class="float_left field_link" id="quote">
                                <span style="color: #333333;font-size: 15px;">READY TO UNLOCK</span>
                            </div> -->
                            <iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Funlockriver&amp;width=300&amp;layout=standard&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:300px; height:35px;" allowTransparency="true"></iframe>
                        </div>

                        <hr class="float_left"/>


                        <div class="form_footer float_left">
                            <div class="float_left" id="phone_thumb">
                                <img src="<?php echo '/image/default_phone_'.$this->session->data['language'].'.png' ; ?>" height="100" />
                            </div>
                            <div class="float_left" id="phone_price">
                                <div class="price"></div>
                                <input class="round_corners_small" type="submit" name="add_to_cart" id="unlock_now_button" value="<?php echo $text_unlocknow; ?>" />
                                <div id="loading" style="margin-top: 10px;">

                                </div>
                            </div>
                            <p class="info"><?php echo $text_pleasenote; ?></p>

                        </div>
                        <div class="clear"></div>

                    </div>
                </div>

                <div class="clear"></div>

                <?php echo $content_bottom; ?>
            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
        <div class="legal">
            <?php //echo $text_usa; ?>
        </div>
        <div style="text-align:center; margin-bottom: 20px; "><div class="fb-page" data-href="https://www.facebook.com/UnlockRiver" data-width="950" data-height="200" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/UnlockRiver"><a href="https://www.facebook.com/UnlockRiver">Unlock River</a></blockquote></div></div></div>
    </div>

    <!-- Facebook POPUP LikeBox With Timer Code Start -->
    <script language="javascript">
        /*$(document).ready(function() {
         $().socialTrafficPop({
         // Configure display of popup
         title: "TO CONTINUE PLEASE SUBSCRIBE & LIKE",
         message: "",
         closeable: false,
         advancedClose: false,
         opacity: '0.50',
         // Configure URLs and Twitter
         google_url: "",
         fb_url: "",
         twitter_user: "",
         twitter_method: "follow",
         // Set timers
         timeout: 20,
         wait: "1",
         });
         });*/
    </script>
    <!-- Facebook POPUP LikeBox With Timer Code End -->

    <div class="clear"></div>

    <div id="dialog-duplicate" title="Duplicate detected" style="display: none;">
        <p>
            <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
            <span id="dialog-duplicate-content">It seems that you already have exactly the same unlock configuration present in your cart.
                        Do you want to proceed to checkout instead of adding the same unlock configuration to your cart?
            </span>
        </p>
    </div>

    <script type="text/javascript" charset="utf-8">

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

        $(function () {

            $("#header_form input[type='text']").change(function(e) {
                var elemId = "#"+e.target.id;
                if($(elemId).val()==''){
                    $(elemId).parents('#inputdiv').find('span.wrong').removeClass('right');
                    //$(elemId).addClass('wrong');
                } else {
                    $(elemId).parents('#inputdiv').find('span.wrong').addClass('right');
                    //$(elemId).addClass('right');
                }
            });

            var $carrierSelect = $('#default-usage-select1');
            var $brandSelect = $('#default-usage-select2');
            var $modelSelect = $('#default-usage-select3');

            $carrierSelect.scombobox("change", function() {
                $brandSelect.scombobox("disabled", true);
                $modelSelect.scombobox("disabled", true);
                valueHandler($carrierSelect, function() {
                    $.get('index.php?route=common/header/ajaxGetBrands&json=true&carrier_id=' + $carrierSelect.scombobox("val"), function(data) {
                        $brandSelect.scombobox("fill", data);
                        $brandSelect.scombobox("disabled", false);
                        $modelSelect.scombobox("disabled", false);
                        valueHandler($brandSelect)();
                        // $modelSelect.scombobox("fill", []);
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

                                    $('#phone_price .price').html('<span class="delivery"><?php echo 'Delivery Time:'; ?> ' + deliver_time + '</span><br /><span class="delivery"><?php echo 'Best Price:'; ?> </span>US$' + price);
                                    $('#phone_thumb').html('<img src="' + phone_img + '" height="100" />');
                                });
                            })();
                        });

                    });
                })();
            });

        });

        // show tool tips
        $('.tool_tip img').hover(
            function() {
                tt_id = $(this).data("id");
                $('#' + tt_id).fadeIn(200);
            },
            function() {
                $('#' + tt_id).fadeOut(200);
            }
        );

        var submitClicked = false;
        var language = '<?php echo $lang; ?>';
        $('#unlock_now_button').click( function(e) {
            if(!submitClicked) {
                addToCartHeader(
                    $('#default-usage-select1').scombobox("val"),
                    $('#default-usage-select2').scombobox("val"),
                    $('#default-usage-select3').scombobox("val"),
                    $('#imei').val(),
                    $('#email').val(),
                    language,
                    function () {
                        submitClicked = true;
                        $("#loading").text(language == "en" ? "Loading.. please wait." : "Cargando.. por favor espere.").show();
                    }, function() {
                        submitClicked = false;
                    });
            }
            e.preventDefault();
        });
    </script>


<?php echo $footer; ?>