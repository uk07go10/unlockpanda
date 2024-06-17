<!DOCTYPE html><html class="no-js" lang="en"><head><?= $scripts ?><script>var text = text || {};
text.faq = {
    en: {

    },
    es: {
    }
};

var faqText = text.faq[language];

$(function () {
    $('#faq').collapse({
        query: '.panel .panel-heading',
        persist: true,
        accordion: true
    });

    function isChatAvailable() {
        var now = moment().tz('EST');


        var weekday = now.isoWeekday();
        if(weekday > 5) {
            return false;
        }

        moment.tz.setDefault('EST');
        var startFirst = moment('10:00:00', 'hh:mm:ss');
        var endFirst = moment('14:00:00', 'hh:mm:ss');

        var startSecond = moment('17:00:00', 'hh:mm:ss');
        var endSecond = moment('23:59:59', 'hh:mm:ss');

        return !!(now.isBetween(startFirst, endFirst) || now.isBetween(startSecond, endSecond));


    };

    if(isChatAvailable()) {
        $('.chat-available').show();
    } else {
        $('.chat-unavailable').show();
    }
});</script></head><body><?= isset($header) ? $header : "" ?>
<?= isset($header_form) ? $header_form : "" ?><section class="page-wrapper mt-50" id="page-content"><!-- SHOP SECTION START--><div class="shop-section mb-80"><div class="container"><? if(isset($flash)): ?><div class="row"><div class="alert alert-danger"><?= $flash['content']; ?></div></div><? endif; ?><div class="row pb-20"><div class="col-md-12 col-sm-12 col-xs-12"><h1><?= $text_header; ?></h1></div></div><div class="row"><div class="col-md-12 col-sm-12 col-xs-12 information-page"><div class="panel-group" id="faq"><? foreach($faqs as $faq): ?><div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title"><i class="zmdi zmdi-help-outline"></i>
<?= $faq['title']; ?></h4></div><div class="panel-body"><p><?= $faq['description']; ?></p><p><?= $text_contact_pre ?> <a class="button extra-small" onclick="zE.activate();" style="height: 22px; position: relative; top: 6px;"><i class="zmdi zmdi-help-outline" style="padding: 5px; width: 28px; font-size: 13px;"></i><span class="chat-available" style="display:none; padding: 1px 10px; font-size: 12px"><?= $text_contact_button_chat_available ?></span><span class="chat-unavailable" style="display:none; padding: 1px 10px; font-size: 12px"><?= $text_contact_button_chat_unavailable ?></span></a></p></div></div><? endforeach; ?></div></div></div></div></div><?= $footer ?>  </section></body></html>