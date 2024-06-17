<!DOCTYPE html>
<html>

<head>
    <?= $scripts ?>
</head>

<body>

<?= $navigation ?>

<!-- Page Body -->
<div class="navigation-offset-container container-xl">
    <section class="faq-section flex-column justify-center w-full">
        <div class="row faq-container">
            <div class="col-12">
                <h1 class="faq-title text-center">
<?php if($this->session->data['language'] == 'es'){ ?>
Preguntas frecuentes
<?php }else{ ?>

Frequently Asked Questions
<?php }  ?>
</h1>
            </div>
        </div>
        <div class="row faq-container mt-5">
            <div class="col-12">
                <? foreach($faqs as $faq): ?>
                <div class="info-box mb-3">
                    <div class="d-flex items-center justify-between cursor-pointer w-full accordion">
                        <p class="info-title"><?= $faq['title']; ?></p>
                        <img src="/catalog/view/theme/web/img/payment/accordion arrow.svg" alt="Accordion Icon" />
                    </div>
                    <p class="panel">
                        <?= $faq['description']; ?>
                    </p>
                </div>
                <? endforeach; ?>
            </div>
        </div>
    </section>
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
