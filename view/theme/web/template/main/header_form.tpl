<section class="unlock-hero custom-flex-row justify-center w-full">
    <div class="unlock-hero-container w-full custom-flex-row items-center">
        <div class="unlock-hero-left">
            <h1 class="imei-unlock-hero-title relative">
                Unlock the Full Potential of Your Phone
                <img src="/catalog/view/theme/web/img/unlock/imei-unlock-hero-title-arrow.svg" alt="Arrow" class="title-arrow" />
            </h1>
            <p class="imei-unlock-hero-desc">
                Simple. Secure. Unlocked.
            </p>
            <!-- <button class="lock-status-check-btn cursor-pointer">Sim Lock Status Check</button> -->
        </div>

        <form class="imei-unlock-form custom-flex-col relative">
            <div id="error-notice" class="alert alert-danger text-center" style="display: none"></div>

            <img src="/catalog/view/theme/web/img/unlock/hero-form-arrow.svg" alt="Arrow" class="form-highlight-arrow" />
            <div class="select-with-icon">
                <img src="/catalog/view/theme/web/img/shared/carrier.svg" class="input-icons" alt="Carrier" />
                <div class="custom-dropdown w-full flex-1 relative custom-flex-row items-center">
                    <select id="carrier-select" class="rich-select">
                        <option value="-1">Select carrier.. </option>
                    <? foreach($carriers as $carrier): ?>
                        <option value="<?= $carrier['manufacturer_id'] ?>"><?php echo html_entity_decode($carrier['name']) ?></option>
                    <? endforeach; ?>
                    </select>
                </div>
            </div>


            <div class="select-with-icon">
                <img src="/catalog/view/theme/web/img/shared/manufacturer.svg" class="input-icons" alt="Manufacturer" />
                <div class="custom-dropdown w-full flex-1 relative custom-flex-row items-center">
                    <select id="brand-select" class="rich-select">
                        <option value="-1">Select brand.. </option>
                    </select>
                </div>
            </div>


            <div class="select-with-icon">
                <img src="/catalog/view/theme/web/img/shared/model.svg" class="input-icons" alt="Model" />
                <div class="custom-dropdown w-full flex-1 relative custom-flex-row items-center">
                    <select id="model-select" class="rich-select">
                        <option value="-1">Select model.. </option>
                    </select>
                </div>
            </div>

            <div class="input-with-icon">
                <img src="/catalog/view/theme/web/img/shared/imei.svg" class="input-icons" alt="IMEI" />
                <input id="imei" type="text" class="custom-input" placeholder="IMEI (15 digits)" />
            </div>

            <div class="input-with-icon">
                <img src="/catalog/view/theme/web/img/shared/email.svg" class="input-icons" alt="Email" />
                <input id="email" type="email" class="custom-input" placeholder="Type Your Email" />
            </div>

            <div class="input-with-icon">
                <input id="phone" type="text" class="custom-input" placeholder="Mobile Number (Optional)" />
            </div>

            <div id="unlock-details-row" class="row justify-content-center" style="display: none;">
                <div class="col-12">
                    <p class="mt-1 mb-1 text-center">
                        <strong>Delivery time:</strong> <span id="unlock-delivery-time"></span>
                    </p>
                </div>
            </div>

            <button id="unlock-button" class="form-submit-btn w-full">Unlock Now <span id="unlock-price"></span></button>
        </form>
    </div>
</section>