<?php

/**
 * Class ControllerCommonAutomation
 * @property ModelReferralReferral $model_referral_referral
 */
class ControllerCommonAutomation extends Controller {

    public function referralCheckLockedTransactions() {
        $this->load->model("referral/referral");

        $min_days = $this->config->get("config_referral_add_lock_time");
        $locked_referrals = $this->model_referral_referral->getLockedReferrals($min_days);

        foreach($locked_referrals as $locked_referral) {
            $order_id = $locked_referral["reference_id"];
            $this->model_referral_referral->markOrderPaid($order_id, true);
        }

    }

    public function test_fraud_email() {
        $this->load->model("fraud/fraud");

        $this->model_fraud_fraud->sendFraudWarningEmail(330197);
    }

    public function bulk() {
        $email = $this->request->post["email"];
        $carrier_id = $this->request->post["carrier_id"];
        $category_id = $this->request->post["category_id"];
        $product_id = $this->request->post["product_id"];
        $imeis = explode(",", $this->request->post["imeis"]);
        $force = false;

        $result_ids = array();

        foreach($imeis as $imei) {
            $c = curl_init();
            curl_setopt($c, CURLOPT_URL, HTTPS_BASE . "index.php?route=checkout/cart/update");
            curl_setopt($c, CURLOPT_COOKIEJAR, "/dev/null");
            curl_setopt($c, CURLOPT_COOKIEFILE, "/dev/null");
            curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($c, CURLOPT_COOKIESESSION, true);

            $data = array(
                "email" => $email,
                "carrier_id" => $carrier_id,
                "category_id" => $category_id,
                "product_id" => $product_id,
                "imei" => $imei,
                "force" => false
            );

            curl_setopt($c, CURLOPT_POST, true);
            curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($data));

            $result = curl_exec($c);

            curl_setopt($c, CURLOPT_URL, HTTPS_BASE . "index.php?route=main/checkout");
            curl_setopt($c, CURLOPT_POSTFIELDS, false);
            curl_setopt($c, CURLOPT_POST, false);

            curl_exec($c);

            curl_setopt($c, CURLOPT_URL, HTTPS_BASE . "index.php?route=payment/generic/create_order&fp=MANUAL&type=Billing");
            curl_setopt($c, CURLOPT_POST, false);

            $result = curl_exec($c);

            curl_close($c);
            array_push($result_ids, json_decode($result)->raw);

        }

        $this->response->addHeader("Content-Type: application/json");
        $this->response->setOutput(json_encode($result_ids));
    }

    public function test_order() {
        $this->load->model("checkout/order");
        $order_id = 470708;

        $this->model_checkout_order->customeremail($order_id, 2, "shamdog@gmail.com", 1);


    }

    public function test_get_details() {
        /* Create a merchantAuthenticationType object with authentication details
         retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(\SampleCodeConstants::MERCHANT_LOGIN_ID);
        $merchantAuthentication->setTransactionKey(\SampleCodeConstants::MERCHANT_TRANSACTION_KEY);

        // Set the transaction's refId
        // The refId is a Merchant-assigned reference ID for the request.
        // If included in the request, this value is included in the response. 
        // This feature might be especially useful for multi-threaded applications.
        $refId = 'ref' . time();

        $request = new AnetAPI\GetTransactionDetailsRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setTransId($transactionId);

        $controller = new AnetController\GetTransactionDetailsController($request);

        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);

    }

}