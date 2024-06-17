<!DOCTYPE html>
<html>

<head>
    <?= $scripts ?>
</head>

<body>

<?= $navigation ?>

<!-- Page Body -->
<div class="page-container">
    <h1 class="page-title">Review Your Order</h1>
    <div class="review-order-row">
        <form class="order-form">
            <? foreach($products as $product): ?>
            <div class="order-form-header-row">
                <img src="<?= $product['thumb']; ?>" alt=""/>
                <div>
                    <h4 class="order-form-header-title">
                        Permanent unlock of <?= $product['name'] ?>
                    </h4>
                    <p class="imei-number">
                        <span>IMEI:</span> <?= $product['imei']; ?>
                    </p>
                    <p class="original-carrier">
                        <span>Original Carrier: </span><?= $product['carrier']; ?>
                    </p>
                </div>
            </div>
            <hr/>
            <p class="discount-title">Coupon Discount</p>
            <div id="coupon-error" class="alert alert-danger" style="display: none">

            </div>
            <div class="discount-input-row">
                <input id="coupon" type="text" class="discount-input" placeholder="Enter your code here.. "/>
                <button id="button-coupon" class="discount-apply-btn">Apply</button>
            </div>
            <hr/>
            <? foreach($totals as $total): ?>
            <? if ($total['title'] === 'Total'): ?>
            <div class="price-row">
                <p>Our price</p>
                <p><?= $total['text']; ?></p>
            </div>
            <? endif ?>
            <? endforeach; ?>
            <p class="delivery-time">Delivery Time: <?= $product['delivery_time']; ?></p>
            <hr/>
            <? endforeach; ?>
            <p class="payment-method-select-title">Payment Method</p>
            <div class="payment-method-select-row">
                <div class="payment-method-item">
                    <img src="/catalog/view/theme/web/img/payment/card.svg" alt="Card Payment"/>
                    <p class="payment-method-name">Card</p>
                </div>
            </div>
            <?= $stripe_new ?>
            <p class="terms-text">By placing your order you agree to our <a href="#">terms and condition.</a></p>
        </form>

        <div class="payment-form-right-info">
            <div class="info-box">
                <div class="custom-flex-row items-center justify-between cursor-pointer w-full accordion">
                    <p class="info-title">Why Unlock With Us?</p>
                    <img src="/catalog/view/theme/web/img/payment/accordion arrow.svg" alt="Accordion Icon"/>
                </div>
                <div class="info-list panel">
                    Choose us for a service that is quick, dependable, and always here to help you. <br/>
                    Here's why you'll love unlocking your phone with us:
                    <ul class="mt-3">
                        <li>
                            <strong>Fast and Easy to Track:</strong>
                            We offer the quickest service out there,
                            and you can watch the live status of your unlock at any time.
                        </li>
                        <li>
                            <strong>24/7 Support Team:</strong> Our friendly team is ready to help you with
                            any questions, day or night.
                        </li>
                        <li><strong>Safe and Legal:</strong>
                            We unlock your phone in a way that is 100% safe and allowed by law,
                            without hurting your warranty.
                        </li>
                        <li><strong>Simple and Convenient:</strong>
                            Unlock your phone easily from your own home, without any trouble.
                        </li>
                        <li><strong>Global Recognition:</strong>
                            Trusted by millions globally for reliable unlocking services.
                        </li>
                    </ul>
                </div>
            </div>

            <div class="info-box">
                <div class="custom-flex-row items-center justify-between cursor-pointer w-full accordion">
                    <p class="info-title"><span>100% Money-Back Guarantee</span></p>
                    <img src="/catalog/view/theme/web/img/payment/accordion arrow.svg" alt="Accordion Icon"/>
                </div>

                <div class="info-list panel">
                    We stand by our promise to unlock your phone successfully, even when your carrier won't or if it's under contract.
                    However, be aware of the following: <br /><br />
                    <strong>Guaranteed Unlock:</strong> If we can't unlock your phone, you get your money back. <br/><br/>
                    <strong>Refund Policy:</strong> We cannot issue refunds for incorrect information provided by the customer, such as:
                    <ul>
                        <li>Wrong or already unlocked IMEI</li>
                        <li>Incorrect carrier or phone model</li>
                        <li>Blacklisted phones (stolen/lost)</li>
                    </ul>

                    <a href="/refund-policy" target="_blank">Detailed refund policy</a>

                </div>
            </div>

            <div class="info-box">
                <div class="custom-flex-row items-center justify-between cursor-pointer w-full accordion">
                    <p class="info-title">Benefits of Unlocking Your Phone</p>
                    <img src="/catalog/view/theme/web/img/payment/accordion arrow.svg" alt="Accordion Icon"/>
                </div>

                <div class="info-list panel">
                    Unlocking your phone with us brings you a world of advantages that go beyond just savings and safety.
                    Here is why unlocking is a great choice:
                    <ul class="mt-3">
                        <li><strong>Increased Resale Value:</strong> Your phone’s market value gets a boost when it is unlocked.</li>
                        <li><strong>Reduced Roaming Costs:</strong> Save on those hefty bills by using local carriers when you are traveling.</li>
                        <li><strong>Freedom of Choice:</strong> Enjoy the liberty to choose any carrier, any SIM card, or any eSIM worldwide, breaking free from the restrictions of your current carrier.</li>
                        <li><strong>Safe and Permanent:</strong> The unlocking process is safe and leaves no damage on your phone; it’s a one-time process that lasts forever.</li>
                        <li><strong>Enhanced Network Compatibility:</strong> Your unlocked phone can work with virtually any network, giving you the flexibility to switch as per your preference.</li>
                    </ul>
                </div>
            </div>

            <div class="info-box">
                <p class="info-title">For additional information, please check these links:</p>
                <ul class="info-list text-underline">
                    <li>
                        <a href="/cdma-carriers" target="_blank">
                            CDMA Carriers
                        </a>
                    </li>
                    <li>
                        <a href="/terms-conditions" target="_blank">
                            Terms and Conditions
                        </a>
                    </li>
                    <li>
                        <a href="/refund-policy">
                            Refund policy
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>







