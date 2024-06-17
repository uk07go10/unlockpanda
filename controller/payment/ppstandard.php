<?php

/**
 * Class ControllerPaymentPPStandard
 * @property ModelCheckoutOrder $model_checkout_order
 * @property ModelReferralReferral $model_referral_referral
 * @property ModelFraudFraud $model_fraud_fraud
 * @property Currency $currency
 */
class ControllerPaymentPPStandard extends Controller {
    public function index() {
//                if(!$this->customer->isLogged()){
//                    $this->redirect($this->url->link('account/login', '', 'SSL'));
//                }
        $this->language->load('payment/pp_standard');

        $this->data['text_testmode'] = $this->language->get('text_testmode');

        $this->data['button_confirm'] = $this->language->get('button_confirm');

        $this->data['testmode'] = $this->config->get('pp_standard_test');

        if (!$this->config->get('pp_standard_test')) {
            $this->data['action'] = 'https://www.paypal.com/cgi-bin/webscr';
        } else {
            $this->data['action'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        }

        $currency = 'USD';

        $this->data['business'] = $this->config->get('pp_standard_email');
        $this->data['item_name'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

        if(isset($this->session->data['email'])){
            $this->data['email'] = $this->session->data['email'];
        } else {
            $this->data['email'] = $this->customer->getEmail();
        }
        //$this->log->write('PP_EMAIL: ' . $this->data['email']);
        $this->data['products'] = array();

        foreach ($this->cart->getProducts() as $product) {
            $option_data = array();

//				$this->debug($product);
            $this->load->model('catalog/manufacturer');
            $manufacturer = $this->model_catalog_manufacturer->getManufacturer($product['carrier']);
            if(!isset($product["name"])) {
                $this->log->write("Product name missing: ID " . (isset($product["product_id"]) ? $product["product_id"] : "unknown"));
            }
            $this->data['products'][] = array(
                'product_id'  => $product['product_id'],
                'name'        => $product['name'] . ' - ' . $this->data['email'],
                'carrier'     => $manufacturer['name'],
                'carrier_id'  => $product['carrier'],
                'imei'        => $product['imei'],
                'price'       => $this->currency->format($product['price'], $currency, false, false),
                'total'       => $this->currency->format($product['price'], $currency, false, false),
                'quantity'    => $product['quantity'],
            );
        }
        //Calculate Couoon Discount
        $total_data = array();
        $total = 0;
        $taxes = $this->cart->getTaxes();
        $discount = 0;

        $this->data['discount_amount_cart'] = 0;
        $this->load->model('total/coupon');
        $this->model_total_coupon->getTotal($total_data, $total, $taxes);
        foreach ($total_data as $dsctdata) {
            $discount = $dsctdata['value'];
        }
        //End

        if ($discount <> 0){
            $this->data['discount_amount_cart'] -= $this->currency->format($total, $currency, false, false);
        }

        $total = $this->currency->format( $this->cart->getSubTotal(), $currency, false, false);
        $this->data['currency_code'] = $currency;


        $this->data['first_name'] = html_entity_decode($this->customer->getFirstName(), ENT_QUOTES, 'UTF-8');
        $this->data['last_name'] = html_entity_decode($this->customer->getLastName(), ENT_QUOTES, 'UTF-8');
//			$this->data['address1'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
//			$this->data['address2'] = html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8');
//			$this->data['city'] = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
//			$this->data['zip'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
        $this->data['country'] = 'US';
//			$this->data['invoice'] = $this->session->data['order_id'] . ' - ' . html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
        $this->data['lc'] = $this->session->data['language'];
        $this->data['return'] = $this->url->link('checkout/success', '', 'SSL');
        $this->data['notify_url'] = $this->url->link('payment/ppstandard/callback', '', 'SSL');
        $this->data['cancel_return'] = $this->url->link('checkout/cart', '', 'SSL');

//                        set session for order_creation after completing paypal payment
        $this->session->data['order_info'] = array(
            'products'       => $this->data['products'],
            'currency_code'  => $this->data['currency_code'],
            'customer_id'    => $this->customer->getId(),
            'email'          => $this->data['email'],
            'firstname'      => $this->data['first_name'],
            'lastname'       => $this->data['last_name'],
            'payment_method' => 'pp_standard',
            'country'        => $this->data['country'],
            'total'          => $total,
        );

        if (!$this->config->get('pp_standard_transaction')) {
            $this->data['paymentaction'] = 'authorization';
        } else {
            $this->data['paymentaction'] = 'sale';
        }

        $this->load->library('encryption');

        $encryption = new Encryption($this->config->get('config_encryption'));
        // $this->data['custom'] = $encryption->encrypt($this->session->data['order_id']);

        //Abandoned Order
        $cookie_name = "abo";
        if(!isset($_COOKIE[$cookie_name])) {
            $adata = array();
            $adata['a_store_name'] = $this->config->get('config_name');
            $adata['a_store_url'] = $this->config->get('config_url');
            $adata['a_email'] = $this->data['email'];
            $adata['a_firstname'] = '';
            $adata['a_lastname'] = '';
            $adata['a_total'] = $this->cart->getSubTotal();
            $adata['a_language_id'] = $this->config->get('config_language_id');
            $adata['a_products'] = $this->data['products'];
            $this->load->model('checkout/order');
            $a_order_id = $this->model_checkout_order->createabandonedorder($adata);
            setcookie('abo', '1');
            $this->session->data['a_order_id'] =  $a_order_id;
            //print_r($adata);
        }
        //echo $this->session->data['a_order_id'];
        //End


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/pp_standard.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/pp_standard.tpl';
        } else {
            $this->template = 'default/template/payment/pp_standard.tpl';
        }

        $this->render();
    }

    public function createunpaid() {
        // create an unpaid order

        $this->load->model("fraud/fraud");

        $json = array();

        try {
            if(!isset($this->session->data['order_info'])) {
                throw new Exception("No order info!");
            }

            $data['customer_id'] = $this->session->data['order_info']['customer_id'];
            $data['firstname'] = $this->session->data['order_info']['firstname'];
            $data['lastname'] = $this->session->data['order_info']['lastname'];
            $data['email'] = strtolower($this->session->data['order_info']['email']);
            $data['telephone'] = '';
            $data['payment_method'] = $this->session->data['order_info']['payment_method'];
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

//            $fraud_check = $this->model_fraud_fraud->classifyOrder($order_id);
//            $related_orders_enabled = $this->config->get("config_enable_fraud_module_client");
//            if($related_orders_enabled == '1') {
//                $reason = $fraud_check->getReason();
//            } else {
//                $reason = "Fraud module disabled for client";
//            }
//
//            if($fraud_check->decision === ModelFraudFraud::$CHECK_OUTCOME_PASS) {
//                $this->model_checkout_order->update($order_id, "18", $reason);
//
//            } else if($fraud_check->decision === ModelFraudFraud::$CHECK_OUTCOME_HOLD) {
//                // pending approval
//                $this->model_checkout_order->update($order_id, "22", $reason);
//                $this->notifier->add(
//                    (new Notification())
//                        ->setError("CheckOutcomeHold", $reason)
//                )->notify();
//
//            } else if($fraud_check->decision === ModelFraudFraud::$CHECK_OUTCOME_REJECT) {
//                $this->model_fraud_fraud->addIdentityFromOrder($order_id);
//                $this->model_fraud_fraud->addIMEIFromOrder($order_id);
//                $this->model_checkout_order->update($order_id, "10", $reason);
//
//                $this->notifier->add(
//                    (new Notification())
//                        ->setError("CheckOutcomeReject", $reason)
//                )->notify();
//
//                throw new Exception("An exception occured. Please try again");
//            }

            $this->session->data["unpaid_order_id"] = $order_id;
            $json['id'] = $encryption->encrypt($order_id);
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

        echo json_encode($json);
        die();
    }

    public function callback() {

        $this->load->library('encryption');
        $this->load->model("referral/referral");

        $encryption = new Encryption($this->config->get('config_encryption'));

        $raw = file_get_contents("php://input");
        $rawArray = explode("&", $raw);

        $out = array();
        foreach($rawArray as $value) {
            $split = explode("=", $value);
            if(count($split) == 2) {
                $out[$split[0]] = urldecode($split[1]);
            }
        }

        $this->request->post = $out;

        if (isset($this->request->post['custom'])) {
            $order_id = $encryption->decrypt($this->request->post['custom']);
            $this->log->write("PP_STANDARD :: Request is coming through, order id: " . $order_id);
        } else {
            $order_id = 0;
            $this->log->write("PP_STANDARD :: Request is without an id! Data:");
            $this->log->write(json_encode($this->request->post));
        }

        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($order_id);

        if ($order_info) {

            $request = 'cmd=_notify-validate';

            $txn_id = "";
            if(isset($this->request->post['txn_id'])) {
                $txn_id = $this->request->post['txn_id'];
            }
            foreach ($this->request->post as $key => $value) {
                $request .= '&' . $key . '=' . urlencode($value);
            }

            $this->log->write("PP_STANDARD :: Request params: " . $request);

            if (!$this->config->get('pp_standard_test')) {
                $curl = curl_init('https://www.paypal.com/cgi-bin/webscr');
            } else {
                $curl = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
            }

            $data_retrieved = false;
            $data_retrieved_tries_left = 5;
            $curl_errno = 0;
            $curl_errmsg = "";

            while(!$data_retrieved && --$data_retrieved_tries_left >= 0) {
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_TIMEOUT, 10);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

                $response = curl_exec($curl);
                $curl_errno = curl_errno($curl);
                $curl_errmsg = curl_error($curl);
                curl_close($curl);

                if($curl_errno == CURLE_OK) {
                    $data_retrieved = true;
                }
            }

            if (!$data_retrieved) {
                $this->log->write('PP_STANDARD :: CURL failed ' . curl_error($curl) . '(' . curl_errno($curl) . ')');

                $subject = "Automatic order verification at PayPal failed";
                $text = sprintf("Order ID: %s, status ID: %s reason: %s. Please mark the transaction by hand if paid.", $order_id, $curl_errno, $curl_errmsg);
                $this->model_checkout_order->sendMail($this->config->get("config_billing_email"), $subject, $text);
                $this->model_checkout_order->sendMail($this->config->get("config_dev_email"), $subject, $text);
                return;
            }

            if ($this->config->get('pp_standard_debug')) {
                $order_log = new Log("paypal.txt");
                $order_log->write('PP_STANDARD :: IPN REQUEST: ' . $request);
                $order_log->write('PP_STANDARD :: IPN RESPONSE: ' . $response);
            }

            $ga_log = new Log("ga");

            if ((strcmp($response, 'VERIFIED') == 0 || strcmp($response, 'UNVERIFIED') == 0) && isset($this->request->post['payment_status'])) {
                $order_status_id = $this->config->get('config_order_status_id');
                $message = "";

                if(isset($this->request->post['txn_type']) && $this->request->post['txn_type'] == "new_case") {
                    // dispute or complaint is handled here
                    $handled = array("dispute", "complaint", "chargeback");
                    if(in_array($this->request->post['case_type'], $handled)) {
                        $order_status_id = "13"; // chargeback
                        $message = (isset($this->request->post['buyer_additional_information']) ? urldecode($this->request->post['buyer_additional_information']) : "Code: " . $this->request->post['reason_code']);
                        //@todo: send mail to admin
                    }

                    try {
                        $this->model_checkout_order->sendMail("shamdog@gmail.com", "New chargeback", "Check " . $order_id . "!");
                    } catch (Exception $e) {
                        $this->log->write("Failed to send notification mail to shamdog@gmail.com");
                    }
                }

                switch($this->request->post['payment_status']) {
                    // normal flow
                    case 'Completed':
                        if($this->request->post['txn_type'] == 'adjustment') {
                            $order_status_id = "13";
                            $message = "Adjustment received from PayPal.";
                            $this->model_checkout_order->sendMail(
                                $this->config->get("config_dev_email"),
                                "New PP Adjustment",
                                sprintf("Order ID: %s", $order_id)
                            );
                            break;
                        }
                        //@todo: test for price
                        if ($this->request->post['mc_gross'] === $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false) || true) {
                            $order_status_id = $this->config->get('pp_standard_completed_status_id');

                            try {
                                $this->load->model('catalog/product');
                                $delayed = false;
                                $products = $this->model_checkout_order->getOrderProducts($order_id);

                                foreach($products as $product) {
                                    $delayed = $delayed || $this->model_catalog_product->isDelayed(
                                            $product['category_id'], $product['product_id'], $product['carrier_id']);
                                }

                                if($delayed) {
                                    $order_status_id = 19; // On Hold
                                    $this->model_checkout_order->sendMail(
                                        $this->config->get("config_dev_email"),
                                        "[ON HOLD][PP] New order",
                                        sprintf("Order put on hold: %s", $order_id)
                                    );
                                }
                            } catch (Exception $e) {
                                $this->model_checkout_order->sendMail(
                                    $this->config->get("config_dev_email"),
                                    "[ON HOLD][PP] Exception",
                                    $e->getMessage()
                                );
                            }


                            if($order_info["order_status_id"] == 17) { // pending eCheck
                                try {
                                    $message = $this->model_checkout_order->eCheckCompletedEmail($order_id);
                                } catch (Exception $e) {
                                    $this->model_checkout_order->sendMail($this->config->get("config_dev_email"),
                                        "Error - echeck completed",
                                        $e->getMessage());
                                }
                            }

                            try {
                                if (in_array($order_info['order_status_id'], array("2", "5"))) {
                                    // if order is either processing or complete
                                    $this->log->write("Transaction already paid! Notifying admin..");

                                    $this->model_checkout_order->sendMail($this->config->get("config_billing_email"),
                                        sprintf("Duplicate payment detected - order %s", $order_info['order_id']),
                                        sprintf("Duplicate payment was registered for order  - previous PayPal TXN ID: %s, "
                                            . "new TXN ID - %s. The new transaction was not saved.", $order_info['txn_id'], $txn_id));
                                    return;
                                }
                            } catch (Exception $e) {
                                $this->model_checkout_order->sendMail("shamdog@gmail.com", "Error!", "Error!");
                            }

                            if($order_info["order_status_id"] == "22") {
                                // it was marked as "on hold" by the fraud module

                                $order_status_id = "20";

                                $this->notifier->add(
                                    (new Notification())
                                        ->setError("OrderHold", sprintf("Order paid: %s", $order_id))
                                )->notify();

                                $this->model_checkout_order->sendMail(
                                    array(
                                        $this->config->get("config_dev_email"),
                                        $this->config->get("config_billing_email"),
                                    ),
                                    sprintf("Suspicious order paid: %s", $order_id),
                                    sprintf("Order with the following id: %s was paid. "
                                        . "Please review the reason by checking the order status page.", $order_id)
                                );
                            }

                            $this->model_checkout_order->setPaymentProviderTransactionID($order_id, $txn_id);

                            $this->model_checkout_order->updateOrderFirstLastNames($order_id,
                                $this->request->post['first_name'],
                                $this->request->post['last_name']
                            );

                            $this->model_checkout_order->updateOrderSecondEmail($order_id, urldecode($this->request->post['payer_email']));

                            if(isset($order_info['email']) && filter_var($order_info['email'], FILTER_VALIDATE_EMAIL)) {
                                try {
                                    $this->model_checkout_order->customeremail($order_id, 2, $order_info['email'], $order_info['language_id']);
                                } catch (Exception  $e) {
                                    $this->model_checkout_order->sendMail($this->config->get("config_dev_email"),
                                        "Paypal payment failed",
                                        $e->getMessage()
                                    );
                                }
                            }

                            try {
                                $this->ga->sendTransactionToGA($order_id, "purchase");
                            } catch (Exception $e) {
                                $ga_log->write($e->getMessage());
                            }


                            $this->model_referral_referral->markOrderPaid($order_id);


                            // check if duplicate imei
                            $products = $this->model_checkout_order->getOrderProducts($order_id);
                            $imeis = array();
                            $duplicate = false;

                            foreach($products as $product) {
                                if(in_array($product['imei'], $imeis)) {
                                    $duplicate = true;
                                }
                                array_push($imeis, $product['imei']);
                            }

                            if($duplicate) {
                                $this->model_checkout_order->sendMail(array(
                                    "pawel@devsti.me", "support@unlockpandasupport.zendesk.com", "emilio@unlockriver.com"
                                ), "Duplicate IMEI order detected", sprintf("Please check %s.", $order_id));
                            }

                        } else {
                            // the amount that was sent is incorrect

                            $order_status_id = 16; // Voided

                            $this->model_checkout_order->sendMail(
                                array(
                                    $this->config->get("config_dev_email"),
                                    $this->config->get("config_billing_email"),
                                    "carimany@unlockriver.com"
                                ),
                                sprintf("Fraud transaction, ID: %s - incorrect value", $order_id),
                                sprintf("Order ID: %s", $order_id)
                            );
                        }
                        break;
                    case 'Denied':
                        $order_status_id = $this->config->get('pp_standard_denied_status_id');
                        if($order_info["order_status_id"] == 17) { // pending eCheck
                            try {
                                $message = $this->model_checkout_order->eCheckFailedEmail($order_id);
                            } catch (Exception $e) {
                                $this->model_checkout_order->sendMail($this->config->get("config_dev_email"),
                                    "Error - echeck failed",
                                    $e->getMessage());
                            }
                        }
                        break;
                    case 'Expired':
                        $order_status_id = $this->config->get('pp_standard_expired_status_id');
                        break;
                    case 'Failed':
                        $order_status_id = $this->config->get('pp_standard_failed_status_id');
                        if($order_info["order_status_id"] == 17) { // pending eCheck
                            try {
                                $message = $this->model_checkout_order->eCheckFailedEmail($order_id);
                            } catch (Exception $e) {
                                $this->model_checkout_order->sendMail($this->config->get("config_dev_email"),
                                    "Error - echeck failed",
                                    $e->getMessage());
                            }
                        }
                        break;
                    case 'Pending':
                        if(isset($this->request->post['pending_reason']) && $this->request->post['pending_reason'] == "echeck") {
                            $order_status_id = 17; // Pending eCheck
                            try {
                                $message = $this->model_checkout_order->eCheckPendingEmail($order_id);
                            } catch (Exception $e) {
                                $this->model_checkout_order->sendMail($this->config->get("config_dev_email"),
                                    "Error - echeck pending",
                                    $e->getMessage());
                            }

                        } else {
                            $order_status_id = 18; // Pending Payment $this->config->get('pp_standard_pending_status_id');
                        }
                        break;
                    case 'Processed':
                        $order_status_id = $this->config->get('pp_standard_processed_status_id');
                        break;
                    case 'Refunded':
                        $refund_value = abs((float)$this->request->post['mc_gross']);
                        $order_value = (float)$this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);

                        if($order_info['order_status_id'] == "16") {
                            $order_status_id = "16";
                            $message = "Refunded payment, that was fraudulent.";
                            // voided - partial payment
                        } else if($refund_value < $order_value * 0.25) {
                            $order_status_id = $order_info["order_status_id"]; // last state it had
                            $message = "Partial refund - refunded " . $this->request->post['mc_gross'];
                        } else {
                            $order_status_id = $this->config->get('pp_standard_refunded_status_id');
                        }

                        $this->model_referral_referral->markOrderRefunded($order_id);

                        try {
                            $this->ga->sendTransactionToGA($order_id, "refund");
                        } catch (Exception $e) {
                            $ga_log->write($e->getMessage());
                        }

                        break;
                    case 'Canceled_Reversal':
                        // case won
                        $order_status_id = "13";
                        $message = "Case won!";

                        break;
                    case 'Reversed':
                        $order_status_id = "13"; // chargeback $this->config->get('pp_standard_reversed_status_id');
                        $this->model_referral_referral->markOrderRefunded($order_id);
                        break;
                    case 'Voided':
                        $order_status_id = $this->config->get('pp_standard_voided_status_id');
                        break;
                }

                if (!$order_info['order_status_id']) {
                    $this->model_checkout_order->confirm($order_id, $order_status_id);
                } else {
                    $this->model_checkout_order->update($order_id, $order_status_id, $message);
                }

            } else {
                $this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id'));
            }
        } else {
            try {

                $this->model_checkout_order->sendMail($this->config->get("config_dev_email"),
                    "Transaction without order ID",
                    sprintf("Content: %s", json_encode($out)));
            } catch (Exception $e) {
                $this->log->write("Cannot send mail to dev");
            }
        }
    }

    /**
     * Cleans the list of orders that are older than x
     * and moves it to abandoned orders list
     */
    public function cleanup() {
        $result = true;
        $number = 0;

        $limit = isset($this->request->get["limit"]) && (int) $this->request->get["limit"] > 0 ? (int)$this->request->get["limit"] : 300;

        $this->load->model('checkout/order');

        $cleanupLog = new Log("cleanup.log");
        $cleanupLog->write("Starting cleaning procedure..");

        if((bool)$this->config->get("config_enable_cleanup")) {
            $cleanupLog->write("Cleaning enabled, proceeding..");
            $cleanupAfter = (int)$this->config->get("config_cleanup_after");
            if($cleanupAfter > 0) {

                $orders = $this->model_checkout_order->getUnpaidOrdersOlderThan($cleanupAfter, $limit);
                foreach($orders as $order) {
                    $this->model_checkout_order->moveToAbandoned($order);
                    $number++;
                }

            } else {
                $cleanupLog->write("Invalid cleanup after setting: " . $cleanupAfter . ", quitting..");
                $result = false;
            }

        } else {
            $cleanupLog->write("Cleanup disabled, quitting..");
            $result = null;
        }

        echo json_encode(array(
            "result" => $result,
            "number" => $number
        ));

    }

    public function test() {
        $this->load->model('checkout/order');

        //$this->ga->sendTransactionToGA("117577");
    }
}
?>