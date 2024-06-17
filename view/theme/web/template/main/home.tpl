<!DOCTYPE html>
<html>

<head>
    <?= $scripts ?>
</head>

<body>

<?= $navigation ?>

<!-- Page Body -->
<?= $header_form ?>


<?php if($this->session->data['language'] == 'en'){ ?>
 <section class="horizontal-frame">
    <marquee loop="infinite" onmouseover="this.stop();" onmouseout="this.start();">
        <div class="horizontal-frame-main custom-flex-row items-center">
            <div class="custom-flex-row items-center guarantee-item">
                <img src="/catalog/view/theme/web/img/unlock/guarantee.svg" alt="Misc" />
                <p>Your phone with <span>any</span> Carrier</p>
            </div>
            <div class="custom-flex-row items-center guarantee-item">
                <img src="/catalog/view/theme/web/img/unlock/guarantee.svg" alt="Misc" />
                <p><span>No</span> risk of damaging your phone</p>
            </div>
            <div class="custom-flex-row items-center guarantee-item">
                <img src="/catalog/view/theme/web/img/unlock/guarantee.svg" alt="Misc" />
                <p>Money-back <span>guarantee</span></p>
            </div>
            <div class="custom-flex-row items-center guarantee-item">
                <img src="/catalog/view/theme/web/img/unlock/guarantee.svg" alt="Misc" />
                <p><span>Easy</span> process and 100% permanent</p>
            </div>
        </div>
    </marquee>
 </section>
<?php }else{ ?>

 <section class="horizontal-frame">
    <marquee loop="infinite" onmouseover="this.stop();" onmouseout="this.start();">
        <div class="horizontal-frame-main custom-flex-row items-center">
            <div class="custom-flex-row items-center guarantee-item">
                <img src="/catalog/view/theme/web/img/unlock/guarantee.svg" alt="Misc" />
                <p>Tu Teléfono <span>con</span>cualquier Operador</p>
            </div>
            <div class="custom-flex-row items-center guarantee-item">
                <img src="/catalog/view/theme/web/img/unlock/guarantee.svg" alt="Misc" />
                <p><span>Sin</span> Riesgos de dañar tu Teléfono</p>
            </div>
            <div class="custom-flex-row items-center guarantee-item">
                <img src="/catalog/view/theme/web/img/unlock/guarantee.svg" alt="Misc" />
                <p>Garantía <span> de Devolución del dinero</span></p>
            </div>
            <div class="custom-flex-row items-center guarantee-item">
                <img src="/catalog/view/theme/web/img/unlock/guarantee.svg" alt="Misc" />
                <p><span>Proceso </span> Fácil y 100% permanente</p>
                
            </div>
        </div>
    </marquee>
 </section>
<?php }  ?>

<?php if($this->session->data['language'] == 'en'){ ?>
  <section class="how-work-section">
    <h1 class="how-work-title">How it works.</h1>
    <div class="custom-flex-row align-items-start w-full how-work-row">
        <div class="flex-1">
            <img src="/catalog/view/theme/web/img/unlock/fill-the-form.svg" class="how-work-item-cover" alt="Fill The Form" />
            <div class="how-work-item-info custom-flex-row">
                <span class="order-number custom-flex-row justify-center items-center">01</span>
                <div>
                    <p class="how-work-item-title">Provide Your Details</p>
                    <p class="how-work-item-desc">
                        Choose your desired service from our array including SIM network unlocks,
                        passcode resets through factory restore, iCloud lock status checks,
                        or FRP lock services.
                        Then, furnish us with the necessary details such as device model and carrier
                        (if applicable), alongside the IMEI or serial number.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex-1">
            <img src="/catalog/view/theme/web/img/unlock/get-unlock-code.svg" class="how-work-item-cover" alt="Get a unlock code" />
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
            <img src="/catalog/view/theme/web/img/unlock/put-and-unlocked.svg" class="how-work-item-cover" alt="Put and unlocked" />
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
<?php }else{ ?>

  <section class="how-work-section">
    <h1 class="how-work-title">Así Funciona:</h1>
    <div class="custom-flex-row align-items-start w-full how-work-row">
        <div class="flex-1">
            <img src="/catalog/view/theme/web/img/unlock/fill-the-form.svg" class="how-work-item-cover" alt="Fill The Form" />
            <div class="how-work-item-info custom-flex-row">
                <span class="order-number custom-flex-row justify-center items-center">01</span>
                <div>
                    <p class="how-work-item-title">Ingresa tus datos</p>
                    <p class="how-work-item-desc">
                       Selecciona tu modelo y operador, e introduce tu IMEI para comenzar la liberación. Este paso garantiza una liberación personalizada para tu dispositivo.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex-1">
            <img src="/catalog/view/theme/web/img/unlock/get-unlock-code.svg" class="how-work-item-cover" alt="Get a unlock code" />
            <div class="how-work-item-info custom-flex-row">
                <span class="order-number custom-flex-row justify-center items-center">02</span>
                <div>
                    <p class="how-work-item-title">Iniciando Liberación</p>
                    <p class="how-work-item-desc">
                        Al confirmar tu orden, procederemos la liberación remota. Con nuestro sistema de seguimiento en directo, estarás al tanto de cada paso del proceso.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex-1">
            <img src="/catalog/view/theme/web/img/unlock/put-and-unlocked.svg" class="how-work-item-cover" alt="Put and unlocked" />
            <div class="how-work-item-info custom-flex-row">
                <span class="order-number custom-flex-row justify-center items-center">03</span>
                <div>
                    <p class="how-work-item-title">Liberación Completa</p>
                    <p class="how-work-item-desc">
                        Te enviaremos un email cuando tu móvil esté liberado. Prepárate para utilizarlo libremente, siguiendo nuestras sencillas instrucciones de activación de liberación y conexión WiFi.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php }  ?>



