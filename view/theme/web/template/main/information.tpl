<!DOCTYPE html>
<html>

<head>
    <?= $scripts ?>
</head>

<body>

<?= $navigation ?>

<!-- Page Body -->
<div class="navigation-offset-container container-xxl">
    <section class="information-section">
        <?= $description ?>
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