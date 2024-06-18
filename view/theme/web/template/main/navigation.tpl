<!-- Top Navigation -->
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W4GM7MB"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

 

<?php if($this->session->data['language'] == 'en'){ ?>
   <div class="top-navigation">
    <div class="navigation-wrapper">

        <a href="/index.php?route=main/home">
            <img src="/catalog/view/theme/web/img/logo.png" alt="Logo Image" class="logo-img" />
        </a>
        <ul class="navigation-menu">
           <?php if (count($languages) > 1) { ?>
           <li class="navigation-menu-item dropdown-tab custom-flex-row items-center">
           <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
             <div id="language" style="padding-bottom:29px;"><?php echo $text_language; ?><br />
               <?php foreach ($languages as $language) { ?>
                 &nbsp;<img src="image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" onclick="setLanguageAndSubmit('<?php echo $language['code']; ?>');" style="cursor:pointer" />
               <?php } ?>
               <input type="hidden" name="language_code" id="language_code" value="" />
               <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
             </div>
           </form>
           
            </li>
           <?php } ?>

            <li class="navigation-menu-item dropdown-tab custom-flex-row items-center">
                <a href="#">Service</a>
                <img src="/catalog/view/theme/web/img/top menu/navigation-bottom-arrow.svg" class="bottom-arrow"
                     alt="Bottom Arrow" />
                <div class="menu-dropdown dropdown-tab-drop">
                    <a href="/index.php?route=main/home" class="menu-item-with-icon">
                        <img src="/catalog/view/theme/web/img/top menu/Unlock-By-IMEI.png" alt="Unlock By IMEI" />
                        <div>
                            <p class="menu-dropdown-item-title">Unlock By IMEI</p>
                            <p class="menu-dropdown-item-desc">Unlock Your Phone By Using <br />16 digit IMEI
                                Number.
                            </p>
                        </div>
                    </a>

                    <div class="menu-item-with-icon menu-item-coming-soon">
                        <img src="/catalog/view/theme/web/img/top menu/Unlock-By-Cable.png" alt="Unlock By Cable" />
                        <div>
                            <p class="menu-dropdown-item-title">Unlock By Cable</p>
                            <p class="menu-dropdown-item-desc">Coming soon!</p>
                        </div>
                    </div>

                    <div class="menu-item-coming-soon">
                        <p class="menu-dropdown-title">Sim Lock Status Check</p>
                        <p class="menu-dropdown-item-desc">Coming soon!</p>
                    </div>
                </div>
            </li>

            <li class="navigation-menu-item">
                <a href="/howit2">How It Works</a>
            </li>

            <li class="navigation-menu-item dropdown-tab custom-flex-row items-center">
                <a href="#">Help</a>
                <img src="/catalog/view/theme/web/img/top menu/navigation-bottom-arrow.svg" class="bottom-arrow"
                     alt="Bottom Arrow" />
                <div class="menu-dropdown dropdown-tab-drop">
                    <a href="/troubleshooting">
                        <p class="menu-dropdown-title">Troubleshooting</p>
                        <p class="menu-dropdown-item-desc">In case you encounter any problems <br> with the process</p>
                    </a>
                </div>
            </li>

            <li class="navigation-menu-item">
                <a href="/index.php?route=main/faq">FAQ</a>
            </li>

            <li class="navigation-menu-item">
                <a href="/index.php?route=main/contact">Contact</a>
            </li>
        </ul>
        <div class="cart-action-row custom-flex-row items-center">
            <a href="/index.php?route=main/status">Check Order Status</a>
            <a href="/index.php?route=main/checkout" class="cart-btn custom-flex-row items-center justify-center">
                <img src="/catalog/view/theme/web/img/top menu/cart.svg" alt="Cart" />
            </a>
            <div class="mobile-hamburger" onclick="toggleMenu()">
                <img src="/catalog/view/theme/web/img/shared/mobile-menu.svg" alt="Mobile Menu" style="display: block;"
                     id="open-mobile-menu" />
                <img src="/catalog/view/theme/web/img/shared/mobile-menu-close.svg" style="display: none;" alt="Close Mobile Menu"
                     id="close-mobile-menu" />
            </div>
        </div>
    </div>
   </div>


   <div class="mobile-responsive-menu" id="mobile-responsive-menu" style="display: none;">
    <ul class="mobile-navigation-menu">
        <li class="navigation-menu-item">
            <a href="/index.php?route=main/status">Check Order Status</a>
        </li>
        <li class="navigation-menu-item">
            <a href="/index.php?route=main/checkout">Cart</a>
        </li>
        <li class="navigation-menu-item dropdown-tab custom-flex-col">
            <div class="custom-flex-row items-center w-full justify-between accordion">
                <a href="#">Service</a>
                <img src="/catalog/view/theme/web/img/top menu/navigation-bottom-arrow.svg" style="width: 15px;" class="bottom-arrow"
                     alt="Bottom Arrow" />
            </div>
            <div class="panel">
                <div class="mobile-dropdown-panel custom-flex-col w-full">
                    <a href="/index.php?route=main/home" class="menu-item-with-icon">
                        <img src="/catalog/view/theme/web/img/top menu/Unlock-By-IMEI.png" alt="Unlock By IMEI" />
                        <div>
                            <p class="menu-dropdown-item-title">Unlock By IMEI</p>
                            <p class="menu-dropdown-item-desc">Unlock Your Phone By Using <br />16 digit IMEI
                                Number.
                            </p>
                        </div>
                    </a>

                    <div class="menu-item-with-icon menu-item-coming-soon">
                        <img src="/catalog/view/theme/web/img/top menu/Unlock-By-Cable.png" alt="Unlock By Cable" />
                        <div>
                            <p class="menu-dropdown-item-title">Unlock By Cable</p>
                            <p class="menu-dropdown-item-desc">
                                Coming soon!
                            </p>
                        </div>
                    </div>

                    <div class="menu-item-coming-soon">
                        <p class="menu-dropdown-title m">Sim Lock Status Check</p>
                        <p class="menu-dropdown-item-desc">
                            Coming soon!
                        </p>
                    </div>
                </div>
            </div>

        </li>

        <li class="navigation-menu-item">
            <a href="/howit2">How It Works</a>
        </li>

        <li class="navigation-menu-item dropdown-tab custom-flex-col">
            <div class="custom-flex-row items-center w-full justify-between accordion">
                <a href="#">Help</a>
                <img src="/catalog/view/theme/web/img/top menu/navigation-bottom-arrow.svg" style="width: 15px;" class="bottom-arrow"
                     alt="Bottom Arrow" />
            </div>
            <div class="panel">
                <div class="mobile-dropdown-panel custom-flex-col w-full">
                    <a href="/troubleshooting">
                        <p class="menu-dropdown-title">Troubleshooting</p>
                        <p class="menu-dropdown-item-desc">In case you encounter any problems <br> with the process</p>
                    </a>
                </div>
            </div>
        </li>

        <li class="navigation-menu-item">
            <a href="/index.php?route=main/faq">FAQ</a>
        </li>
        <li class="navigation-menu-item">
            <a href="/index.php?route=main/contact">Contact</a>
        </li>
         <?php if (count($languages) > 1) { ?>
           <li class="navigation-menu-item dropdown-tab custom-flex-row items-center">
           <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
             <div id="language" style="padding-bottom:29px;"><?php echo $text_language; ?><br />
               <?php foreach ($languages as $language) { ?>
                 &nbsp;&nbsp;<img src="image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" onclick="setLanguageAndSubmit('<?php echo $language['code']; ?>');" style="cursor:pointer" />
               <?php } ?>
               <input type="hidden" name="language_code" id="language_code" value="" />
               <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
             </div>
           </form>
           
            </li>
           <?php } ?>
    </ul>
   </div>
<?php }else{ ?>

   <div class="top-navigation">
    <div class="navigation-wrapper">

        <a href="/index.php?route=main/home">
            <img src="/catalog/view/theme/web/img/logo.png" alt="Logo Image" class="logo-img" />
        </a>
        <ul class="navigation-menu">
           <?php if (count($languages) > 1) { ?>
           <li class="navigation-menu-item dropdown-tab custom-flex-row items-center">
           <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
             <div id="language" style="padding-bottom:29px;"><?php echo $text_language; ?><br />
               <?php foreach ($languages as $language) { ?>
                 &nbsp;&nbsp; <img src="image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" onclick="setLanguageAndSubmit('<?php echo $language['code']; ?>');" style="cursor:pointer" />
               <?php } ?>
               <input type="hidden" name="language_code" id="language_code" value="" />
               <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
             </div>
           </form>
           
            </li>
           <?php } ?>

            <li class="navigation-menu-item dropdown-tab custom-flex-row items-center">
                <a href="#">Servicio</a>
                <img src="/catalog/view/theme/web/img/top menu/navigation-bottom-arrow.svg" class="bottom-arrow"
                     alt="Bottom Arrow" />
                <div class="menu-dropdown dropdown-tab-drop">
                    <a href="/index.php?route=main/home" class="menu-item-with-icon">
                        <img src="/catalog/view/theme/web/img/top menu/Unlock-By-IMEI.png" alt="Unlock By IMEI" />
                        <div>
                            <p class="menu-dropdown-item-title">Desbloqueo por IMEI</p>
                            <p class="menu-dropdown-item-desc">Desbloquee su teléfono usando el número  <br />IMEI de 16 dígitos.
                            </p>
                        </div>
                    </a>

                    <div class="menu-item-with-icon menu-item-coming-soon">
                        <img src="/catalog/view/theme/web/img/top menu/Unlock-By-Cable.png" alt="Unlock By Cable" />
                        <div>
                            <p class="menu-dropdown-item-title">Desbloqueo por cable</p>
                            <p class="menu-dropdown-item-desc">¡Muy pronto!</p>
                        </div>
                    </div>

                    <div class="menu-item-coming-soon">
                        <p class="menu-dropdown-title">Comprobación del estado del bloqueo de la tarjeta SIM</p>
                        <p class="menu-dropdown-item-desc">¡Muy pronto!</p>
                    </div>
                </div>
            </li>

            <li class="navigation-menu-item">
                <a href="/howit2">Así Funcion</a>
            </li>

            <li class="navigation-menu-item dropdown-tab custom-flex-row items-center">
                <a href="#">Ayuda</a>
                <img src="/catalog/view/theme/web/img/top menu/navigation-bottom-arrow.svg" class="bottom-arrow"
                     alt="Bottom Arrow" />
                <div class="menu-dropdown dropdown-tab-drop">
                    <a href="/troubleshooting">
                        <p class="menu-dropdown-title">Solución de Problemas</p>
                        <p class="menu-dropdown-item-desc">En caso de que encuentre algún  <br> problema con el proceso</p>
                    </a>
                </div>
            </li>

            <li class="navigation-menu-item">
                <a href="/index.php?route=main/faq">FAQ</a>
            </li>

            <li class="navigation-menu-item">
                <a href="/index.php?route=main/contact">Contacto</a>
            </li>
        </ul>
        <div class="cart-action-row custom-flex-row items-center">
            <a href="/index.php?route=main/status">Verificar Estado de Orden</a>
            <a href="/index.php?route=main/checkout" class="cart-btn custom-flex-row items-center justify-center">
                <img src="/catalog/view/theme/web/img/top menu/cart.svg" alt="Cart" />
            </a>
            <div class="mobile-hamburger" onclick="toggleMenu()">
                <img src="/catalog/view/theme/web/img/shared/mobile-menu.svg" alt="Mobile Menu" style="display: block;"
                     id="open-mobile-menu" />
                <img src="/catalog/view/theme/web/img/shared/mobile-menu-close.svg" style="display: none;" alt="Close Mobile Menu"
                     id="close-mobile-menu" />
            </div>
        </div>
    </div>
   </div>


   <div class="mobile-responsive-menu" id="mobile-responsive-menu" style="display: none;">
    <ul class="mobile-navigation-menu">
        <li class="navigation-menu-item">
            <a href="/index.php?route=main/status">Verificar Estado de Orden</a>
        </li>
        <li class="navigation-menu-item">
            <a href="/index.php?route=main/checkout">Carro</a>
        </li>
        <li class="navigation-menu-item dropdown-tab custom-flex-col">
            <div class="custom-flex-row items-center w-full justify-between accordion">
                <a href="#">Servicio</a>
                <img src="/catalog/view/theme/web/img/top menu/navigation-bottom-arrow.svg" style="width: 15px;" class="bottom-arrow"
                     alt="Bottom Arrow" />
            </div>
            <div class="panel">
                <div class="mobile-dropdown-panel custom-flex-col w-full">
                    <a href="/index.php?route=main/home" class="menu-item-with-icon">
                        <img src="/catalog/view/theme/web/img/top menu/Unlock-By-IMEI.png" alt="Unlock By IMEI" />
                        <div>
                            <p class="menu-dropdown-item-title">Desbloqueo por IMEI</p>
                            <p class="menu-dropdown-item-desc">Desbloquee su teléfono usando el número  <br />IMEI de 16 dígitos.
                            </p>
                        </div>
                    </a>

                    <div class="menu-item-with-icon menu-item-coming-soon">
                        <img src="/catalog/view/theme/web/img/top menu/Unlock-By-Cable.png" alt="Unlock By Cable" />
                        <div>
                            <p class="menu-dropdown-item-title">Desbloqueo por cable</p>
                            <p class="menu-dropdown-item-desc">¡Muy pronto!</p>
                        </div>
                    </div>

                    <div class="menu-item-coming-soon">
                        <p class="menu-dropdown-title">Comprobación del estado del bloqueo de la tarjeta SIM</p>
                        <p class="menu-dropdown-item-desc">¡Muy pronto!</p>
                    </div>
                </div>
            </div>

        </li>

        <li class="navigation-menu-item">
            <a href="/howit2">Así Funcion</a>
        </li>

        <li class="navigation-menu-item dropdown-tab custom-flex-col">
            <div class="custom-flex-row items-center w-full justify-between accordion">
                <a href="#">Ayuda</a>
                <img src="/catalog/view/theme/web/img/top menu/navigation-bottom-arrow.svg" style="width: 15px;" class="bottom-arrow"
                     alt="Bottom Arrow" />
            </div>
            <div class="panel">
                <div class="mobile-dropdown-panel custom-flex-col w-full">
                    <a href="/troubleshooting">
                        <p class="menu-dropdown-title">Solución de problemas</p>
                        <p class="menu-dropdown-item-desc">En caso de que encuentre algún  <br> problema con el proceso</p>
                    </a>
                </div>
            </div>
        </li>

        <li class="navigation-menu-item">
            <a href="/index.php?route=main/faq">FAQ</a>
        </li>
        <li class="navigation-menu-item">
            <a href="/index.php?route=main/contact">Contacto</a>
        </li>
         <?php if (count($languages) > 1) { ?>
           <li class="navigation-menu-item dropdown-tab custom-flex-row items-center">
           <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
             <div id="language" style="padding-bottom:29px;"><?php echo $text_language; ?><br />
               <?php foreach ($languages as $language) { ?>
                 &nbsp;&nbsp;<img src="image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" onclick="setLanguageAndSubmit('<?php echo $language['code']; ?>');" style="cursor:pointer" />
               <?php } ?>
               <input type="hidden" name="language_code" id="language_code" value="" />
               <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
             </div>
           </form>
           
            </li>
           <?php } ?>
    </ul>
   </div>
<?php }  ?>

<!-- Top Navigation -->
<script>
function setLanguageAndSubmit(code) {
  var languageInput = document.getElementById('language_code');
  languageInput.value = code; // Set the language code
  languageInput.form.submit(); // Submit the form containing this input
}

</script>
