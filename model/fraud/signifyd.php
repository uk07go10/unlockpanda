<?php

/**
 * @property Loader load
 * @property ModelCheckoutOrder $model_checkout_order
 */
class ModelFraudSignifyd extends Model {

    private $_request = array(
        "purchase" => array(
            "browserIpAddress" => "",
            "orderId" => "",
            "createdAt" => "",
            "paymentGateway" => "", // stripe or p
            "paymentMethod" => "credit_card",
            "currency" => "USD",
            "avsResponseCode" => "",
            "cvvResponseCode" => "",
            "orderChannel" => "WEB",
            "totalPrice" => 0.0
        ),
        "card" => array(
            "cardHolderName" => "",
            "last4" => "",
            "expiryMonth" => "",
            "expiryYear" => "",
            "billingAddress" => array(
                "streetAddress" => "",
                "city" => "",
                "postalCode" => "",
                "countryCode" => ""
            )
        ),
        "userAccount" => array(
            "email" => "",
            "username" => ""
        ),
        "seller" => array(
            "name" => "UnlockRiver.com",
            "domain" => "unlockriver.com"
        )
    );

    //todo: products?

    private $_api_url = "https://api.signifyd.com/v2/cases";
    private $_api_key = "r4aeMet7zPLkoJ2ieHBB6gWH8"; //todo: to param

    public function setKey($key) {
        $this->_api_key = $key;
    }

    public function investigateStripe($request, $card, $order_id) {
        $this->load->model("checkout/order");

        $order = $this->model_checkout_order->getOrder($order_id);
        $order_products = $this->model_checkout_order->getOrderProducts($order_id);
        $now = new DateTime();

        $this->_request["purchase"]["browserIpAddress"] = $request->server["REMOTE_ADDR"];
        $this->_request["purchase"]["orderId"] = (string)$order_id;
        $this->_request["purchase"]["createdAt"] = $now->format(DateTime::ATOM);
        $this->_request["purchase"]["paymentGateway"] = "stripe";
        $this->_request["purchase"]["avsResponseCode"] = $this->stripeAVSChecks(
            $card->address_line1_check, $card->address_zip_check
        );
        $this->_request["purchase"]["cvvResponseCode"] = $this->stripeCVVChecks(
            $card->cvc_check
        );
        $this->_request["purchase"]["totalPrice"] = $order["total"];

        $this->_request["card"]["cardHolderName"] = $card->name;
        $this->_request["card"]["last4"] = $card->last4;
        $this->_request["card"]["expiryMonth"] = $card->exp_month;
        $this->_request["card"]["expiryYear"] = $card->exp_year;
        $this->_request["card"]["billingAddress"]["streetAddress"] = $card->address_line1;
        $this->_request["card"]["billingAddress"]["city"] = $card->address_city;
        $this->_request["card"]["billingAddress"]["postalCode"] = $card->address_zip;
        $this->_request["card"]["billingAddress"]["countryCode"] = $card->country;

        $this->_request["userAccount"]["email"] = $order["email"];
        $this->_request["userAccount"]["username"] = $order["email"];

        return $this->_makeRequest($this->_request);
    }

    private function stripeAVSChecks($address_line_check, $address_zip_check) {
        $check_array = array(
            "pass" => array(
                "pass" => "Y",
                "fail" => "A",
                "unchecked" => "B"
            ),
            "fail" => array(
                "pass" => "Z",
                "fail" => "N",
                "unchecked" => "N"
            ),
            "unchecked" => array(
                "pass" => "P",
                "fail" => "N",
                "unchecked" => "U"
            )
        );

        if(array_key_exists($address_line_check, $check_array) &&
            array_key_exists($address_zip_check, $check_array[$address_line_check])) {
            return $check_array[$address_line_check][$address_zip_check];
        }

        return "";

    }

    private function stripeCVVChecks($cvc_check) {
        $check_array = array(
            "pass" => "M",
            "fail" => "N",
            "unchecked" => "P"
        );

        if(array_key_exists($cvc_check, $check_array)) {
            return $check_array[$cvc_check];
        }

        return "";
    }

    public function queryCase($case_id) {
        return $this->_makeRequest(false, "/" . $case_id);
    }

    private function _makeRequest($data=false, $url_append="") {
        $url = $this->_api_url . $url_append;

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_HEADER, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);

        if($data) {
            curl_setopt($c, CURLOPT_POST, true);
            curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($data));
        }

        curl_setopt($c, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Basic " . base64_encode($this->_api_key)
        ));

        $response = curl_exec($c);
        $response_decoded = json_decode($response);

        return $response_decoded;
    }

}