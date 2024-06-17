<!DOCTYPE html>
<html>

<head>
    <?= $scripts ?>
</head>

<body>

<?= $navigation ?>

<!-- Page Body -->
<div class="navigation-offset-container container-xxl">
    <section class="contact-section">
        <div class="row mb-5">
            <div class="col-12">
                <h1 class="text-center contact-title">Contact us</h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input id="name" type="text" class="form-control" placeholder="Your name">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="text" class="form-control" placeholder="Your email">
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Your message</label>
                    <textarea id="message" class="form-control" rows="8"></textarea>
                </div>

                <button id="submit" class="form-submit-btn">Submit</button>
            </div>
        </div>
        <div class="row mt-3">
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