<script>



    var $zopimData = {
        email: '<?php echo $email; ?>',
        greetings: '<?php echo $greetings; ?>',
        language: '<?php echo $language; ?>',
        notes: '<?php echo $notes ?>'
    };

    var storage;

    if(typeof Storage !== "undefined") {
        storage = localStorage;
    } else {
        storage = {
            setItem: function () {
                return true;
            },
            getItem: function () {
                return false;
            }
        }
    }

    function updateZopimInfo() {
        setTimeout(function () {
            $zopim(function () {

                $zopim.livechat.setEmail($zopimData.email);
                if(!$zopim.livechat.getName()) {
                    $zopim.livechat.setName($zopimData.email.split("@")[0]);
                }


                $zopim.livechat.addTags("Language: " + $zopimData.language);
                $zopim.livechat.setLanguage($zopimData.language);
                $zopim.livechat.setGreetings($zopimData.greetings);
                $zopim.livechat.offlineForm.setGreetings($zopimData.greetings);
                $zopim.livechat.setNotes($zopimData.notes);

            });
        }, 500);
    }

    function paymentFailedCallback() {
        if(storage.getItem("$zopimActionInvoked")) {
            return;
        }

        setTimeout(function ()  {
            $zopim(function () {
                $zopim.livechat.addTags("PayPal");
                $zopim.livechat.addTags("Payment problem");

                $zopim.livechat.window.show();
                storage.setItem("$zopimActionInvoked", true);
            });
        }, 1000);

    }
    $(function() {
        updateZopimInfo();
        <?php if($payment_cancelled) { ?>
        paymentFailedCallback();
        <?php } ?>
    });

</script>