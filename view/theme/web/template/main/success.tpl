<!DOCTYPE html>
<html>

<head>
    <?= $scripts ?>
</head>

<body>

<?= $navigation ?>

<!-- Page Body -->

<?php if($this->session->data['language'] == 'en'){ ?>
  <div class="custom-flex-row justify-center payment-success-page-container">
    <div class="custom-flex-col items-center justify-center payment-success-box">
        <img src="/catalog/view/theme/web//img/payment/success-bg.svg" alt="Success Background" class="success-bg" />

        <div class="custom-flex-col w-full items-center z-10">
            <img src="/catalog/view/theme/web/img/payment/success-img.svg" alt="Success Image" />
            <h1 class="payment-success-title">Congratulations</h1>
            <p class="payment-success-desc">Your order has been received. You’ll get an email as soon as it ready.</p>
            <div class="custom-flex-col payment-info-wrapper">
                <div class="payment-success-info-row custom-flex-row items-center justify-between w-full">
                    <p class="payment-info-title">Your Email:</p>
                    <p class="payment-info-detail"><?= $email ?></p>
                </div>
                <div class="payment-success-info-row custom-flex-row items-center justify-between w-full">
                    <p class="payment-info-title">Order lD:</p>
                    <p class="payment-info-detail"><?= $order_id ?></p>
                </div>
                <div class="payment-success-info-row custom-flex-row items-center justify-between w-full">
                    <p class="payment-info-title">Expected Delivery Time:</p>
                    <p class="payment-info-detail"><?= $delivery_time ?></p>
                </div>
            </div>

            <div class="custom-flex-row items-center actions-row">
                <a href="/index.php?route=main/contact">Need Support?</a>
                <a href="/index.php?route=main/status">Track Order</a>
            </div>
        </div>
    </div>
</div>
<?php }else{ ?>

  <div class="custom-flex-row justify-center payment-success-page-container">
    <div class="custom-flex-col items-center justify-center payment-success-box">
        <img src="/catalog/view/theme/web//img/payment/success-bg.svg" alt="Success Background" class="success-bg" />

        <div class="custom-flex-col w-full items-center z-10">
            <img src="/catalog/view/theme/web/img/payment/success-img.svg" alt="Success Image" />
            <h1 class="payment-success-title">Felicitaciones</h1>
            <p class="payment-success-desc">Tu pedido ha sido recibido. Recibirás un correo electrónico tan pronto como esté listo.</p>
            <div class="custom-flex-col payment-info-wrapper">
                <div class="payment-success-info-row custom-flex-row items-center justify-between w-full">
                    <p class="payment-info-title">Correo Electrónico:</p>
                    <p class="payment-info-detail"><?= $email ?></p>
                </div>
                <div class="payment-success-info-row custom-flex-row items-center justify-between w-full">
                    <p class="payment-info-title">Orden lD:</p>
                    <p class="payment-info-detail"><?= $order_id ?></p>
                </div>
                <div class="payment-success-info-row custom-flex-row items-center justify-between w-full">
                    <p class="payment-info-title">Tiempo de Entrega estimado:</p>
                    <p class="payment-info-detail"><?= $delivery_time ?></p>
                </div>
            </div>

            <div class="custom-flex-row items-center actions-row">
                <a href="/index.php?route=main/contact">¿Necesitas soporte?</a>
                <a href="/index.php?route=main/status">Verificar Orden</a>
            </div>
        </div>
    </div>
</div>
<?php }  ?>


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