<?php if($this->session->data['language'] == 'en'){ ?>
  <section class="why-choose-section custom-flex-row justify-center items-center">
    <img src="/catalog/view/theme/web/img/unlock/ellipse1.svg" class="ellipse1" alt="Ellipse 1" />
    <img src="/catalog/view/theme/web/img/unlock/ellipse2.svg" class="ellipse2" alt="Ellipse 2" />
    <div class="why-choose-container custom-flex-row items-center">
        <img src="/catalog/view/theme/web/img/unlock/why-choose-cover.svg" class="why-choose-cover" alt="Cover Image" />
        <div>
            <h1 class="why-choose-title">Why Choose UnlockPanda to Unlock your Phone?</h1>
            <p class="why-choose-desc">UnlockPanda provides the hassle-free service for unlocking your phone. With
                services like network unlocks, carrier unlocks, iCloud status checks, and more, we provide reliable
                solutions backed by exceptional customer support. Enjoy the freedom to use your phone on any network
                with our 100% money-back guarantee. Trust UnlockPanda for seamless and secure unlocking.</p>
            <!--
            <button class="learn-more-button custom-flex-row items-center">Learn More
                <img src="/catalog/view/theme/web/img/unlock/right-arrow.svg" alt="Right Arrow" />
            </button>
            -->
        </div>
    </div>
</section>
<?php }else{ ?>

    <section class="why-choose-section custom-flex-row justify-center items-center">
    <img src="/catalog/view/theme/web/img/unlock/ellipse1.svg" class="ellipse1" alt="Ellipse 1" />
    <img src="/catalog/view/theme/web/img/unlock/ellipse2.svg" class="ellipse2" alt="Ellipse 2" />
    <div class="why-choose-container custom-flex-row items-center">
        <img src="/catalog/view/theme/web/img/unlock/why-choose-cover.svg" class="why-choose-cover" alt="Cover Image" />
        <div>
            <h1 class="why-choose-title">¿Por qué elegir UnlockPanda para liberar tu teléfono?</h1>
            <p class="why-choose-desc">UnlockPanda proporciona el servicio sin complicaciones para liberar tu teléfono. Con servicios como Liberación de Red, Liberación de Operadores, Comprobaciones de Estado de iCloud y más, brindamos soluciones confiables respaldadas por un servicio de atención al cliente excepcional. Disfruta de la libertad de usar tu teléfono en cualquier red con nuestra garantía de devolución del 100% del dinero. Confíea en UnlockPanda para una liberación segura y sin problemas.</p>
            <!--
            <button class="learn-more-button custom-flex-row items-center">Más Información
                <img src="/catalog/view/theme/web/img/unlock/right-arrow.svg" alt="Right Arrow" />
            </button>
            -->
        </div>
    </div>
</section>
<?php }  ?>





