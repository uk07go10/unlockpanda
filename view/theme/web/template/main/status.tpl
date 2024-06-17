<!DOCTYPE html>
<html>

<head>
    <?= $scripts ?>
</head>

<body>

<?= $navigation ?>

<!-- Page Body -->
<?php if($this->session->data['language'] == 'en'){ ?>
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
<?php }else{ ?>

  <div class="navigation-offset-container container-xxl">
    <section class="check-order-status-section">
        <div class="row mb-5">
            <div class="col-12">
                <h1 class="text-center check-order-status-title">Comprobar el estado del pedido</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6">
                <p class="pt-3">
                    Introduce en los cuadros el ID de tu pedido y el correo electrónico que utilizaste al crear el pedido y haz clic en Verificar
  estado.
                </p>
                <ul>
                    <li>Utilice el mismo correo electrónico que utilizó para realizar el pedido.</li>
                    <li>El ID del pedido se puede encontrar en el correo electrónico de recibo.</li>
                    <li>Los tiempos de entrega pueden variar según la marca de tu teléfono. Por lo general, los códigos de desbloqueo se entregan
en 24
horas, sin embargo, en algunos casos puede demorar hasta 15 días hábiles según la marca, el
modelo y la
red de tu teléfono.
                    </li>
                </ul>
            </div>
            <div class="col-12 col-md-6 mt-3 mt-md-0 d-flex flex-column">
                <section class="check-status justify-center w-full">
                    <div class="input-with-icon">
                        <img src="/catalog/view/theme/web/img/shared/email.svg">
                        <input id="order-email" type="email" class="custom-input" placeholder="Tu correo electrónico">
                    </div>
                </section>
                <section class="check-status justify-center w-full mt-3">
                    <div class="input-with-icon">
                        <input id="order-id" type="text" class="custom-input" placeholder="Su número de orden">
                    </div>
                </section>
                <button id="check-status" class="form-submit-btn text-center mt-5">Comprobar estado</button>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <div id="result"></div>
            </div>
        </div>
    </section>
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