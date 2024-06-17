<!DOCTYPE html>
<html>

<head>
    <?= $scripts ?>
</head>

<body>

<?= $navigation ?>

<!-- Page Body -->
<div class="navigation-offset-container container-xxl">
    <section class="check-order-status-section">
        <div class="row mb-5">
            <div class="col-12">
                <h1 class="text-center check-order-status-title">Check order status</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6">
                <p class="pt-3">
                    Insert your Order ID and email you've used when creating the order in the boxes and click Check
                    status.
                </p>
                <ul>
                    <li>Use the same email you've used to place the order.</li>
                    <li>Order ID can be found on the receipt email.</li>
                    <li>Delivery times may vary depending on the brand of your phone. Usually unlock codes are delivered
                        within 24
                        hours, however in some cases it may take up to 15 business days depending on your phone’s brand,
                        model and
                        network.
                    </li>
                </ul>
            </div>
            <div class="col-12 col-md-6 mt-3 mt-md-0 d-flex flex-column">
                <section class="check-status justify-center w-full">
                    <div class="input-with-icon">
                        <img src="/catalog/view/theme/web/img/shared/email.svg">
                        <input id="order-email" type="email" class="custom-input" placeholder="Your Email">
                    </div>
                </section>
                <section class="check-status justify-center w-full mt-3">
                    <div class="input-with-icon">
                        <input id="order-id" type="text" class="custom-input" placeholder="Your order number">
                    </div>
                </section>
                <button id="check-status" class="form-submit-btn text-center mt-5">Check status</button>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <div id="result"></div>
            </div>
        </div>
    </section>
</div>


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
<!-- Page Body -->

<?= $footer ?>

</body>

</html>