<?php if($this->session->data['language'] == 'en'){ ?>
  <section class="testimonial-section custom-flex-col items-center">
    <h1 class="testimonial-title">Surely it’s not <span>that</span> easy, <span>right?</span></h1>
    <p class="testimonial-desc">It really is. Have a look at what some our customers say 👇🏼</p>

    <div class="trustpilot-testimonial-container custom-flex-row">
        <div class="trustpilot-logo-section custom-flex-col justify-center flex-1">
            <img src="/catalog/view/theme/web/img/unlock/Trustpilot-logo.png" alt="Trustpilot Logo" class="trustpilot-logo-img" />
            <h4 class="testimonial-score-title">Excellent</h4>
            <img src="/catalog/view/theme/web/img/unlock/testimonial-stars.png" alt="Testimonial Stars" class="testimonial-stars" />
            <p class="testimonial-score-desc">TrustScore 4.5 | <strong>2537 reviews</strong></p>
            <button class="check-reviews-btn">Check All Reviews</button>
        </div>
        <div class="testimonials-wrapper flex-1 custom-flex-row">
            <div class="testimonial-card custom-flex-col">
                <div class="testimonial-user-info-row custom-flex-row items-center">
                    <img src="/catalog/view/theme/web/img/unlock/Emilio.png" alt="User Avatar" class="testimonial-user-avatar" />
                    <div>
                        <p class="testimonial-user-name">Emilio Zeceña</p>
                        <p class="testimonial-user-location custom-flex-row items-center">1 reviews&nbsp;&nbsp;&nbsp;<img
                                    src="/catalog/view/theme/web/img/unlock/carbon_location.svg" alt="Location" />&nbsp;United States</p>
                    </div>
                </div>
                <div class="w-full custom-flex-row items-center justify-between">
                    <img src="/catalog/view/theme/web/img/unlock/5_stars.png" alt="5 Stars" />
                    <p class="testimonial-date">
                        May 23, 2023
                    </p>
                </div>

                <p class="user-review-title">Safe and reliable</p>
                <p class="user-review-desc">I have processed 2 orders with this company, unfortunately only 1 could
                    be unlocked but they gave me a full refund for the order that was not completed, so I would
                    recommend this site because it is reliable.</p>
            </div>

            <div class="testimonial-card custom-flex-col">
                <div class="testimonial-user-info-row custom-flex-row items-center">
                    <img src="/catalog/view/theme/web/img/unlock/Karl.png" alt="User Avatar" class="testimonial-user-avatar" />
                    <div>
                        <p class="testimonial-user-name">Karl Flohr</p>
                        <p class="testimonial-user-location custom-flex-row items-center">2 reviews&nbsp;&nbsp;&nbsp;<img
                                    src="/catalog/view/theme/web/img/unlock/carbon_location.svg" alt="Location" />&nbsp;United States</p>
                    </div>
                </div>
                <div class="w-full custom-flex-row items-center justify-between">
                    <img src="/catalog/view/theme/web/img/unlock/5_stars.png" alt="5 Stars" />
                    <p class="testimonial-date">
                        Apr 22, 2023
                    </p>
                </div>

                <p class="user-review-title">Worked 100% and very Fast!</p>
                <p class="user-review-desc">I was a little skeptical about this but was in need of unlocking my
                    iPhone for a trip abroad. It worked perfectly fine and the delivery time was very fast.
                    Definitely recommend Unlockpanda and will use again if I need to unlock another device.</p>
            </div>

            <div class="testimonial-card custom-flex-col">
                <div class="testimonial-user-info-row custom-flex-row items-center">
                    <img src="/catalog/view/theme/web/img/unlock/Nicholas.png" alt="User Avatar" class="testimonial-user-avatar" />
                    <div>
                        <p class="testimonial-user-name">Nicholas Antunano</p>
                        <p class="testimonial-user-location custom-flex-row items-center">9 reviews&nbsp;&nbsp;&nbsp;<img
                                    src="/catalog/view/theme/web/img/unlock/carbon_location.svg" alt="Location" />&nbsp;Spain</p>
                    </div>
                </div>
                <div class="w-full custom-flex-row items-center justify-between">
                    <img src="/catalog/view/theme/web/img/unlock/5_stars.png" alt="5 Stars" />
                    <p class="testimonial-date">
                        May 11, 2021
                    </p>
                </div>

                <p class="user-review-title">Unlock panda was the best service I've…</p>
                <p class="user-review-desc">Unlock panda was the best service I've ever used and were so quick so
                    fast afordable. I've read their reviews online and people say they're not real and I'm telling
                    you if I could see you face-to-face in my life I would tell you it's real Thank you guys.</p>
            </div>

            <div class="testimonial-card custom-flex-col">
                <div class="testimonial-user-info-row custom-flex-row items-center">
                    <img src="/catalog/view/theme/web/img/unlock/Jessie.png" alt="User Avatar" class="testimonial-user-avatar" />
                    <div>
                        <p class="testimonial-user-name">Jessie Akpan</p>
                        <p class="testimonial-user-location custom-flex-row items-center">2 reviews&nbsp;&nbsp;&nbsp;<img
                                    src="/catalog/view/theme/web/img/unlock/carbon_location.svg" alt="Location" />&nbsp;NG</p>
                    </div>
                </div>
                <div class="w-full custom-flex-row items-center justify-between">
                    <img src="/catalog/view/theme/web/img/unlock/5_stars.png" alt="5 Stars" />
                    <p class="testimonial-date">
                        Oct 2, 2018
                    </p>
                </div>

                <p class="user-review-title">Best in maintaining Integrity</p>
                <p class="user-review-desc">With so much doubt on online company, UnlockPanda has proven herself to
                    be one of the trusted n best company i have ever known.Am a Nigerian, i requested for a network
                    unlock, i paid. Unfortunately it wasnt unlock, n my money was refunded within days.Thank you
                    UnlockPanda. You guys should keep up the good work.</p>
            </div>
        </div>
    </div>
</section> 
<?php }else{ ?>

  <section class="testimonial-section custom-flex-col items-center">
    <h1 class="testimonial-title">¿No crees que <span>así de</span> easy, <span> simple?</span></h1>
    <p class="testimonial-desc">Realmente lo es. Descubre lo que dicen nuestros clientes 👇🏼</p>

    <div class="trustpilot-testimonial-container custom-flex-row">
        <div class="trustpilot-logo-section custom-flex-col justify-center flex-1">
            <img src="/catalog/view/theme/web/img/unlock/Trustpilot-logo.png" alt="Trustpilot Logo" class="trustpilot-logo-img" />
            <h4 class="testimonial-score-title">Excelente</h4>
            <img src="/catalog/view/theme/web/img/unlock/testimonial-stars.png" alt="Testimonial Stars" class="testimonial-stars" />
            <p class="testimonial-score-desc">TrustScore 4.5 | <strong>2537 opiniones</strong></p>
            <button class="check-reviews-btn">Ver Todas las Opiniones</button>
        </div>
        <div class="testimonials-wrapper flex-1 custom-flex-row">
            <div class="testimonial-card custom-flex-col">
                <div class="testimonial-user-info-row custom-flex-row items-center">
                    <img src="/catalog/view/theme/web/img/unlock/Emilio.png" alt="User Avatar" class="testimonial-user-avatar" />
                    <div>
                        <p class="testimonial-user-name">Emilio Zeceña</p>
                        <p class="testimonial-user-location custom-flex-row items-center">1 reviews&nbsp;&nbsp;&nbsp;<img
                                    src="/catalog/view/theme/web/img/unlock/carbon_location.svg" alt="Location" />&nbsp;United States</p>
                    </div>
                </div>
                <div class="w-full custom-flex-row items-center justify-between">
                    <img src="/catalog/view/theme/web/img/unlock/5_stars.png" alt="5 Stars" />
                    <p class="testimonial-date">
                        May 23, 2023
                    </p>
                </div>

                <p class="user-review-title">Safe and reliable</p>
                <p class="user-review-desc">I have processed 2 orders with this company, unfortunately only 1 could
                    be unlocked but they gave me a full refund for the order that was not completed, so I would
                    recommend this site because it is reliable.</p>
            </div>

            <div class="testimonial-card custom-flex-col">
                <div class="testimonial-user-info-row custom-flex-row items-center">
                    <img src="/catalog/view/theme/web/img/unlock/Karl.png" alt="User Avatar" class="testimonial-user-avatar" />
                    <div>
                        <p class="testimonial-user-name">Karl Flohr</p>
                        <p class="testimonial-user-location custom-flex-row items-center">2 reviews&nbsp;&nbsp;&nbsp;<img
                                    src="/catalog/view/theme/web/img/unlock/carbon_location.svg" alt="Location" />&nbsp;United States</p>
                    </div>
                </div>
                <div class="w-full custom-flex-row items-center justify-between">
                    <img src="/catalog/view/theme/web/img/unlock/5_stars.png" alt="5 Stars" />
                    <p class="testimonial-date">
                        Apr 22, 2023
                    </p>
                </div>

                <p class="user-review-title">Worked 100% and very Fast!</p>
                <p class="user-review-desc">I was a little skeptical about this but was in need of unlocking my
                    iPhone for a trip abroad. It worked perfectly fine and the delivery time was very fast.
                    Definitely recommend Unlockpanda and will use again if I need to unlock another device.</p>
            </div>

            <div class="testimonial-card custom-flex-col">
                <div class="testimonial-user-info-row custom-flex-row items-center">
                    <img src="/catalog/view/theme/web/img/unlock/Nicholas.png" alt="User Avatar" class="testimonial-user-avatar" />
                    <div>
                        <p class="testimonial-user-name">Nicholas Antunano</p>
                        <p class="testimonial-user-location custom-flex-row items-center">9 reviews&nbsp;&nbsp;&nbsp;<img
                                    src="/catalog/view/theme/web/img/unlock/carbon_location.svg" alt="Location" />&nbsp;Spain</p>
                    </div>
                </div>
                <div class="w-full custom-flex-row items-center justify-between">
                    <img src="/catalog/view/theme/web/img/unlock/5_stars.png" alt="5 Stars" />
                    <p class="testimonial-date">
                        May 11, 2021
                    </p>
                </div>

                <p class="user-review-title">Unlock panda was the best service I've…</p>
                <p class="user-review-desc">Unlock panda was the best service I've ever used and were so quick so
                    fast afordable. I've read their reviews online and people say they're not real and I'm telling
                    you if I could see you face-to-face in my life I would tell you it's real Thank you guys.</p>
            </div>

            <div class="testimonial-card custom-flex-col">
                <div class="testimonial-user-info-row custom-flex-row items-center">
                    <img src="/catalog/view/theme/web/img/unlock/Jessie.png" alt="User Avatar" class="testimonial-user-avatar" />
                    <div>
                        <p class="testimonial-user-name">Jessie Akpan</p>
                        <p class="testimonial-user-location custom-flex-row items-center">2 reviews&nbsp;&nbsp;&nbsp;<img
                                    src="/catalog/view/theme/web/img/unlock/carbon_location.svg" alt="Location" />&nbsp;NG</p>
                    </div>
                </div>
                <div class="w-full custom-flex-row items-center justify-between">
                    <img src="/catalog/view/theme/web/img/unlock/5_stars.png" alt="5 Stars" />
                    <p class="testimonial-date">
                        Oct 2, 2018
                    </p>
                </div>

                <p class="user-review-title">Best in maintaining Integrity</p>
                <p class="user-review-desc">With so much doubt on online company, UnlockPanda has proven herself to
                    be one of the trusted n best company i have ever known.Am a Nigerian, i requested for a network
                    unlock, i paid. Unfortunately it wasnt unlock, n my money was refunded within days.Thank you
                    UnlockPanda. You guys should keep up the good work.</p>
            </div>
        </div>
    </div>
</section>
<?php }  ?>




