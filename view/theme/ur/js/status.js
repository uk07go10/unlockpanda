var text = text || {};
text.home = {
    en: {
        
    },
    es: {

    }
};

var homeText = text.home[language];

$(function () {

    $("#check-status").click(function() {
        var order_id = $("#order-id").val();
        var order_email = $("#order-email").val();

        $.get('index.php?route=main/ajax/status&order_email=' + order_email + '&order_id=' + order_id , function(data) {
           var $container = $("#result");
           if(!data.error) {
               $container.html("<div class='alert alert-success'>" +
                   "<p>Status: <strong>" + data.name + "</strong></p>" +
                   "<p>" + data.comment.replace(/\n/g, '<br>') + "</p>" +
                   "</div>");
           } else {
               $container.html("<div class='alert alert-danger'><p>" + data.message + "</p></div>");
           }
        });
    });
    
});