<?php

/**
 * Class ControllerPaymentGeneric
 * @property ModelFraudFraud model_fraud_fraud
 * @property ModelReferralReferral model_referral_referral
 * @property ModelCheckoutOrder model_checkout_order
 * @property Log log
 * @property Currency currency
 */
class ControllerPaymentGeneric extends Controller {

    public function create_order() {
        // create an unpaid order

        $this->load->model("fraud/fraud");
        $json = array();

        if(isset($this->request->get["type"]) && in_array($this->request->get["type"], array("Login", "Billing"))) {
            $this->session->data["pp_payment_type"] = $this->request->get["type"];
        } else {
            $this->session->data["pp_payment_type"] = "Login";
        }

        try {
            if(!isset($this->session->data['order_info'])) {
                throw new Exception("No order info!");
            }

            $data['customer_id'] = $this->session->data['order_info']['customer_id'];
            $data['firstname'] = $this->session->data['order_info']['firstname'];
            $data['lastname'] = $this->session->data['order_info']['lastname'];
            $data['email'] = strtolower($this->session->data['order_info']['email']);
            $data['telephone'] = isset($this->session->data['phone']) ? $this->session->data['phone'] : '';
            $data['payment_method'] = 'pp_standard';
            $data['total'] = $this->session->data['order_info']['total'];
            $data['language_id'] = $this->config->get('config_language_id');
            $data['currency_id'] = $this->currency->getId();
            $data['currency_code'] = $this->currency->getCode();
            $data['currency_value'] = $this->currency->getValue($this->currency->getCode());
            $data['ip'] = $this->request->server['REMOTE_ADDR'];    
            $data['products'] = $this->session->data['order_info']['products'];

            $total_data = array();
            $total = 0;
            $taxes = $this->cart->getTaxes();

            $this->load->model('setting/extension');

            $sort_order = array();
            $results = $this->model_setting_extension->getExtensions('total');
            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
            }

            array_multisort($sort_order, SORT_ASC, $results);

            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('total/' . $result['code']);

                    $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
                }
            }

            $sort_order = array();

            foreach ($total_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $total_data);

            $data['totals'] = $total_data;

            // $shipping_address = $this->session->data['guest']['shipping'];
            $data['shipping_firstname'] = "";
            $data['shipping_lastname'] = "";
            $data['shipping_email'] = "";
            $data['shipping_company'] = "";
            $data['shipping_address_1'] = "";
            $data['shipping_address_2'] = "";
            $data['shipping_city'] = "";
            $data['shipping_postcode'] = "";
            $data['shipping_zone'] = "";
            $data['shipping_zone_id'] = "";
            $data['shipping_country'] = "";
            $data['shipping_country_id'] = "";
            $data['shipping_address_format'] = "";
            $data['text'] = $this->language->get('text_iphone_guest');
            $data['uuid'] = $this->ga->uuid;
            $data['fingerprint'] = isset($this->request->get["fp"]) ? $this->request->get["fp"] : false;

            $this->load->model('checkout/order');
            $this->load->library('encryption');
            $encryption = new Encryption($this->config->get('config_encryption'));

            $json = array();
            $order_id = $this->model_checkout_order->createunpaid($data);

            $this->load->model("referral/referral");
            $ref_email = isset($this->request->cookie['r']) ?
                $this->model_referral_referral->referralCodeToEmail($this->request->cookie['r']) : false;
            if($ref_email !== false) {
                $this->model_referral_referral->addOrderToReferral($ref_email, $order_id);
            }

            // $result = $this->model_fraud_fraud->handleOrderCheck($order_id, false);
            $ua_log = new Log("ua_log.txt");
            $ua_log->write(sprintf("%s: %s", $order_id, json_encode($_SERVER)));

            $this->session->data["unpaid_order_id"] = $order_id;
            $json['id'] = $encryption->encrypt($order_id);
            $json['raw'] = $order_id;
            $json['result'] = true;

            if(isset($this->session->data["gift"])) {
                $this->model_checkout_order->saveGiftInfo($order_id, "CHRISTMAS2016", $this->session->data["gift"]);
            }

        } catch (Exception $e) {
            $this->log->write("Create unpaid error:");
            $this->log->write($e->getMessage());

            $this->notifier
                ->add(
                    (new Notification())
                        ->setException($e)
                )->notify();

            $json['result'] = false;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}