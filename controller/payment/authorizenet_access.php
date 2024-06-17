<?php

use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

/**
 * Class ControllerPaymentAuthorizeNetAccess
 * @property ModelReferralReferral $model_referral_referral
 * @property ModelCheckoutOrder $model_checkout_order
 * @property ModelFraudFraud $model_fraud_fraud
 */
class ControllerPaymentAuthorizeNetAccess extends Controller {
    public function index() {

        if(!array_key_exists("new", $this->request->get)) {
            // production
            $this->data['authorizenet_access_enabled'] = $this->config->get('authorizenet_access_enabled_production');
            $this->data['authorizenet_access_api_login_id'] = $this->config->get('authorizenet_access_production_api_login_id');
            $this->data['authorizenet_access_transaction_key'] = $this->config->get('authorizenet_access_production_transaction_key');
            $this->data['authorizenet_gateway_url'] = 'https://accept.authorize.net/payment/payment';
            $this->data['authorizenet_access_mode'] = 'p';
        } else {
            // test (?new)
            $this->data['authorizenet_access_enabled'] = $this->config->get('authorizenet_access_enabled_new');
            $this->data['authorizenet_access_api_login_id'] = $this->config->get('authorizenet_access_new_api_login_id');
            $this->data['authorizenet_access_transaction_key'] = $this->config->get('authorizenet_access_new_transaction_key');
            $this->data['authorizenet_gateway_url'] = 'https://test.authorize.net/payment/payment';
            $this->data['authorizenet_access_mode'] = 'n';
        }

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
            $this->data['authorizenet_access_payment_button_text'] = $this->config->get('authorizenet_access_payment_button_text_en');
        } else {
            $this->data['authorizenet_access_payment_button_text'] = $this->config->get('authorizenet_access_payment_button_text_es');
        }


        $this->data['language'] = $this->session->data['language'];

