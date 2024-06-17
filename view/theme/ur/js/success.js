function email_subscribe(){
    $.ajax({
        type: 'post',
        url: 'index.php?route=module/mailing/promo_register',
        dataType: 'html',
        data:$("#subscribe").serialize(),
        beforeSend: function() {
            $('#subscribe_email').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
        },
        complete: function() {
            $('.wait').remove();
        },
        success: function (data) {
            $("#subscribe_result").html("Email Confirmed Successfully.");
            $("#subscribe :input").attr("disabled", true);
            $("#subscribe")[0].reset();
        }});
}