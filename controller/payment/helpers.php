<?php

/**
 * Class ControllerPaymentPPStandard
 * @property ModelCheckoutOrder $model_checkout_order
 * @property ModelReferralReferral $model_referral_referral
 * @property ModelFraudFraud $model_fraud_fraud
 * @property ModelCatalogManufacturer $model_catalog_manufacturer
 * @property Currency $currency
 * @property Cart cart
 */
class ControllerPaymentHelpers extends Controller {
    public function index() {

        $this->language->load('payment/helpers');
        $this->load->model('catalog/manufacturer');

        $this->data['email'] = isset($this->session->data['email']) ? $this->session->data['email'] : "";
        $this->data['greetings'] = $this->language->get('greetings');
        $this->data['language'] = $this->session->data['language'];

        $notes = array(
            "Client has the following items in his cart: "
        );
        foreach($this->cart->getProducts() as $product) {
            $carrier = $this->model_catalog_manufacturer->getManufacturer($product['carrier']);

            $notes[] = sprintf('%s, carrier: %s, IMEI: %s', $product['name'], htmlspecialchars_decode($carrier['name']), $product['imei']);
        };

        $this->data['notes'] = implode(" ", $notes);
        $this->data['payment_cancelled'] = isset($this->session->data['payment_cancelled']) ? $this->session->data['payment_cancelled'] : false;

        unset($this->session->data['payment_cancelled']);


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/helpers.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/helpers.tpl';
        } else {
            $this->template = 'default/template/payment/helpers.tpl';
        }

        $this->render();
    }
}
?>