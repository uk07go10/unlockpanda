<?php if($stripe_enabled): ?>
    <script src="https://js.stripe.com/v3"></script>
    <button id="pay-stripe" class="order-submit-btn">
        Make A Payment
    </button>
    <script>
        let clicked = false;
        const paymentText = {
            header: 'Warning',
            message: 'Please accept the terms.',
            wait_header: 'Processing',
            wait_message: 'Please wait..'
        };
        
        $(function() {
            
            const stripe = Stripe("<?php echo $stripe_publishable_key ?>");

            $("#pay-stripe").click(function(e) {
                e.preventDefault();
                clicked = true;


                $.post("index.php?route=payment/stripe/session&v=<?php echo $stripe_mode ?>&fp=", {}, function(data) {
                    if(data.id) {
                        stripe.redirectToCheckout({
                            sessionId: data.id
                        }).then(function(result) {
                            console.log(result);
                        })
                    } else {
                        clicked = false;
                        Swal.fire({
                            title: 'Error',
                            text: data.error.message,
                            icon: 'warning',
                            confirmButtonText: "OK"
                        });
                    }
                });
            });

            $(window).on("popstate", function() {
                clicked = false;
            });
        });

    </script>
<?php endif; ?>