<!DOCTYPE html>
<html>

<head>
    <?= $scripts ?>
</head>

<body>

<?= $navigation ?>

<!-- Page Body -->
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