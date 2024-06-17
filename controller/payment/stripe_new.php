<?php

/**
 * Class ControllerPaymentStripe
 * @property ModelReferralReferral $model_referral_referral
 * @property ModelFraudSignifyd $model_fraud_signifyd
 * @property ModelFraudFraud $model_fraud_fraud
 * @property ModelCheckoutOrder $model_checkout_order
 * @property Cart $cart
 * @property ModelTotalCoupon $model_total_coupon
 * @property Currency $currency
 * @property ModelFraudGraph model_fraud_graph
 */
class ControllerPaymentStripeNew extends Controller {
    public function index() {

        if(!array_key_exists("new", $this->request->get)) {
            // production
            $this->data['stripe_enabled'] = $this->config->get('stripe_enabled_production');
            $this->data['stripe_publishable_key'] = $this->config->get('stripe_new_publishable_key');
            $this->data['stripe_mode'] = "p";
        } else {
            // test (?new)
            $this->data['stripe_enabled'] = $this->config->get('stripe_enabled_new');
            $this->data['stripe_publishable_key'] = $this->config->get('stripe_new_publishable_key');
            $this->data['stripe_mode'] = "n";
        }

        $this->data['stripe_enabled_bitcoin'] = $this->config->get('stripe_enabled_bitcoin');
        $this->data['stripe_enabled_alipay'] = $this->config->get('stripe_enabled_alipay');
        $this->data['stripe_require_address'] = $this->config->get('stripe_require_address');

        if(isset($this->session->data['email'])){
            $this->data['email'] = $this->session->data['email'];
        } else {
            $this->data['email'] = $this->customer->getEmail();
        }
        $currency = "USD";
        $this->data['currency'] = $currency;

        $total_data = array();
        $total = $this->cart->getSubTotal();
        $taxes = $this->cart->getTaxes();

        $this->load->model('total/coupon');
        $this->model_total_coupon->getTotal($total_data, $total, $taxes);

        $amount = $this->currency->format($total, $currency, false, false) * 100;
        $this->data['amount'] = $amount;

        if($this->session->data['language'] == "en") {
            $this->data['description'] = str_replace("{count}", (string)$this->cart->countProducts(), $this->config->get('stripe_payment_title_text_en'));
            $this->data['stripe_payment_button_text'] = $this->config->get('stripe_payment_button_text_en');
        } else {
            $this->data['description'] = str_replace("{count}", (string)$this->cart->countProducts(), $this->config->get('stripe_payment_title_text_es'));
            $this->data['stripe_payment_button_text'] = $this->config->get('stripe_payment_button_text_es');
        }


        $this->data['language'] = $this->session->data['language'];
        $this->template = 'ur/template/payment/stripe_migrated.tpl';
        

        $this->render();
    }
}