<?php

/**
 * Class ControllerPaymentAmazonPay
 * @property ModelReferralReferral $model_referral_referral
 * @property ModelPaymentAmazonPay $model_payment_amazon_pay
 * @property ModelCheckoutOrder $model_checkout_order
 */

class ControllerPaymentAmazonPay extends Controller {
    public function index() {

        if(!array_key_exists("new", $this->request->get)) {
            // production
            $this->data['amazonpay_enabled'] = $this->config->get('amazonpay_enabled_production');
            $this->data['amazonpay_merchant_id'] = $this->config->get('amazonpay_production_merchant_id');
            $this->data['amazonpay_client_id'] = $this->config->get('amazonpay_production_client_id');
            $this->data['amazonpay_script_url'] = 'https://static-na.payments-amazon.com/OffAmazonPayments/us/js/Widgets.js';
            $this->data['amazonpay_mode'] = 'p';
        } else {
            // test (?new)
            $this->data['amazonpay_enabled'] = $this->config->get('amazonpay_enabled_test');
            $this->data['amazonpay_merchant_id'] = $this->config->get('amazonpay_test_merchant_id');
            $this->data['amazonpay_client_id'] = $this->config->get('amazonpay_test_client_id');
            $this->data['amazonpay_script_url'] = 'https://static-na.payments-amazon.com/OffAmazonPayments/us/js/Widgets.js';
            //$this->data['amazonpay_script_url'] = 'https://static-na.payments-amazon.com/OffAmazonPayments/us/sandbox/js/Widgets.js';
            $this->data['amazonpay_mode'] = 'p';
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

        $this->template = 'ur/template/payment/amazon_pay.tpl';
        $this->render();
    }
    
    private function _getClient($production) {
        if($production) {
            $amazon_merchant_id = $this->config->get('amazonpay_production_merchant_id');
            $amazon_access_key = $this->config->get('amazonpay_production_access_key');
            $amazon_secret_key = $this->config->get('amazonpay_production_secret_key');
            $amazon_client_id = $this->config->get('amazonpay_production_client_id');
            $amazon_client_secret = $this->config->get('amazonpay_production_client_secret');
        } else {
            $amazon_merchant_id = $this->config->get('amazonpay_test_merchant_id');
            $amazon_access_key = $this->config->get('amazonpay_test_access_key');
            $amazon_secret_key = $this->config->get('amazonpay_test_secret_key');
            $amazon_client_id = $this->config->get('amazonpay_test_client_id');
            $amazon_client_secret = $this->config->get('amazonpay_test_client_secret');
        }

        return new AmazonPay\Client(array(
            "merchant_id" => $amazon_merchant_id,
            "access_key" => $amazon_access_key,
            "secret_key" => $amazon_secret_key,
            "client_id" => $amazon_client_id,
            "region" => "us",
            "sandbox" => !$production
        ));
    }

    public function session() {
        $is_production = !(isset($this->request->get['v']) && $this->request->get['v'] == "n");
        if($is_production) {
            $amazon_merchant_id = $this->config->get('amazonpay_production_merchant_id');
            $amazon_access_key = $this->config->get('amazonpay_production_access_key');
            $amazon_secret_key = $this->config->get('amazonpay_production_secret_key');
            $amazon_client_id = $this->config->get('amazonpay_production_client_id');
            $amazon_client_secret = $this->config->get('amazonpay_production_client_secret');
        } else {
            $amazon_merchant_id = $this->config->get('amazonpay_test_merchant_id');
            $amazon_access_key = $this->config->get('amazonpay_test_access_key');
            $amazon_secret_key = $this->config->get('amazonpay_test_secret_key');
            $amazon_client_id = $this->config->get('amazonpay_test_client_id');
            $amazon_client_secret = $this->config->get('amazonpay_test_client_secret');
        }
        
        $this->load->model("fraud/fraud");

        $json = array(
            "result" => true,
            "params"=> array(
                "sellerId" => $amazon_merchant_id,
                "returnURL" => $this->url->link('main/checkout/completed', '', 'SSL'),
                "cancelReturnURL" => $this->url->link("main/checkout", "", "SSL"),
                "accessKey" => $amazon_access_key,
                "lwaClientId" => $amazon_client_id,
                "shippingAddressRequired" => "true",
                "paymentAction" => "AuthorizeAndCapture",
                // "scope" => "profile",
                "sellerNote" => "",
                "sellerOrderId" => "",
                "amount" => "",
            ),
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
            $data['payment_method'] = 'amazonpay';
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

            $this->load->model('catalog/manufacturer');

            $notes_parts = array();
            foreach ($this->cart->getProducts() as $product) {
                $manufacturer = $this->model_catalog_manufacturer->getManufacturer($product['carrier']);
                array_push($notes_parts, htmlspecialchars_decode($product['name']) . " unlock - carrier: " . htmlspecialchars_decode($manufacturer["name"]) . ", IMEI: " . $product["imei"]);
            }
            
            $json["params"]["sellerNote"] = implode(" + ", $notes_parts);
            $json["params"]["sellerOrderId"] = $order_id;
            $json["params"]["amount"] = $amount;
            
            $this->load->model('payment/amazon_pay');
            $signature = $this->model_payment_amazon_pay->sign($amazon_secret_key, $json["params"]);
            $json["params"]["signature"] = $signature;

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
                    ->setError("AmazonPay", "General error caught - " . $e->getMessage())
                    ->setMetadata($data)
            )->notify();
        }

        header('Content-Type: application/json');
        echo json_encode($json);
        die();
    }

