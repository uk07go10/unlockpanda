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
class ControllerPaymentStripe extends Controller {
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

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/stripe.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/stripe.tpl';
        } else {
            $this->template = 'default/template/payment/stripe.tpl';
        }

        $this->render();
    }

    public function session() {
        // create charge

        $start_time = microtime(true);

        $this->load->model("fraud/fraud");

        if(isset($this->request->get['v']) && $this->request->get['v'] == "n") {
            $api_key = $this->config->get('stripe_new_secret_key');
        } else {
            $api_key = $this->config->get('stripe_production_secret_key');
        }

        \Stripe\Stripe::setApiKey($api_key);
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
            $data['payment_method'] = 'stripe';
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
            
            $metadata = array(
                "order_id" => $order_id,
                "order_email" => strtolower($this->session->data['order_info']['email'])
            );

            $this->load->model('catalog/manufacturer');
            $line_items = array();

            $imeis = array();
            foreach ($this->cart->getProducts() as $product) {
                array_push($imeis, $product["imei"]);
            }

            $imeis = implode(", ", $imeis);

            array_push($line_items, array(
                "amount" => $this->currency->format($total, $currency, false, false) * 100,
                "currency" => "usd",
                "name" => sprintf("Order ID #%s", $order_id),
                "quantity" => 1,
                "description" => sprintf("%s unlock(s), IMEIs: %s", $this->cart->countProducts(), $imeis)
            ));
            
//            problem with discounts I think?
//             
//            foreach ($this->cart->getProducts() as $product) {
//                $manufacturer = $this->model_catalog_manufacturer->getManufacturer($product['carrier']);
//
//                array_push($line_items, array(
//                    "amount" => $this->currency->format($product["total"], $currency, false, false) * 100,
//                    "currency" => "usd",
//                    "name" => htmlspecialchars_decode($product['name']) . " unlock",
//                    "quantity" => $product["quantity"],
//                    "description" => "Carrier: " . htmlspecialchars_decode($manufacturer["name"]) . ", IMEI: " . $product["imei"]
//                ));
//            }

            $json['error']['step'] = 2;

            $response = \Stripe\Checkout\Session::create(array(
                "customer_email" => $metadata["order_email"],
                "client_reference_id" => $metadata["order_id"],
                "payment_method_types" => array("card"),
                "cancel_url" => $this->url->link("main/checkout", "", "SSL"),
                "success_url" => $this->url->link('main/checkout/success&st=Completed', '', 'SSL'),
                "line_items" => $line_items,
                "payment_intent_data" => array(
                    "receipt_email" => $metadata["order_email"],
                    "metadata" => $metadata
                )
            ));
            $json['id'] = $response->id;

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
                    ->setError("Stripe", "General error caught - " . $e->getMessage())
                    ->setMetadata($data)
            )->notify();
        }

        header('Content-Type: application/json');
        echo json_encode($json);
        die();
    }

    public function charge() {
        // create charge

        $start_time = microtime(true);

        $this->load->model("fraud/fraud");

        if(isset($this->request->get['v']) && $this->request->get['v'] == "n") {
            $api_key = $this->config->get('stripe_new_secret_key');
        } else {
            $api_key = $this->config->get('stripe_production_secret_key');
        }

        \Stripe\Stripe::setApiKey($api_key);
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
            $data['payment_method'] = 'stripe';
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

            $name = (isset($this->request->post["card"]["name"]) ? $this->request->post["card"]["name"]: strtolower($this->session->data['order_info']['email']));
            if($name != "") {
                if(strpos($name, "@") !== false) {
                    $names_parts = explode("@", $name);
                    $name = $names_parts[0];
                }

                $names_parts = explode(" ", $name);
                $first_name = array_shift($names_parts);

                if(count($names_parts) > 0) {
                    $last_name = implode(" ", $names_parts);
                } else {
                    $last_name = "";
                }

                $this->model_checkout_order->updateOrderFirstLastNames($order_id,
                    $first_name,
                    $last_name
                );
            }

            try {
                $source = $this->request->post['id'];
                $currency = $this->session->data['currency'];
                $total_data = array();
                $total = $this->cart->getSubTotal();
                $taxes = $this->cart->getTaxes();

                $this->load->model('total/coupon');
                $this->model_total_coupon->getTotal($total_data, $total, $taxes);

                $amount = $this->currency->format($total, $currency, false, false) * 100;
                $metadata = array(
                    "order_id" => $order_id,
                    "order_email" => strtolower($this->session->data['order_info']['email'])
                );

                $this->load->model('catalog/manufacturer');
                $products_parts = array();

                foreach ($this->cart->getProducts() as $product) {

                    $manufacturer = $this->model_catalog_manufacturer->getManufacturer($product['carrier']);
                    $products_parts[] = htmlspecialchars_decode($product['name']) . ' (carrier: ' . htmlspecialchars_decode($manufacturer['name']) . ')';
                }

                $description = "Unlock " . implode(", ", $products_parts) .  ' - ' .$data['email'];

                $json['error']['step'] = 2;

                $response = \Stripe\Charge::create(array(
                    "amount" => $amount,
                    "currency" => $currency,
                    "source" => $source,
                    "description" => $description,
                    "metadata" => $metadata,
                    "receipt_email" => $metadata["order_email"]
                ), array(
                    "idempotency_key" => $this->ga->uuid . "-" . $order_id
                ));

                $fingerprint = $response->source->fingerprint;
                $last_four = $response->source->last4;

                $this->model_fraud_fraud->addCardToOrder($order_id, $fingerprint, $last_four, $this->request->post["card"]);

                $json['result'] = ($response->status == "succeeded" ? true : false) && ($response->paid);

                if($json['result']) {
                    // successful transaction

                    $this->model_checkout_order->setPaymentProviderTransactionID($order_id, $response->id);

                    if($ref_email !== false) {
                        $this->model_referral_referral->markOrderPaid($order_id);
                    }

                    $delayed = false;
                    $products = $this->model_checkout_order->getOrderProducts($order_id);
                    try {
                        $this->load->model('catalog/product');

                        foreach($products as $product) {
                            $delayed = $delayed || $this->model_catalog_product->isDelayed(
                                    $product['category_id'], $product['product_id'], $product['carrier_id']);
                        }

                        if($delayed) {
                            $this->model_checkout_order->sendMail(
                                $this->config->get("config_dev_email"),
                                "[ON HOLD][Stripe] New order",
                                sprintf("Order put on hold: %s", $order_id)
                            );
                        }
                    } catch (Exception $e) {
                        $this->model_checkout_order->sendMail(
                            $this->config->get("config_dev_email"),
                            "[ON HOLD][Stripe] Exception",
                            $e->getMessage()
                        );
                    } // delayed order handling section end

                    $order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$this->session->data['unpaid_order_id'] . "'");
                    $order_info = $this->model_checkout_order->getOrder($order_id);

                    foreach ($order_total_query->rows as $order_total) {
                        $this->load->model('total/' . $order_total['code']);

                        if (method_exists($this->{'model_total_' . $order_total['code']}, 'confirm')) {
                            $this->{'model_total_' . $order_total['code']}->confirm($order_info, $order_total);
                        }
                    }

                    // second check - with TXN ID
                    $order_status_override =  $this->model_fraud_fraud->handleOrderCheck($order_id, true);

                    if(!$order_status_override) {
                        $this->model_checkout_order->update($order_id, !$delayed ? "2" : "19"); // Pending | On Hold
                    } else {
                        $this->model_checkout_order->update($order_id, $order_status_override);
                    }

                    if(isset($order_info['email']) && filter_var($order_info['email'], FILTER_VALIDATE_EMAIL)) {
                        $this->model_checkout_order->customeremail($order_id, 2, $order_info['email'], $order_info['language_id']);
                    }

                    $ga_log = new Log("ga");
                    try {
                        $this->ga->sendTransactionToGA($order_id, "purchase");
                    } catch (Exception $e) {
                        $ga_log->write($e->getMessage());
                    }

                    $this->log->write("Successful Stripe transaction: " . $amount . " is the amount, order ID: " . $order_id);

                    $stop_time = microtime(true);
                    $this->cache->set(sprintf("stripe:elapsed:%s", $order_id), $stop_time - $start_time);

                } else {
                    // failed transaction
                    $json['result'] = false;
                    $json['error']['message'] = $response->failure_message;

                    $transaction_log_text = $response->failure_code . " : " . $response->failure_message;
                    $this->model_checkout_order->update($order_id, "10", $transaction_log_text);
                    $this->log->write("Failed Stripe transaction, reason: " . $transaction_log_text . ", id: " . $response->id);

                    $this->model_fraud_fraud->addFailedFromOrder($order_id, $this->request->post["card"]);
                }

            } catch(\Stripe\Error\Card $e) {
                $json['result'] = false;
                $json['error']['message'] = $e->getMessage();

                $transaction_log_text = $e->getMessage();
                $this->model_checkout_order->update($order_id, "10", $transaction_log_text);
                $this->model_fraud_fraud->addFailedFromOrder($order_id, $this->request->post["card"]);

                $this->log->write("Failed Stripe transaction, reason: " . $transaction_log_text);

                $this->notifier->add(
                    (new Notification())
                        ->setError("StripeCard", "\\Stripe\\Error\\Card caught")
                        ->setMetadata($data)
                )->notify();
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
                    ->setError("Stripe", "General error caught - " . $e->getMessage())
                    ->setMetadata($data)
            )->notify();
        }

        header('Content-Type: application/json');
        echo json_encode($json);
        die();
    }
    
    public function callback_v2() {
        // $endpoint_secret = whsec_aCZXXA49XumLlPR9Tu43gmNPqNpSRVoj

        $stripe_log = new Log("stripe_callback_v2.txt");
        
        $api_key = $this->config->get('stripe_production_secret_key');
        \Stripe\Stripe::setApiKey($api_key);
        $endpoint_secret = $this->config->get('stripe_callback_secret');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            $this->notifier->add(
                (new Notification())
                    ->setError("StripeV2", "UnexpectedValueException")
                    ->setException($e)
            )->notify();
            
            http_response_code(400);
            exit();
        } catch(\Stripe\Error\SignatureVerification $e) {
            // Invalid signature
            $this->notifier->add(
                (new Notification())
                    ->setError("StripeV2", "SignatureVerification")
                    ->setException($e)
            )->notify();
            
            http_response_code(400);
            exit();
        }
        
        $stripe_log->write(json_encode($event->jsonSerialize()));

        $this->load->model('fraud/fraud');
        $this->load->model('checkout/order');
        $this->load->model('fraud/graph');
        $this->load->model('referral/referral');

        $return_success = true;
        
        switch ($event->type) {
            case "checkout.session.completed": {
                
                $intent = \Stripe\PaymentIntent::retrieve($event->data->object->payment_intent);
                
                $order_id = $intent->metadata->order_id;
                $order = $this->model_checkout_order->getOrder($order_id);
                
                // todo: is it possible it will have more charges?
                $charge = $intent->charges->data[0];
                

                // set name
                $name = $charge->billing_details->name;
                if($name != "") {
                    if(strpos($name, "@") !== false) {
                        $names_parts = explode("@", $name);
                        $name = $names_parts[0];
                    }

                    $names_parts = explode(" ", $name);
                    $first_name = array_shift($names_parts);

                    if(count($names_parts) > 0) {
                        $last_name = implode(" ", $names_parts);
                    } else {
                        $last_name = "";
                    }

                    $this->model_checkout_order->updateOrderFirstLastNames($order_id,
                        $first_name,
                        $last_name
                    );
                }
                
                // add card to order
                if($charge->payment_method_details->type === "card") {
                    $fingerprint = $charge->payment_method_details->card->fingerprint;
                    $last_four = $charge->payment_method_details->card->last4;
                    $this->model_fraud_fraud->addCardToOrder($order_id, $fingerprint, $last_four, $charge->payment_method_details->card->jsonSerialize());
                } else {
                    $this->notifier->add(
                        (new Notification())
                            ->setError("StripeV2", "Non-card source")
                            ->setMetadata($intent->jsonSerialize())
                    )->notify();
                }
                
                // set charge ID
                $this->model_checkout_order->setPaymentProviderTransactionID($order_id, $charge->id);

                $delayed = false;
                $products = $this->model_checkout_order->getOrderProducts($order_id);
                try {
                    $this->load->model('catalog/product');

                    foreach($products as $product) {
                        $delayed = $delayed || $this->model_catalog_product->isDelayed(
                                $product['category_id'], $product['product_id'], $product['carrier_id']);
                    }

                    if($delayed) {
                        $this->model_checkout_order->sendMail(
                            $this->config->get("config_dev_email"),
                            "[ON HOLD][Stripe] New order",
                            sprintf("Order put on hold: %s", $order_id)
                        );
                    }
                } catch (Exception $e) {
                    $this->model_checkout_order->sendMail(
                        $this->config->get("config_dev_email"),
                        "[ON HOLD][Stripe] Exception",
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

                $this->model_checkout_order->update($order_id, "20"); // Pending | On Hold

                $stripe_log->write("Successful Stripe transaction: " . $charge->amount . " is the amount, order ID: " . $order_id);
                
                break;
            }

            case "charge.succeeded": {
                $order_id = $event->data->object->metadata->order_id;
                // set charge ID
                $this->model_checkout_order->setPaymentProviderTransactionID($order_id, $event->data->object->id);

                if($order_id) {
                    $order = $this->model_checkout_order->getOrder($order_id);
                    $products = $this->model_checkout_order->getOrderProducts($order_id);

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
                        sprintf("Stripe payment received -  order ID %s", $order_id),
                        $content
                    );
                    $this->model_fraud_graph->addOrder($order_id);

                    if($duplicate) {
                        $this->model_checkout_order->sendMail(array(
                            "pawel@devsti.me", "support@unlockpandasupport.zendesk.com", "emilio@unlockriver.com"
                        ), "Duplicate IMEI order detected", sprintf("Please check %s.", $order_id));
                    }
                } else {
                    $return_success = false;
                }
                break;
            }
            case "charge.dispute.created": {
                $txn_id = $event->data->object->charge;
                $order_id = $this->model_checkout_order->getByTXNID($txn_id);
                if($order_id) {
                    $send_notification = true;
                    $order = $this->model_checkout_order->getOrder($order_id);

                    $this->model_checkout_order->update($order_id, "13");
                    $this->model_fraud_fraud->addFromOrder($order_id);
                    $this->model_checkout_order->sendMail($this->config->get("config_dev_email"),
                        sprintf("Stripe dispute created - order %s", $order_id),
                        sprintf("Client has created dispute for order %s. Charge ID: %s.", $order_id, $txn_id)
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
                            sprintf("Stripe order chargeback created - order %s", $order_id),
                            sprintf("Client has created dispute for order %s", $order_id)
                        );
                    }
                    $this->model_fraud_graph->addOrder($order_id);

                } else {
                    $return_success = false;
                }
                break;
            }
            case "charge.refunded": {
                $txn_id = $event->data->object->id;
                $order_id = $this->model_checkout_order->getByTXNID($txn_id);
                if($order_id) {
                    $this->model_checkout_order->update($order_id, "11");
//                    $this->model_checkout_order->sendMail("shamdog@gmail.com",
//                        "Stripe payment refunded " . $order_id,
//                        "Refund has been created for order " . $order_id . ". Charge ID: " . $txn_id . "."
//                    );
                    $this->model_referral_referral->markOrderRefunded($order_id);
                    $this->ga->sendTransactionToGA($order_id, "refund");
                    $this->model_fraud_graph->addOrder($order_id);
                }
                break;
            }
        }
        if ($return_success) {
            http_response_code(200); // PHP 5.4 or greater
        } else {
            http_response_code(404);
        }
    }

    public function callback() {
        $stripe_log = new Log("stripe_callback.txt");

        $body = file_get_contents('php://input');
        $event_json = json_decode($body);

        $stripe_log->write($body);

        $this->load->model('checkout/order');
        $this->load->model('fraud/fraud');
        $this->load->model('fraud/graph');
        $this->load->model('referral/referral');

        switch($event_json->type) {
            case "charge.succeeded": {
                $txn_id = $event_json->data->object->id;
                $order_id = $this->model_checkout_order->getByTXNID($txn_id);
                if($order_id) {
                    $order = $this->model_checkout_order->getOrder($order_id);
                    $products = $this->model_checkout_order->getOrderProducts($order_id);

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
                        sprintf("Stripe payment received -  order ID %s", $order_id),
                        $content
                    );
                    $this->model_fraud_graph->addOrder($order_id);

                    if($duplicate) {
                        $this->model_checkout_order->sendMail(array(
                            "pawel@devsti.me", "support@unlockpandasupport.zendesk.com", "emilio@unlockriver.com"
                        ), "Duplicate IMEI order detected", sprintf("Please check %s.", $order_id));
                    }
                }
                break;
            }
            case "charge.dispute.created": {
                $txn_id = $event_json->data->object->charge;
                $order_id = $this->model_checkout_order->getByTXNID($txn_id);
                if($order_id) {
                    $send_notification = false;
                    $order = $this->model_checkout_order->getOrder($order_id);
                    if($order["order_status_id"] == "1") { // processing
                        $send_notification = true;
                    }

                    $this->model_checkout_order->update($order_id, "13");
                    $this->model_fraud_fraud->addFromOrder($order_id);
                    $this->model_checkout_order->sendMail($this->config->get("config_dev_email"),
                        sprintf("Stripe dispute created - order %s", $order_id),
                        sprintf("Client has created dispute for order %s. Charge ID: %s.", $order_id, $txn_id)
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
                            sprintf("Stripe order chargeback created - order %s", $order_id),
                            sprintf("Client has created dispute for order %s", $order_id)
                        );
                    }
                    $this->model_fraud_graph->addOrder($order_id);

                }
                break;
            }
            case "charge.refunded": {
                $txn_id = $event_json->data->object->id;
                $order_id = $this->model_checkout_order->getByTXNID($txn_id);
                if($order_id) {
                    $this->model_checkout_order->update($order_id, "11");
//                    $this->model_checkout_order->sendMail("shamdog@gmail.com",
//                        "Stripe payment refunded " . $order_id,
//                        "Refund has been created for order " . $order_id . ". Charge ID: " . $txn_id . "."
//                    );
                    $this->model_referral_referral->markOrderRefunded($order_id);
                    $this->ga->sendTransactionToGA($order_id, "refund");
                    $this->model_fraud_graph->addOrder($order_id);
                }
                break;
            }
        }
    }
}