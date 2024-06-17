<?php if ($amazonpay_enabled): ?>

    <script type='text/javascript'>
        window.onAmazonLoginReady = function() {
            amazon.Login.setClientId('<?php echo $amazonpay_client_id ?>');
        };
        window.onAmazonPaymentsReady = function() {
            showButton();
        };
    </script>
    <script async="async" src='<?php echo $amazonpay_script_url ?>'></script>

    <div id="AmazonPayButton">
    </div>

<!--    <script async src="https://static-na.payments-amazon.com/OffAmazonPayments/us/js/Widgets.js"></script>-->
<!--    <div-->
<!--            data-ap-widget-type="expressPaymentButton"-->
<!--            data-ap-signature="qX%2F3N%2BNMX0%2BUsdK%2FYqL6lOOYT1LH368xyML3C4DaEzo%3D"-->
<!--            data-ap-seller-id="AE8MOED2BAVXA"-->
<!--            data-ap-access-key="AKIAINCNHWF5LDEDFXYQ"-->
<!--            data-ap-lwa-client-id="amzn1.application-oa2-client.23b79fa1fddf4847b127ab3cf2d0df54"-->
<!--            data-ap-return-url="https://www.unlockpanda.com/index.php?success"-->
<!--            data-ap-currency-code="USD"-->
<!--            data-ap-amount="10"-->
<!--            data-ap-note=""-->
<!--            data-ap-shipping-address-required="false"-->
<!--            data-ap-payment-action="AuthorizeAndCapture"-->
<!--    >-->
<!--    </div>-->

    <script type="text/javascript">
        function showButton(){
            OffAmazonPayments.Button("AmazonPayButton", "<?php echo $amazonpay_merchant_id ?>", {
                type:  "hostedPayment",
                hostedParametersProvider: function(done) {
                    $.post("index.php?route=payment/amazon_pay/session&v=<?php echo $amazonpay_mode ?>&fp=" + getFp(), {}, function (data) {
                        console.log(data);
                        done(data.params);
                    });
                    
                },
                onError: function(error) {
                    console.log("The following error occurred: " 
                           + error.getErrorCode() 
                           + ' - ' + error.getErrorMessage());
                }
            });
        }
    </script>

<?php endif; ?>