    public function callback() {
        $amazonpay_access_log = new Log("amazonpay_access.txt");

        if (!function_exists('getallheaders')) {
            function getallheaders() {
                $headers = [];
                foreach ($_SERVER as $name => $value) {
                    if (substr($name, 0, 5) == 'HTTP_') {
                        $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                    }
                }
                return $headers;
            }
        }
        
        $headers = getallheaders();
        $body = file_get_contents('php://input');
        
        $handler = new AmazonPay\IpnHandler($headers, $body);

        $request = $handler->toArray();
        
        $amazonpay_access_log->write(print_r($request, true));

        $this->load->model('checkout/order');
        $this->load->model('fraud/fraud');
        $this->load->model('fraud/graph');
        $this->load->model('referral/referral');
        
        switch($request["NotificationType"]) {
            case "PaymentCapture": {
                $capture_id = $request["CaptureDetails"]["AmazonCaptureId"];
                $suffix_start_pos = strrpos($capture_id, "-");
                $txn_id = substr($capture_id, 0, $suffix_start_pos);
                $capture_suffix = substr($capture_id, $suffix_start_pos + 1);
                $status = substr($capture_suffix, 0, 1);
                
                if($status !== "C") {
                    // todo: notify
                }

                $order_id = $this->model_checkout_order->getByTXNID($txn_id);
                $order = $this->model_checkout_order->getOrder($order_id);
                $products = $this->model_checkout_order->getOrderProducts($order_id);

                $client = $this->_getClient(true);
                $data = $client->getOrderReferenceDetails(array(
                    "amazon_order_reference_id" => $txn_id
                ))->toArray();
                
                $name = $data['GetOrderReferenceDetailsResult']['OrderReferenceDetails']['Destination']['PhysicalDestination']['Name'];

                $names_parts = explode(" ", $name);
                $first_name = array_shift($names_parts);

                if(count($names_parts) > 0) {
                    $last_name = implode(" ", $names_parts);
                } else {
                    $last_name = "";
                }
                
                $this->model_checkout_order->setPaymentCaptureID($order_id, $capture_suffix);

                // set name
                $this->model_checkout_order->updateOrderFirstLastNames($order_id,
                    $first_name,
                    $last_name
                );

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
                            "[ON HOLD][AmazonPay] New order",
                            sprintf("Order put on hold: %s", $order_id)
                        );
                    }
                } catch (Exception $e) {
                    $this->model_checkout_order->sendMail(
                        $this->config->get("config_dev_email"),
                        "[ON HOLD][AmazonPay] Exception",
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

                $amazonpay_access_log->write("Successful AmazonPay transaction, order ID: " . $order_id);

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
                    sprintf("AmazonPay payment received -  order ID %s", $order_id),
                    $content
                );

                if($duplicate) {
                    $this->model_checkout_order->sendMail(array(
                        "pawel@devsti.me", "support@unlockpandasupport.zendesk.com", "emilio@unlockriver.com"
                    ), "Duplicate IMEI order detected", sprintf("Please check %s.", $order_id));
                }

                $client->closeOrderReference(array(
                    "amazon_order_reference_id" => $txn_id,
                    "closure_reason" => "Processing started at third party"
                ));
                
                break;
            }

            case "PaymentRefund": {
                $capture_id = $request["RefundDetails"]["AmazonRefundId"];
                $suffix_start_pos = strrpos($capture_id, "-");
                $txn_id = substr($capture_id, 0, $suffix_start_pos);

                $order_id = $this->model_checkout_order->getByTXNID($txn_id);
                if($order_id) {
                    $this->model_checkout_order->update($order_id, "11");
                    $this->model_referral_referral->markOrderRefunded($order_id);
                    $this->ga->sendTransactionToGA($order_id, "refund");
                }

                break;
            }
        }
    }
}