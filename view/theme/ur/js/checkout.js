
var clicked = false;

function getFp() {
    var value = Cookies.get("fp");
    if(typeof(value) === "undefined") {
        return "false";
    }
    return value;
}

$(function () {

    try {
        new Fp2().get(function (result) {
            Cookies.set("fp", result);
        });
    }
    catch (e) {
        
    }
    
    var $couponError = $("#coupon-error");
    
    $("#button-coupon").click(function (e) {
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