        $this->template = 'ur/template/payment/authorizenet_access.tpl';
        $this->render();
    }

    public function session() {

        if(isset($this->request->get['v']) && $this->request->get['v'] == "n") {
            $api_login_id = $this->config->get('authorizenet_access_new_api_login_id');
            $transaction_key = $this->config->get('authorizenet_access_new_transaction_key');
            $environment = \net\authorize\api\constants\ANetEnvironment::SANDBOX;
        } else {
            $api_login_id = $this->config->get('authorizenet_access_production_api_login_id');
            $transaction_key = $this->config->get('authorizenet_access_production_transaction_key');
            $environment = \net\authorize\api\constants\ANetEnvironment::PRODUCTION;
        }

        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($api_login_id);
        $merchantAuthentication->setTransactionKey($transaction_key);

        $this->load->model("fraud/fraud");

        $json = array(
            "result" => true,
            "id" => null,
            "error" => array(
                "step" => 0,
                "message" => ""
            ));

        $data = array();
        $order_id = false;

        try {
            if(!$this->session->data['order_info']) {
                throw new Exception("No order_info provided");
            }

            $data['customer_id'] = $this->session->data['order_info']['customer_id'];
            $data['firstname'] = $this->session->data['order_info']['firstname'];
            $data['lastname'] = $this->session->data['order_info']['lastname'];
            $data['email'] = trim(strtolower($this->session->data['order_info']['email']));
            $data['telephone'] = isset($this->session->data['phone']) ? $this->session->data['phone'] : '';
            $data['payment_method'] = 'authorizenet';
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
            $data['shipping_firstname'] = isset($this->session->data['magnetosim']) ? $this->session->data['magnetosim']['shipping_firstname'] : '';
            $data['shipping_lastname'] = isset($this->session->data['magnetosim']) ? $this->session->data['magnetosim']['shipping_lastname'] : '';
            $data['shipping_email'] = isset($this->session->data['magnetosim']) ? $this->session->data['magnetosim']['shipping_email'] : '';
            $data['shipping_company'] = "";
            $data['shipping_address_1'] = isset($this->session->data['magnetosim']) ? $this->session->data['magnetosim']['shipping_address_1'] : '';
            $data['shipping_address_2'] = isset($this->session->data['magnetosim']) ? $this->session->data['magnetosim']['shipping_address_2'] : '';
            $data['shipping_city'] = isset($this->session->data['magnetosim']) ? $this->session->data['magnetosim']['shipping_city'] : '';
            $data['shipping_postcode'] = isset($this->session->data['magnetosim']) ? $this->session->data['magnetosim']['shipping_postcode'] : '';
            $data['shipping_zone'] = isset($this->session->data['magnetosim']) ? $this->session->data['magnetosim']['shipping_zone'] : '';
            $data['shipping_zone_id'] = "";
            $data['shipping_country'] = isset($this->session->data['magnetosim']) ? $this->session->data['magnetosim']['shipping_country'] : '';
            $data['shipping_country_id'] = "";
            $data['text'] = $this->language->get('text_iphone_guest');
            $data['uuid'] = $this->ga->uuid;
            $data['fingerprint'] = isset($this->request->get["fp"]) ? $this->request->get["fp"] : false;

            $this->load->model('checkout/order');
            $this->load->library('encryption');
            $encryption = new Encryption($this->config->get('config_encryption'));

            $order_id = $this->model_checkout_order->createunpaid($data);
            $ua_log = new Log("ua_log.txt");
            $ua_log->write(sprintf("%s: %s", $order_id, json_encode($_SERVER)));
            $this->session->data["unpaid_order_id"] = $order_id;
            $json['id'] = $order_id;
            $json['error']['step'] = 1;

            if(isset($this->session->data["gift"])) {
                $this->model_checkout_order->saveGiftInfo($order_id, "CHRISTMAS2016", $this->session->data["gift"]);
            }

            $this->load->model("referral/referral");
            $ref_email = isset($this->request->cookie['r']) ?
                $this->model_referral_referral->referralCodeToEmail($this->request->cookie['r']) : false;
            if($ref_email !== false) {
                $this->model_referral_referral->addOrderToReferral($ref_email, $order_id);
            }


            $currency = $this->session->data['currency'];
            $total_data = array();
            $total = $this->cart->getSubTotal();
            $taxes = $this->cart->getTaxes();

            $this->load->model('total/coupon');
            $this->model_total_coupon->getTotal($total_data, $total, $taxes);

            $amount = $this->currency->format($total, $currency, false, false);
            $metadata = array(
                "order_id" => $order_id,
                "order_email" => strtolower($this->session->data['order_info']['email'])
            );

            //create a transaction
            $transactionRequestType = new AnetAPI\TransactionRequestType();
            $transactionRequestType->setTransactionType("authCaptureTransaction");
            $transactionRequestType->setAmount($amount);
            $transactionRequestType->setRefTransId($order_id);

            //add the values for transaction settings
            $duplication = new AnetAPI\SettingType();
            $duplication->setSettingName("duplicateWindow");
            $duplication->setSettingValue("0");

            $transactionRequestType->addToTransactionSettings($duplication);

            // Set Hosted Form options
            $setting1 = new AnetAPI\SettingType();
            $setting1->setSettingName("hostedPaymentButtonOptions");
            $setting1->setSettingValue("{\"text\": \"Pay\"}");

            $setting2 = new AnetAPI\SettingType();
            $setting2->setSettingName("hostedPaymentOrderOptions");
            $setting2->setSettingValue("{\"show\": true, \"merchantName\": \"" . $this->config->get('config_name') . "\"}");

            $setting3 = new AnetAPI\SettingType();
            $setting3->setSettingName("hostedPaymentReturnOptions");
            $setting3->setSettingValue("{\"url\": \"https://www.unlockpanda.com/index.php?route=main/checkout/completed\", \"cancelUrl\": \"https://www.unlockpanda.com/index.php?route=main/checkout\", \"showReceipt\": true}");

            $setting4 = new AnetAPI\SettingType();
            $setting4->setSettingName("hostedPaymentPaymentOptions");
            $setting4->setSettingValue("{\"cardCodeRequired\": true, \"showCreditCard\": true, \"showBankAccount\": false}");



            $this->load->model('catalog/manufacturer');

            foreach ($this->cart->getProducts() as $product) {

                $item = new AnetAPI\LineItemType();
                $manufacturer = $this->model_catalog_manufacturer->getManufacturer($product['carrier']);

                $item->setItemId($product["product_id"]);
                $item->setName(substr(htmlspecialchars_decode($product['name']) . " unlock", 0, 24));
                $item->setDescription("Carrier: " . htmlspecialchars_decode($manufacturer["name"]) . ", IMEI: " . $product["imei"]);
                $item->setUnitPrice($this->currency->format($product["total"], $currency, false, false));
                $item->setQuantity($product["quantity"]);

                $transactionRequestType->addToLineItems($item);
            }

            $order = new AnetAPI\OrderType();
            $order->setInvoiceNumber($order_id);
            $order->setDescription("Order");
            $transactionRequestType->setOrder($order);

            // Build transaction request
            $request = new AnetAPI\GetHostedPaymentPageRequest();
            $request->setMerchantAuthentication($merchantAuthentication);
            $request->setTransactionRequest($transactionRequestType);

            $request->addToHostedPaymentSettings($setting1);
            $request->addToHostedPaymentSettings($setting2);
            $request->addToHostedPaymentSettings($setting3);
            $request->addToHostedPaymentSettings($setting4);

            //execute request
            $controller = new AnetController\GetHostedPaymentPageController($request);
            $response = $controller->executeWithApiResponse($environment);

            if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
                $json["id"] = $response->getToken();
            } else {
                $json["result"] = false;
                $json["error"] = array();

                foreach($response->getMessages()->getMessage() as $error) {
                    $details = array(
                        "code" => $error->getCode(),
                        "text" => $error->getText()
                    );

                    array_push($json["error"], $details);

                    $this->notifier->add(
                        (new Notification())
                            ->setError("AuthorizeNetAccess", "Query error caught - " . $error->getCode() . ", "  .$error->getText())
                            ->setMetadata($details)
                    )->notify();
                }
            }

        } catch (Exception $e) {
            $transaction_log_text = $e->getMessage();

            $json['result'] = false;
            $json['error']['message'] = $transaction_log_text;
            if($order_id !== false) {
                $this->model_checkout_order->update($order_id, "10", $transaction_log_text);
            }

            $this->log->write("Create unpaid error: " . $e->getMessage());

            $this->notifier->add(
                (new Notification())
                    ->setError("AuthorizeNetAccess", "General error caught - " . $e->getMessage())
                    ->setMetadata($data)
            )->notify();
        }

        header('Content-Type: application/json');
        echo json_encode($json);
        die();
    }

    public function notify_chargeback_emsdata() {
        $authorizenet_access_log = new Log("authorizenet_access.txt");

        $body = file_get_contents('php://input');
        $event_json = json_decode($body);

        $authorizenet_access_log->write($body);

        $this->load->model('checkout/order');
        $this->load->model('fraud/fraud');
        $this->load->model('fraud/graph');
        $this->load->model('referral/referral');

        $reference_number = $event_json->reference_number;
        $batch_date = $event_json->batch_date;
        $amount = $event_json->amount;
        $card_last_4 = $event_json->card_last_4;

        $order_id = $this->model_checkout_order->getOrderIDByCardDetails($card_last_4, $amount, $batch_date);
        if($order_id) {
            $send_notification = false;
            $order = $this->model_checkout_order->getOrder($order_id);
            if($order["order_status_id"] == "1") { // processing
                $send_notification = true;
            }

            $this->model_checkout_order->update($order_id, "13", sprintf("Case ID: %s", $reference_number));
            $this->model_fraud_fraud->addFromOrder($order_id);
            $this->model_fraud_fraud->addIMEIFromOrder($order_id);
            $this->model_checkout_order->sendMail($this->config->get("config_dev_email"),
                sprintf("AuthorizeNet chargeback created - order %s", $order_id),
                sprintf("Client has created chargeback for order %s.", $order_id)
            );
            $this->model_referral_referral->markOrderRefunded($order_id);
            $this->ga->sendTransactionToGA($order_id, "refund");

            if ($send_notification) {
                $this->model_checkout_order->sendMail(
                    array(
                        $this->config->get("config_dev_email"),
                        "emilio@unlockriver.com",
                        "support@unlockpandasupport.zendesk.com"
                    ),
                    sprintf("AuthorizeNet order chargeback created - order %s", $order_id),
                    sprintf("Client has created dispute for order %s", $order_id)
                );
            }
            $this->model_fraud_graph->addOrder($order_id);
            http_response_code(200);
        } else {
            $this->model_checkout_order->sendMail(
                array(
                    $this->config->get("config_dev_email"),
                    "emilio@unlockriver.com",
                    "support@unlockpandasupport.zendesk.com"
                ),
                sprintf("AuthorizeNet chargeback - order ID not found for #%s", $reference_number),
                sprintf("Order not found for the following params: batch date %s, amount %s, card last 4 %s", $batch_date, $amount, $card_last_4)
            );
            http_response_code(404);
        }
    }

    public function callback() {
        $authorizenet_access_log = new Log("authorizenet_access.txt");

        $body = file_get_contents('php://input');
        $event_json = json_decode($body);

        $authorizenet_access_log->write($body);

        $this->load->model('checkout/order');
        $this->load->model('fraud/fraud');
        $this->load->model('fraud/graph');
        $this->load->model('referral/referral');

        switch($event_json->eventType) {
            case "net.authorize.payment.authcapture.created": {
                $transaction_id = $event_json->payload->id;

                $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
                $merchantAuthentication->setName($this->config->get('authorizenet_access_production_api_login_id'));
                $merchantAuthentication->setTransactionKey($this->config->get('authorizenet_access_production_transaction_key'));


                $request = new AnetAPI\GetTransactionDetailsRequest();
                $request->setMerchantAuthentication($merchantAuthentication);
                $request->setTransId($transaction_id);

                $controller = new AnetController\GetTransactionDetailsController($request);

                $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);

                // order_id
                $order_id = $response->getTransaction()->getOrder()->getInvoiceNumber();
                $order = $this->model_checkout_order->getOrder($order_id);
                $products = $this->model_checkout_order->getOrderProducts($order_id);

                $first_name = $response->getTransaction()->getBillTo()->getFirstName();
                $last_name = $response->getTransaction()->getBillTo()->getLastName();

                // set name
                $this->model_checkout_order->updateOrderFirstLastNames($order_id,
                    $first_name,
                    $last_name
                );

                // set charge ID
                $this->model_checkout_order->setPaymentProviderTransactionID($order_id, $transaction_id);

                $delayed = false;
                try {
                    $this->load->model('catalog/product');

                    foreach($products as $product) {
                        $delayed = $delayed || $this->model_catalog_product->isDelayed(
                                $product['category_id'], $product['product_id'], $product['carrier_id']);
                    }

                    if($delayed) {
                        $this->model_checkout_order->sendMail(
                            $this->config->get("config_dev_email"),
                            "[ON HOLD][AuthorizeNet] New order",
                            sprintf("Order put on hold: %s", $order_id)
                        );
                    }
                } catch (Exception $e) {
                    $this->model_checkout_order->sendMail(
                        $this->config->get("config_dev_email"),
                        "[ON HOLD][AuthorizeNet] Exception",
                        $e->getMessage()
                    );
                } // delayed order handling section end

                $order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . $this->db->escape($order_id) . "'");

                foreach ($order_total_query->rows as $order_total) {
                    $this->load->model('total/' . $order_total['code']);

                    if (method_exists($this->{'model_total_' . $order_total['code']}, 'confirm')) {
                        $this->{'model_total_' . $order_total['code']}->confirm($order, $order_total);
                    }
                }

                if(isset($order['email']) && filter_var($order['email'], FILTER_VALIDATE_EMAIL)) {
                    $this->model_checkout_order->customeremail($order_id, 2, $order['email'], $order['language_id']);
                }

                $ga_log = new Log("ga");
                try {
                    $this->ga->sendTransactionToGA($order_id, "purchase");
                } catch (Exception $e) {
                    $ga_log->write($e->getMessage());
                }

                $this->model_checkout_order->update($order_id, !$delayed ? "2" : "19"); // Pending | On Hold

                $authorizenet_access_log->write("Successful AuthorizeNet transaction, order ID: " . $order_id);

                $content = sprintf("Order number: %s, language: %s\r\n\r\n", $order_id, $order['language_id'] == 1 ? "English" : "Spanish");
                $imeis = array();
                $duplicate = false;

                foreach($products as $product) {
                    $content .= sprintf("- %s, IMEI: %s, carrier: %s, price: %s\r\n", $product['name'], $product['imei'], $product['carrier'], $product['price']);
                    if(in_array($product['imei'], $imeis)) {
                        $duplicate = true;
                    }
                    array_push($imeis, $product['imei']);
                }
                $content .= sprintf("\r\nClient email: %s", $order['email']);

                $this->model_checkout_order->sendMail($this->config->get("config_billing_email"),
                    sprintf("AuthorizeNet payment received -  order ID %s", $order_id),
                    $content
                );

                if($duplicate) {
                    $this->model_checkout_order->sendMail(array(
                        "pawel@devsti.me", "support@unlockpandasupport.zendesk.com", "emilio@unlockriver.com"
                    ), "Duplicate IMEI order detected", sprintf("Please check %s.", $order_id));
                }

                $last_4 = substr($response->getTransaction()->getPayment()->getCreditCard()->getCardNumber(), -4);
                $this->model_fraud_fraud->addCardToOrder($order_id, '', $last_4, array());

                break;
            }
            case "net.authorize.payment.void.created":
            case "net.authorize.payment.refund.created": {
                $transaction_id = $event_json->payload->id;

                $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
                $merchantAuthentication->setName($this->config->get('authorizenet_access_production_api_login_id'));
                $merchantAuthentication->setTransactionKey($this->config->get('authorizenet_access_production_transaction_key'));


                $request = new AnetAPI\GetTransactionDetailsRequest();
                $request->setMerchantAuthentication($merchantAuthentication);
                $request->setTransId($transaction_id);

                $controller = new AnetController\GetTransactionDetailsController($request);

                $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);

                $txn_id = $response->getTransaction()->getRefTransId();
                if(!$txn_id) {
                    $txn_id = $response->getTransaction()->getOrder()->getInvoiceNumber();
                }
                $order_id = $this->model_checkout_order->getByTXNID($txn_id);
                if($order_id) {
                    $this->model_checkout_order->update($order_id, "11", "The refund has been confirmed by the AuthorizeNet backend.");
                    $this->model_referral_referral->markOrderRefunded($order_id);
                    $this->ga->sendTransactionToGA($order_id, "refund");
                }

                break;
            }
            default: {

                break;
            }
        }
    }
}