<?php if($this->session->data['language'] == 'en'){ ?>
  <section class="faq-section custom-flex-row justify-center w-full">
    <div class="faq-container custom-flex-row">
        <div>
            <h1 class="faq-title">Frequently Asked<br />Questions</h1>
            <p class="faq-desc">Can’t find what you are looking for?</p>
            <p class="faq-chat custom-flex-row items-center">
                We would like to&nbsp;<span>chat<img src="/catalog/view/theme/web/img/unlock/faq-arrow.svg" alt="FAQ Arrow"
                                                     class="faq-arrow" /></span>&nbsp;with you.
            </p>
            <img src="/catalog/view/theme/web/img/unlock/faq-chat.svg" alt="FAQ Chat" class="faq-chat-img" />
        </div>
        <div class="faq-right">
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
<?php }else{ ?>

  <section class="faq-section custom-flex-row justify-center w-full">
    <div class="faq-container custom-flex-row">
        <div>
            <h1 class="faq-title">Preguntas  Asked<br />Frecuentes</h1>
            <p class="faq-desc">¿No encuentras lo que estás buscando?</p>
            <p class="faq-chat custom-flex-row items-center">
               Nos encantaría&nbsp;<span>chatear<img src="/catalog/view/theme/web/img/unlock/faq-arrow.svg" alt="FAQ Arrow"
                                                     class="faq-arrow" /></span>&nbsp;contigo.
            </p>
            <img src="/catalog/view/theme/web/img/unlock/faq-chat.svg" alt="FAQ Chat" class="faq-chat-img" />
        </div>
        <div class="faq-right">
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
