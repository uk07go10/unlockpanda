$(function() {
    const $couponError = $("#coupon-error");
    const $couponButton = $('#button-coupon');

    $couponButton.click(function (e) {
        e.preventDefault();
        $.post('index.php?route=total/coupon/calculate', {
            coupon: $("#coupon").val()
        }, function(data) {
            data = $.parseJSON(data);
            if(data.error) {
                $couponError.text(data.error);
                $couponError.show();
            }

            if(data.redirect) {
                window.location.reload();
            }
        })
    })
});