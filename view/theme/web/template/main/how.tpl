<!DOCTYPE html>
<html>

<head>
    <?= $scripts ?>
</head>

<body>

<?= $navigation ?>

<!-- Page Body -->
<div class="navigation-offset-container container-xxl">
    <section class="how-work-section">
        <h1 class="how-work-title">How it works.</h1>
        <div class="custom-flex-row align-items-start w-full how-work-row">
            <div class="flex-1">
                <img src="/catalog/view/theme/web/img/unlock/fill-the-form.svg" class="how-work-item-cover"
                     alt="Fill The Form"/>
                <div class="how-work-item-info custom-flex-row">
                    <span class="order-number custom-flex-row justify-center items-center">01</span>
                    <div>
                        <p class="how-work-item-title">Provide Your Details</p>
                        <p class="how-work-item-desc">
                            Choose your desired service from our array including SIM network unlocks,
                            passcode resets through factory restore, iCloud lock status checks,
                            or FRP lock services.
                            Then, furnish us with the necessary details such as device model and
                            carrier (if applicable), alongside the IMEI or serial number.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex-1">
                <img src="/catalog/view/theme/web/img/unlock/get-unlock-code.svg" class="how-work-item-cover"
                     alt="Get a unlock code"/>
                <div class="how-work-item-info custom-flex-row">
                    <span class="order-number custom-flex-row justify-center items-center">02</span>
                    <div>
                        <p class="how-work-item-title">We Initiate the Service</p>
                        <p class="how-work-item-desc">
                            Once your payment is confirmed, we promptly start working on the service you requested.
                            Stay informed with the live tracking link that we provide in your confirmation email.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex-1">
                <img src="/catalog/view/theme/web/img/unlock/put-and-unlocked.svg" class="how-work-item-cover"
                     alt="Put and unlocked"/>
                <div class="how-work-item-info custom-flex-row">
                    <span class="order-number custom-flex-row justify-center items-center">03</span>
                    <div>
                        <p class="how-work-item-title">Service Completion</p>
                        <p class="how-work-item-desc">
                            After we've completed the service, you'll receive a notification through email.
                            The completion status may refer to various outcomes such as a network unlock,
                            a status check report, or a reset passcode based on the service you opted for.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bottom-desc-section">
        <div class="bottom-desc-row">
            <h1 class="bottom-desc-title">
                Have a problem? We are here to help you
                <img src="/catalog/view/theme/web/img/payment/bottom desc arrow.png" alt="Title Arrow"/>
            </h1>
            <div class="bottom-desc-right">
                <p>Our dedicated team is available 24/7 to resolve any unlocking issues, provide guidance, and ensure a
                    smooth process. Experience the convenience of real-time interaction and unlock your phone
                    confidently with UnlockPanda's Live Chat Support by your side.</p>
                <button onclick="zE.activate()" class="form-submit-btn">Chat With Us</button>
            </div>
        </div>
    </section>

</div>
<!-- Page Body -->

<?= $footer ?>

</body>

</html>