<?php if($this->session->data['language'] == 'en'){ ?>
  <section class="bottom-desc-section">
    <div class="bottom-desc-row">
        <h1 class="bottom-desc-title">
            Have a problem? We are here to help you
            <img src="/catalog/view/theme/web/img/payment/bottom desc arrow.png" alt="Title Arrow" />
        </h1>
        <div class="bottom-desc-right">
            <p>Our dedicated team is available 24/7 to resolve any unlocking issues, provide guidance, and ensure a
                smooth process. Experience the convenience of real-time interaction and unlock your phone
                confidently with UnlockPanda's Live Chat Support by your side.</p>
            <button onclick="zE.activate()" class="form-submit-btn">Chat With Us</button>
        </div>
    </div>
</section>
<?php }else{ ?>

  <section class="bottom-desc-section">
    <div class="bottom-desc-row">
        <h1 class="bottom-desc-title">
            ¿Tienes un Problema? ¡Estamos aquí para Ayudarte!
            <img src="/catalog/view/theme/web/img/payment/bottom desc arrow.png" alt="Title Arrow" />
        </h1>
        <div class="bottom-desc-right">
            <p>Nuestro equipo dedicado está disponible las 24 horas del día, los 7 días de la semana para resolver cualquier problema de liberación, brindar orientación y garantizar un proceso sin problemas. Experimenta la comodidad de la interacción en tiempo real y libera tu teléfono con confianza con el soporte de chat en vivo de UnlockPanda a tu lado.</p>
            <button onclick="zE.activate()" class="form-submit-btn">Chatea con Nosotros</button>
        </div>
    </div>
</section>
<?php }  ?>
<!-- Page Body -->

<?= $footer ?>

</body>
</html>