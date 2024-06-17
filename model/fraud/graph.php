<?php

/**
 * @property Loader load
 * @property ModelCheckoutOrder model_checkout_order
 */
class ModelFraudGraph extends Model {

    private $_url = "http://worker-large.devsti.me:9050/order/UP";

    public function addOrder($order_id) {

        $this->load->model("checkout/order");
        $order = $this->model_checkout_order->getOrder($order_id);
        $order_details = $this->model_checkout_order->getOrderProducts($order_id);

        $emails = array($order["email"]);

        if($order["payment_method"] == "pp_standard") {
            $payment_email = $order["shipping_email"];
            if($payment_email) {
                array_push($emails, $payment_email);
            }

            $card_fingerprint = false;
        } else {
            $card_fingerprint = $this->model_checkout_order->getOrderCardFingerprint($order_id);
        }

        $request = array(
            "order_id" => (string)$order_id,
            "date_added" => $order["date_added"],
            "name" => $order["firstname"] . " " . $order["lastname"],
            "fingerprint" => $order["fingerprint"],
            "uuid" => $order["uuid"],
            "ip" => $order["ip"],
            "imei" => $order_details[0]["imei"],
            "emails" => $emails,
            "card_fingerprint" => $card_fingerprint,
            "expected_fraud" => false,
            "has_chargeback" => $order["order_status_id"] == "13"
        );

        $fraud_module_enabled = $this->config->get("config_enable_related_orders_admin");
        if($fraud_module_enabled == '1') {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->_url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            $response  = curl_exec($ch);
            curl_close($ch);
        }
    }
}