<?php

/*
 * Copyright (c) 2012 Web Project Solutions LLC (info@webprojectsol.com)
 *
 * SOFTWARE LICENSE AGREEMENT
 *
 * This Software is not free.
 *
 * Developer hereby grants to Licensee a perpetual, non-exclusive,
 * limited license to use the Software as set forth in this Agreement.
 *
 * Licensee shall not modify, copy, duplicate, reproduce, license or sublicense the Software,
 * or transfer or convey the Software or any right in the Software to anyone else
 * without the prior written consent of Developer; provided that Licensee may
 * make one copy of the Software for backup or archival purposes.
 *
 *
 *  @author Antonello Venturino <info@webprojectsol.com>
 *  @copyright  2012 Web Project Solutions LLC
 *  @license    http://www.webprojectsol.com/license.php
 *  @url  http://www.webprojectsol.com/en/modules-of-payment/paypal-express-checkout.html
 */

/**
 * Class ControllerPaymentPaypalExpress
 * @property Log $log
 * @property ModelCheckoutOrder $model_checkout_order
 * @property ModelCatalogManufacturer $model_catalog_manufacturer
 * @property ModelFraudFraud model_fraud_fraud
 */
class ControllerPaymentPaypalExpress extends Controller {

    private $repeatDoExpressCheckoutPayment = 0;
    private $repeatSetExpressCheckout = 0;
    private $_supportedCurrencyCodes = array('AUD', 'CAD', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'ILS', 'JPY', 'MXN', 'NOK', 'NZD', 'PLN', 'GBP', 'SGD', 'SEK', 'CHF', 'USD', 'TWD', 'THB');
    private $skip_confirm = false;

    public function __construct($registry) {
        parent::__construct($registry);
        set_error_handler(array($this, 'exceptionsErrorHandler'));
        register_shutdown_function(array($this, 'registerShutdownFunction'));

        $this->language->load('payment/paypal_express');

        $this->load->model("checkout/order");
        $this->load->model("referral/referral");
        $this->load->model("catalog/manufacturer");
    }

    protected function index() {
        if ($this->request->get['route'] == 'checkout/confirm' && !isset($this->session->data['pec']['login'])) {
            $this->data['PECheckout'] = 'true';
        }

        $this->language->load('payment/paypal_express');

        $this->data['text_wait'] = $this->language->get('text_wait');
        $this->data['text_payment_processing'] = $this->language->get('text_payment_processing');

        $this->data['button_confirm'] = $this->language->get('button_confirm');
        $this->data['environment'] = $this->config->get('paypal_express_test') ? 'sandbox' : 'production';
        $this->data['merchant_id'] = $this->config->get('paypal_express_test')
            ? $this->config->get('paypal_express_merchant_id') : $this->config->get('paypal_express_merchant_id_test');

        if (isset($this->session->data['pec']['skip_confirm'])) {
            $this->data['skip_confirm'] = $this->session->data['pec']['skip_confirm'];
        } else {
            $this->data['skip_confirm'] = false;
        }

        $this->data['actionSetExpressCheckout'] = $this->url->link('payment/paypal_express/SetExpressCheckout', '', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'SSL' : 'NONSSL'));
        $this->data['actionDoExpressCheckoutPayment'] = $this->url->link('payment/paypal_express/pay', '', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'SSL' : 'NONSSL'));

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/paypal_express.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/paypal_express.tpl';
        } else {
            $this->template = 'default/template/payment/paypal_express.tpl';
        }

        $this->render();
    }

    private function sendTransactionToGateway($url, $parameters) {
        $server = parse_url($url);
        $error = array(
            "occurred" => false,
            "message" => ""
        );

        if (!isset($server['port'])) {
            $server['port'] = ($server['scheme'] == 'https') ? 443 : 80;
        }

        if (!isset($server['path'])) {
            $server['path'] = '/';
        }

        if (function_exists('curl_init')) {
            $curl = curl_init($server['scheme'] . '://' . $server['host'] . $server['path'] . (isset($server['query']) ? '?' . $server['query'] : ''));
            curl_setopt($curl, CURLOPT_PORT, $server['port']);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
            curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);

            $result = curl_exec($curl);

            if (!isset($result) || !$result) {
                $message = 'PayPal Express Checkout Request failed: ' . curl_error($curl) . '(' . curl_errno($curl) . ')';
                $this->log->write($message);

                $error["occurred"] = true;
                $error["message"] = $message;

                curl_close($curl);
                return false;
            } else {
                curl_close($curl);
            }
        } else {
            $message = 'PayPal Express Checkout Request failed: Your server doesn\'t support the curl functions, it\'s need to work correctly';
            $this->log->write($message);
            $result = '';

            $error["occurred"] = true;
            $error["message"] = $message;
        }

        return $result;
    }

    public function cancel($no_redir=false) {

        $redirectToNew = false;
        if(isset($this->request->server["HTTP_REFERER"]) && strpos($this->request->server["HTTP_REFERER"], "main/checkout") !== false) {
            $redirectToNew = true;
        }

        $this->session->data['payment_cancelled'] = true;
        unset($this->session->data['pec']);
        if (isset($this->session->data['unpaid_order_id'])) {
            $this->load->model('checkout/order');
            $order_info = $this->model_checkout_order->getOrder($this->session->data['unpaid_order_id']);
            if (!$order_info['order_status_id']) {
                $this->model_checkout_order->confirm($this->session->data['unpaid_order_id'], $this->config->get('paypal_express_order_status_id_voided'), '', false);
            } else {
                $this->model_checkout_order->update($this->session->data['unpaid_order_id'], $this->config->get('paypal_express_order_status_id_voided'), '', false);
            }
        }

        if(!$no_redir) {
            if($redirectToNew || true) {
                $this->redirect($this->url->link('main/checkout', '', 'SSL'));
            } else {
                $this->redirect($this->url->link('checkout/cart', '', 'SSL'));
            }
        }
    }

    public function pay($directly = false, $totalSum = false, $noOrderDetail = false) {
        $json = array();
//        if (!$this->isEnabled()) {
//            $json['error'] = 'Disabled';
//            if ($directly) {
//                return $json;
//            }
//            $this->response->setOutput(json_encode($json));
//            return;
//        }

        try {
            if (!isset($this->session->data['pec']['login']) || !isset($this->session->data['pec']['token'])) {
                unset($this->session->data['pec']);
                $this->SetExpressCheckout();
                return;
            }

            if (!isset($this->session->data['pec']['payerid'])) {
                $this->finalize();
                return;
            }

            if (!isset($this->session->data['unpaid_order_id']) || ($this->cart->hasShipping() && !isset($this->session->data['shipping_method']))) {
                $json['success'] = $this->url->link('checkout/pec_checkout', '', 'SSL');
                if ($directly) {
                    return $json;
                }
                $this->response->setOutput(json_encode($json));
                return;
            }

            if (!$this->config->get('paypal_express_test')) {
                $curl = 'https://api-3t.paypal.com/nvp';
                $p_user = $this->config->get('paypal_express_username');
                $p_pwd = $this->config->get('paypal_express_password');
                $p_signature = $this->config->get('paypal_express_signature');
            } else {
                $curl = 'https://api-3t.sandbox.paypal.com/nvp';
                $p_user = $this->config->get('paypal_express_username_test');
                $p_pwd = $this->config->get('paypal_express_password_test');
                $p_signature = $this->config->get('paypal_express_signature_test');
            }

            $order_total = $this->getOrderTotals();
            $this->getSupportedCurrencyCode();

            if (!$this->config->get('paypal_express_method')) {
                $payment_type = 'Authorization';
            } else {
                $payment_type = 'Sale';
            }

            $request = 'USER=' . $p_user;
            $request .= '&PWD=' . $p_pwd;
            $request .= '&VERSION=85.0';
            $request .= '&SIGNATURE=' . $p_signature;
            $request .= '&METHOD=DoExpressCheckoutPayment';
            $request .= '&TOKEN=' . $this->session->data['pec']['token'];
            $request .= '&PAYMENTREQUEST_0_PAYMENTACTION=' . $payment_type;
            $request .= '&PAYERID=' . $this->session->data['pec']['payerid'];
            $request .= '&PAYMENTREQUEST_0_CURRENCYCODE=' . urlencode($this->session->data['pec']['currency']);
            $request .= '&PAYMENTREQUEST_0_NOTETEXT=' . urlencode($this->config->get('config_name') . ' Order ID: ' . $this->session->data['unpaid_order_id']);

            if (!$noOrderDetail) {
                if ($totalSum) {
                    $total = 0;
                }
                if ($this->config->get('paypal_express_senditem')) {
                    $n = 0;
                    foreach ($this->cart->getProducts() as $product) {
                        $options = '';
                        $optionsName = '';
                        if (isset($product['option']) && $product['option']) {
                            foreach ($product['option'] as $option) {
                                $options .= $option['name'] . ': ' . $option['option_value'] . ', ';
                            }
                            $optionsName = substr($options, 0, -2);
                        }

                        $carrier = $this->model_catalog_manufacturer->getManufacturer($product['carrier']);
                        $request .= '&L_PAYMENTREQUEST_0_NAME' . $n . '=' . urlencode(html_entity_decode($product['name']) . ' - ' . $this->session->data['email'] . ' (carrier: ' . htmlspecialchars_decode($carrier['name']) . ')');
                        // $request .= '&L_PAYMENTREQUEST_0_NUMBER' . $n . '=' . urlencode($product['model']);
                        $request .= '&L_PAYMENTREQUEST_0_NUMBER' . $n . '=' . urlencode($product['imei']);
                        if ($optionsName) {
                            $request .= '&L_PAYMENTREQUEST_0_DESC' . $n . '=' . urlencode($optionsName);
                        }
                        $request .= '&L_PAYMENTREQUEST_0_AMT' . $n . '=' . urlencode($this->PriceFormat($this->currency->format($product['price'], $this->session->data['pec']['currency'], false, false)));
                        $request .= '&L_PAYMENTREQUEST_0_QTY' . $n . '=' . urlencode($product['quantity']);
                        ++$n;
                        if ($totalSum) {
                            $total += $this->PriceFormat($this->currency->format($product['price'], $this->session->data['pec']['currency'], false, false));
                        }
                    }
                }

                $this->load->library('encryption');
                $encryption = new Encryption($this->config->get('config_encryption'));

                $request .= '&PAYMENTREQUEST_0_CUSTOM=' . urlencode($encryption->encrypt($this->session->data['unpaid_order_id']));
                $request .= '&PAYMENTREQUEST_0_ITEMAMT=' . urlencode($this->PriceFormat($this->currency->format($order_total['subtotal'], $this->session->data['pec']['currency'], false, false)));
                $request .= '&PAYMENTREQUEST_0_TAXAMT=' . urlencode($this->PriceFormat($this->currency->format($order_total['taxcost'], $this->session->data['pec']['currency'], false, false)));
                $request .= '&PAYMENTREQUEST_0_SHIPDISCAMT=' . urlencode($this->PriceFormat($this->currency->format($order_total['discount'], $this->session->data['pec']['currency'], false, false)));
                $request .= '&PAYMENTREQUEST_0_HANDLINGAMT=' . urlencode($this->PriceFormat($this->currency->format($order_total['handling'], $this->session->data['pec']['currency'], false, false)));
                $request .= '&PAYMENTREQUEST_0_SHIPPINGAMT=' . urlencode($this->PriceFormat($this->currency->format($order_total['shippingcost'], $this->session->data['pec']['currency'], false, false)));
                if ($totalSum) {
                    $total += $this->PriceFormat($this->currency->format($order_total['taxcost'], $this->session->data['pec']['currency'], false, false));
                    $total += $this->PriceFormat($this->currency->format($order_total['discount'], $this->session->data['pec']['currency'], false, false));
                    $total += $this->PriceFormat($this->currency->format($order_total['handling'], $this->session->data['pec']['currency'], false, false));
                    $total += $this->PriceFormat($this->currency->format($order_total['shippingcost'], $this->session->data['pec']['currency'], false, false));
                    $request .= '&PAYMENTREQUEST_0_AMT=' . urlencode($this->PriceFormat($total));
                } else {
                    $request .= '&PAYMENTREQUEST_0_AMT=' . urlencode($this->PriceFormat($this->currency->format($order_total['total'], $this->session->data['pec']['currency'], false, false)));
                }
            } else {
                $request .= '&PAYMENTREQUEST_0_AMT=' . urlencode($this->PriceFormat($this->currency->format($order_total['total'], $this->session->data['pec']['currency'], false, false)));
            }

            $response = $this->sendTransactionToGateway($curl, $request);

            $response_data = array();
            parse_str($response, $response_data);

            if (($response_data['ACK'] == 'Success') || ($response_data['ACK'] == 'SuccessWithWarning')) {
                $this->load->model('checkout/order');

                $order_info = $this->model_checkout_order->getOrder($this->session->data['unpaid_order_id']);
                if (!$order_info['order_status_id']) {
                    $this->model_checkout_order->confirm($this->session->data['unpaid_order_id'], $this->config->get('paypal_express_order_status_id'));
                }
                $message = '';

                if (isset($response_data['PAYMENTINFO_0_TRANSACTIONID'])) {
                    $message .= 'TRANSACTIONID: ' . $response_data['PAYMENTINFO_0_TRANSACTIONID'] . "\n";
                }
                if (isset($response_data['PAYMENTINFO_0_PAYMENTSTATUS'])) {
                    $message .= 'PAYPAL STATUS: ' . $response_data['PAYMENTINFO_0_PAYMENTSTATUS'] . "\n";
                }

                $message .= "\n";
                foreach ($response_data as $field => $value) {
                    $message .= $field . ': ' . $value . "\n";
                }

                if ($message) {
                    if ($response_data['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed') {
                        $this->model_checkout_order->update($this->session->data['unpaid_order_id'], $order_info["order_status_id"], $message, false);

                    } else {
                        $this->model_checkout_order->update($this->session->data['unpaid_order_id'], $order_info["order_status_id"], $message, false);
                        $this->model_checkout_order->sendMail(array(
                            $this->config->get("config_dev_email"),
                            $this->config->get("config_billing_email"),
                            "support@unlockpandasupport.zendesk.com",
                            "emilio@unlockriver.com"
                        ), sprintf("Order #%s PayPal status: %s", $this->session->data['unpaid_order_id'], $response_data['PAYMENTINFO_0_PAYMENTSTATUS']),
                            sprintf("Please check order %s", $this->session->data['unpaid_order_id']));
                        $this->notifier->add(
                            (new Notification())
                                ->setError("PAYMENTINFO_0_PAYMENTSTATUS", $response_data['PAYMENTINFO_0_PAYMENTSTATUS'])
                        )->notify();
                    }
                }

                $json['success'] = $this->url->link('checkout/success?st=Completed', '', 'SSL');
            } else {
                $this->notifier->add(
                    (new Notification())
                        ->setError("ACK", $response_data['ACK'])
                )->notify();

                $json['error'] = '';
                for ($i = 0; $i < 10; ++$i) {
                    if (isset($response_data['L_ERRORCODE' . $i])) {
                        if ($response_data['L_ERRORCODE' . $i] == '10413') {
                            if ($this->repeatDoExpressCheckoutPayment <= 2) {
                                ++$this->repeatDoExpressCheckoutPayment;
                                if ($this->repeatDoExpressCheckoutPayment == 1) {
                                    $this->pay($directly, true);
                                } elseif ($this->repeatDoExpressCheckoutPayment == 2) {
                                    $this->pay($directly, false, true);
                                }
                                return;
                            }
                        } elseif ($response_data['L_ERRORCODE' . $i] == '10486') {
                            $this->log->write(sprintf("Error: redirecting to PP! %s", $this->session->data['unpaid_order_id']));
                            $this->_setFlash($this->language->get("error_payment_pp"), "attention");

                        } else {
                            if (isset($response_data['L_SEVERITYCODE' . $i]) && isset($response_data['L_ERRORCODE' . $i]) && isset($response_data['L_LONGMESSAGE' . $i])) {
                                $message = $response_data['L_SEVERITYCODE' . $i] . ': ' . $response_data['L_ERRORCODE' . $i] . ' - ' . $response_data['L_LONGMESSAGE' . $i] . '<br />';
                                $json['error'] .= $message;

                                $this->notifier->add(
                                    (new Notification())
                                        ->setError("L_ERRORCODE", $message)
                                )->notify();
                            }
                        }
                    } else {
                        $i = 10;
                    }
                }
                $this->cancel(true);
                $json['success'] = $this->url->link('main/checkout', '', 'SSL');
            }
            if ($directly) {
                return $json;
            }
            $this->response->setOutput(json_encode($json));
        } catch (Exception $e) {
            $message = $e->getMessage();
            $json['error'] = $message;

            $this->notifier->add(
                (new Notification())
                    ->setError("EXCEPTION", $message)
            )->notify();
        }
        if ($directly) {
            return $json;
        }
        $this->response->setOutput(json_encode($json));
    }

    public function finalize() {
        try {
            if (isset($this->session->data['pec']['token'])) {
                if ($this->session->data['pec']['token'] == $this->request->get['token']) {
                    $this->session->data['pec']['login'] = true;
                }
            } else {
                $this->redirect($this->url->link('payment/paypal_express/cancel', '', 'SSL'));
            }

            if (!$this->config->get('paypal_express_test')) {
                $curl = 'https://api-3t.paypal.com/nvp';
                $p_user = $this->config->get('paypal_express_username');
                $p_pwd = $this->config->get('paypal_express_password');
                $p_signature = $this->config->get('paypal_express_signature');
            } else {
                $curl = 'https://api-3t.sandbox.paypal.com/nvp';
                $p_user = $this->config->get('paypal_express_username_test');
                $p_pwd = $this->config->get('paypal_express_password_test');
                $p_signature = $this->config->get('paypal_express_signature_test');
            }

            $request = 'METHOD=GetExpressCheckoutDetails';
            $request .= '&USER=' . $p_user;
            $request .= '&PWD=' . $p_pwd;
            $request .= '&SIGNATURE=' . $p_signature;
            $request .= '&VERSION=85.0';
            $request .= '&TOKEN=' . $this->session->data['pec']['token'];

            $response = $this->sendTransactionToGateway($curl, $request);
            $response_data = array();
            parse_str($response, $response_data);

            $order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$this->session->data['unpaid_order_id'] . "'");
            $order_info = $this->model_checkout_order->getOrder($this->session->data['unpaid_order_id']);

            foreach ($order_total_query->rows as $order_total) {
                $this->load->model('total/' . $order_total['code']);

                if (method_exists($this->{'model_total_' . $order_total['code']}, 'confirm')) {
                    $this->{'model_total_' . $order_total['code']}->confirm($order_info, $order_total);
                }
            }

            $this->model_checkout_order->updateOrderFirstLastNames($this->session->data['unpaid_order_id'],
                $response_data['FIRSTNAME'],
                $response_data['LASTNAME']
            );

            $this->session->data['pec']['payerid'] = isset($response_data['PAYERID']) ? $response_data['PAYERID'] : $this->request->get['PayerID'];

            $json = $this->pay(true);
            if (isset($json['error'])) {
                $this->session->data['pec']['error'] = $json['error'];
                $this->redirect($this->url->link('main/checkout', '', 'SSL'));
            } else {
                $this->redirect($this->url->link('main/checkout/success&st=Completed', '', 'SSL'));
            }

        } catch (Exception $e) {
            $message = $e->getMessage();
            $this->session->data['pec']['error'] = $message;

            $this->notifier->add(
                (new Notification())
                    ->setError("EXCEPTION", $message)
            )->notify();

            $this->redirect($this->url->link('main/checkout', '', 'SSL'));
        }
    }

    public function callback() {

        $this->load->library('encryption');
        $this->load->model("referral/referral");
        $this->load->model('fraud/fraud');
        $this->load->model('fraud/graph');

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
                $curl_url = 'https://www.paypal.com/cgi-bin/webscr';
            } else {
                $curl_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
            }

            $data_retrieved = false;
            $data_retrieved_tries_left = 2;
            $curl_errno = 0;
            $curl_errmsg = "";
            $curl = curl_init($curl_url);

            while(!$data_retrieved && --$data_retrieved_tries_left >= 0) {
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_TIMEOUT, 5);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

                $response = curl_exec($curl);
                $curl_errno = curl_errno($curl);
                $curl_errmsg = curl_error($curl);
                curl_close($curl);

                if($curl_errno == CURLE_OK) {
                    $data_retrieved = true;
                } else {
                    $curl = curl_init($curl_url);
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
            
            $is_verified = (strcmp($response, 'VERIFIED') == 0 || strcmp($response, 'UNVERIFIED') == 0);

            if (isset($this->request->post['payment_status'])) {
                $order_status_id = $this->config->get('config_order_status_id');
                $message = "";

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

                        // @todo: test for price
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
                                $this->model_checkout_order->sendMail($this->config->get("config_dev_email"), "Error!", "Error!");
                            }

                            if($order_info["order_status_id"] == "22") {
                                // when "Pending Approval (Unpaid)", then "Pending Approval"
                                $order_status_id = "20";
                            }

                            if($order_info["order_status_id"] == "21") {
                                // When "Fraud Detected", then "Pending Refund"
                                $order_status_id = "23";
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
                        $this->model_fraud_fraud->addFromOrder($order_id);
                        $this->model_checkout_order->sendMail($this->config->get("config_dev_email"),
                            sprintf("PayPal dispute created - order %s", $order_id),
                            sprintf("Client has created dispute for order %s.", $order_id)
                        );
                        if($order_info['order_status_id'] == "1") {
                            $this->model_checkout_order->sendMail(
                                array(
                                    $this->config->get("config_dev_email"),
                                    "emilio@unlockriver.com",
                                    "support@unlockpandasupport.zendesk.com"
                                ),
                                sprintf("PayPal order chargeback created - order %s", $order_id),
                                sprintf("Client has created dispute for order %s", $order_id)
                            );
                        }
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
                
                if(!$is_verified) {
                    try {
                        $this->model_checkout_order->sendMail(array(
                            "shamdog@gmail.com", "support@unlockpandasupport.zendesk.com", "emilio@unlockriver.com"
                        ), "PP order status change without verification - #" . $order_id, "Please check #" . $order_id . "!");
                    } catch (Exception $e) {
                        $this->log->write("Failed to send notification mail to shamdog@gmail.com");
                    }
                }

            } else {
                if(isset($this->request->post['txn_type']) && $this->request->post['txn_type'] == "new_case") {
                    // dispute or complaint is handled here
                    $handled = array("dispute", "complaint", "chargeback");
                    if(in_array($this->request->post['case_type'], $handled)) {
                        $order_status_id = "13"; // chargeback
                        $message = (isset($this->request->post['buyer_additional_information']) ? urldecode($this->request->post['buyer_additional_information']) : "Code: " . $this->request->post['reason_code']);
                        $this->model_fraud_fraud->addFromOrder($order_id);
                        $this->model_checkout_order->update($order_id, $order_status_id, $message);
                    }

                    try {
                        $this->model_checkout_order->sendMail("shamdog@gmail.com", "New chargeback", "Check " . $order_id . "!");
                    } catch (Exception $e) {
                        $this->log->write("Failed to send notification mail to shamdog@gmail.com");
                    }
                } else {
                    try {
                        $this->model_checkout_order->sendMail("shamdog@gmail.com", "Weird PP order", "Check " . $order_id . "!");
                    } catch (Exception $e) {
                        $this->log->write("Failed to send notification mail to shamdog@gmail.com");
                    }
                    $this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id'));
                }
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

        $this->model_fraud_graph->addOrder($order_id);
    }

    public function SetExpressCheckout($totalSum = false, $noOrderDetail = false) {
        $json = array();
//        if (!$this->isEnabled()) {
//            $json['error'] = 'Disabled';
//            $this->response->setOutput(json_encode($json));
//            return;
//        }

        try {
            // Validate cart has products and has stock.
            if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
                $json['success'] = $this->url->link('main/checkout', '', 'SSL');
                $json['has_products'] = !($this->cart->hasProducts());
                $json['empty'] = empty($this->session->data['vouchers']);
                $this->response->setOutput(json_encode($json));
                return;
            }

            $order_total = $this->getOrderTotals();

            // Check if the payment is authorized go to confirm step
            if (isset($this->session->data['pec']['payerid'])) {
                $this->session->data['payment_method'] = array(
                    'code' => 'paypal_express',
                    'title' => html_entity_decode($this->config->get('paypal_express_title_' . $this->config->get('config_language_id'))),
                    'sort_order' => $this->config->get('paypal_express_sort_order')
                );
                $json['success'] = $this->url->link('checkout/pec_checkout', '', 'SSL');
                $this->response->setOutput(json_encode($json));
                return;
            } elseif (isset($this->session->data['pec']['token']) && isset($this->session->data['pec']['login']) && $this->session->data['pec']['login'] == true) {
                $json['success'] = $this->url->link('payment/paypal_express/finalize', '', 'SSL');
                $this->response->setOutput(json_encode($json));
                return;
            }

            if (isset($this->session->data['unpaid_order_id']) && $this->config->get('paypal_express_confirm_order') && $this->config->get('paypal_express_skip_confirm')) {
                $this->load->model('checkout/order');
                $this->model_checkout_order->confirm($this->session->data['unpaid_order_id'], $this->config->get('paypal_express_order_status_id'));
            }

            if (isset($this->session->data['pec'])) {
                unset($this->session->data['pec']);
            }

            if (!$this->config->get('paypal_express_method')) {
                $payment_type = 'Authorization';
            } else {
                $payment_type = 'Sale';
            }

            if (!class_exists('uagent_info')) {
                require_once(DIR_SYSTEM . 'helper/device_detect.php');
                $DeviceDetect = new uagent_info();
            } else {
                global $DeviceDetect;
                if (!is_object($DeviceDetect)) {
                    $DeviceDetect = new uagent_info();
                }
            }

            if (!$this->config->get('paypal_express_test')) {
                $curl = 'https://api-3t.paypal.com/nvp';
                $paypal_url = 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout';
                $p_user = $this->config->get('paypal_express_username');
                $p_pwd = $this->config->get('paypal_express_password');
                $p_signature = $this->config->get('paypal_express_signature');
            } else {
                $curl = 'https://api-3t.sandbox.paypal.com/nvp';
                $paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout';
                $p_user = $this->config->get('paypal_express_username_test');
                $p_pwd = $this->config->get('paypal_express_password_test');
                $p_signature = $this->config->get('paypal_express_signature_test');
            }

            $mobile_checkout = false;
            if ($DeviceDetect->isIphone || $DeviceDetect->isAndroidPhone || $DeviceDetect->isTierIphone || $DeviceDetect->isTierTablet) {
                $paypal_url .= '-mobile';
                $mobile_checkout = true;
            }

            if ($this->config->get('paypal_express_skip_confirm') && isset($this->session->data['unpaid_order_id'])) {
                $paypal_url .= '&useraction=commit';
            }

            $pp_payment_type = isset($this->session->data["pp_payment_type"]) ? $this->session->data["pp_payment_type"] : "Login";

            $request = 'USER=' . $p_user;
            $request .= '&PWD=' . $p_pwd;
            $request .= '&VERSION=85.0';
            $request .= '&SIGNATURE=' . $p_signature;
            $request .= '&METHOD=SetExpressCheckout';
            $request .= '&PAYMENTREQUEST_0_PAYMENTACTION=' . $payment_type;
            $request .= '&RETURNURL=' . urlencode($this->url->link('payment/paypal_express/finalize', '', 'SSL'));
            $request .= '&CANCELURL=' . urlencode($this->url->link('payment/paypal_express/cancel', '', 'SSL'));
            $request .= '&SOLUTIONTYPE=Sole';
            $request .= '&LANDINGPAGE=' . $pp_payment_type;
            if (isset($this->session->data['unpaid_order_id'])) {
                $request .= '&TOTALTYPE=Total';
            } else {
                $request .= '&TOTALTYPE=EstimatedTotal';
            }
            if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
                $server = $this->config->get('config_ssl');
            } else {
                $server = $this->config->get('config_url');
            }
            if ($this->config->get('paypal_express_logo')) {
                $request .= '&HDRIMG=' . urlencode($server . 'image/' . $this->config->get('paypal_express_logo'));
            }

            if ($this->customer->isLogged()) {
                $request .= '&EMAIL=' . $this->customer->getEmail();
            } elseif (isset($this->session->data['email'])) {
                $request .= '&EMAIL=' . $this->session->data['email'];
            }

            $send_address = false;

            $this->session->data['pec']['noshipping'] = false;
            if ($this->cart->hasShipping() && $send_address) {
                $request .= '&NOSHIPPING=0';
            } elseif ($this->cart->hasShipping() && !$send_address) {
                $request .= '&NOSHIPPING=2';
            } else {
                $request .= '&NOSHIPPING=1';
                $this->session->data['pec']['noshipping'] = true;
            }

            if($this->session->data['language'] == 'en') {
                $localecode = 'en_US';
            } elseif ($this->session->data['language'] == 'es') {
                $localecode = 'es_XC';
            } else {
                $localecode = $this->config->get('paypal_express_merchant_country');
            }

            if ($mobile_checkout) {
                $mobile_localecode_denied = array('AD', 'AL', 'AM', 'BR', 'GE', 'VA', 'IN', 'MC', 'MD', 'UA');
                if (in_array($localecode, $mobile_localecode_denied)) {
                    $localecode = 'en_US';
                    $request .= '&LOCALECODE=en_US';
                } else {
                    $request .= '&LOCALECODE=' . $localecode;
                }
            } else {
                $request .= '&LOCALECODE=' . $localecode;
            }


            $this->session->data['pec']['localecode'] = $localecode;
            $this->getSupportedCurrencyCode();

            if (!$noOrderDetail) {
                if ($totalSum) {
                    $total = 0;
                }
                if ($this->config->get('paypal_express_senditem')) {
                    $n = 0;
                    foreach ($this->cart->getProducts() as $product) {
                        $options = '';
                        $optionsName = '';
                        if (isset($product['option']) && $product['option']) {
                            foreach ($product['option'] as $option) {
                                $options .= $option['name'] . ': ' . $option['option_value'] . ', ';
                            }
                            $optionsName = substr($options, 0, -2);
                        }

                        $carrier = $this->model_catalog_manufacturer->getManufacturer($product['carrier']);
                        $request .= '&L_PAYMENTREQUEST_0_NAME' . $n . '=' . urlencode(html_entity_decode($product['name']) . ' - ' . $this->session->data['email'] . ' (carrier: ' . htmlspecialchars_decode($carrier['name']) . ')');
                        //$request .= '&L_PAYMENTREQUEST_0_NUMBER' . $n . '=' . urlencode($product['model']);
                        $request .= '&L_PAYMENTREQUEST_0_NUMBER' . $n . '=' . urlencode($product['imei']);
                        if ($optionsName) {
                            $request .= '&L_PAYMENTREQUEST_0_DESC' . $n . '=' . urlencode($optionsName);
                        }
                        $request .= '&L_PAYMENTREQUEST_0_AMT' . $n . '=' . urlencode($this->PriceFormat($this->currency->format($product['price'], $this->session->data['pec']['currency'], false, false)));
                        $request .= '&L_PAYMENTREQUEST_0_QTY' . $n . '=' . urlencode($product['quantity']);
                        ++$n;
                        if ($totalSum) {
                            $total += $this->PriceFormat($this->currency->format($product['price'], $this->session->data['pec']['currency'], false, false));
                        }
                    }
                }

                $request .= '&PAYMENTREQUEST_0_NOTIFYURL=' . urlencode($this->url->link('payment/paypal_express/callback'));
                $request .= '&PAYMENTREQUEST_0_ITEMAMT=' . urlencode($this->PriceFormat($this->currency->format($order_total['subtotal'], $this->session->data['pec']['currency'], false, false)));
                $request .= '&PAYMENTREQUEST_0_TAXAMT=' . urlencode($this->PriceFormat($this->currency->format($order_total['taxcost'], $this->session->data['pec']['currency'], false, false)));
                $request .= '&PAYMENTREQUEST_0_SHIPDISCAMT=' . urlencode($this->PriceFormat($this->currency->format($order_total['discount'], $this->session->data['pec']['currency'], false, false)));
                $request .= '&PAYMENTREQUEST_0_HANDLINGAMT=' . urlencode($this->PriceFormat($this->currency->format($order_total['handling'], $this->session->data['pec']['currency'], false, false)));
                $request .= '&PAYMENTREQUEST_0_SHIPPINGAMT=' . urlencode($this->PriceFormat($this->currency->format($order_total['shippingcost'], $this->session->data['pec']['currency'], false, false)));
                if ($totalSum) {
                    $total += $this->PriceFormat($this->currency->format($order_total['taxcost'], $this->session->data['pec']['currency'], false, false));
                    $total += $this->PriceFormat($this->currency->format($order_total['discount'], $this->session->data['pec']['currency'], false, false));
                    $total += $this->PriceFormat($this->currency->format($order_total['handling'], $this->session->data['pec']['currency'], false, false));
                    $total += $this->PriceFormat($this->currency->format($order_total['shippingcost'], $this->session->data['pec']['currency'], false, false));
                    $request .= '&PAYMENTREQUEST_0_AMT=' . urlencode($this->PriceFormat($total));
                } else {
                    $request .= '&PAYMENTREQUEST_0_AMT=' . urlencode($this->PriceFormat($this->currency->format($order_total['total'], $this->session->data['pec']['currency'], false, false)));
                }
            } else {
                $request .= '&PAYMENTREQUEST_0_AMT=' . urlencode($this->PriceFormat($this->currency->format($order_total['total'], $this->session->data['pec']['currency'], false, false)));
            }
            $request .= '&PAYMENTREQUEST_0_CURRENCYCODE=' . urlencode($this->session->data['pec']['currency']);
            $response = $this->sendTransactionToGateway($curl, $request);

            $response_data = array();
            parse_str($response, $response_data);

            if (($response_data['ACK'] == 'Success') || ($response_data['ACK'] == 'SuccessWithWarning')) {
                // $json['success'] = $paypal_url . '&token=' . $response_data['TOKEN'];
                $json['token'] = $response_data['TOKEN'];
                $this->session->data['pec']['token'] = $response_data['TOKEN'];
            } else {
                $json['error'] = '';
                for ($i = 0; $i < 10; ++$i) {
                    if (isset($response_data['L_ERRORCODE' . $i])) {
                        if ($response_data['L_ERRORCODE' . $i] == '10413') {
                            if ($this->repeatSetExpressCheckout <= 2) {
                                ++$this->repeatSetExpressCheckout;
                                if ($this->repeatSetExpressCheckout == 1) {
                                    $this->SetExpressCheckout(true);
                                } elseif ($this->repeatSetExpressCheckout == 2) {
                                    $this->SetExpressCheckout(false, true);
                                }
                                return;
                            }
                        }
                        if (isset($response_data['L_SEVERITYCODE' . $i]) && isset($response_data['L_ERRORCODE' . $i]) && isset($response_data['L_LONGMESSAGE' . $i])) {
                            $json['error'] .= $response_data['L_SEVERITYCODE' . $i] . ': ' . $response_data['L_ERRORCODE' . $i] . ' - ' . $response_data['L_LONGMESSAGE' . $i] . "\n";
                        }
                    } else {
                        $i = 10;
                    }
                }
            }
            $this->response->setOutput(json_encode($json));
        } catch (Exception $e) {
            $json['error'] = $e->getMessage();
        }
        $this->response->setOutput(json_encode($json));
    }

    private function getSupportedCurrencyCode() {
        if (in_array($this->session->data['currency'], $this->_supportedCurrencyCodes)) {
            $this->session->data['pec']['currency'] = $this->session->data['currency'];
        } elseif ($this->session->data['pec']['localecode'] == 'BR' && $this->session->data['currency'] == 'BRL') {
            $this->session->data['pec']['currency'] = 'BRL';
        } elseif ($this->session->data['pec']['localecode'] == 'MY' && $this->session->data['currency'] == 'MYR') {
            $this->session->data['pec']['currency'] = 'MYR';
        } elseif ($this->session->data['pec']['localecode'] == 'TR' && $this->session->data['currency'] == 'TRY') {
            $this->session->data['pec']['currency'] = 'TRY';
        } else {
            $this->session->data['pec']['currency'] = $this->config->get('paypal_express_default_currency') ? $this->config->get('paypal_express_default_currency') : 'USD';
        }
    }

    private function PriceFormat($number) {
        return number_format($number, 2, '.', ',');
    }

    private function CreateOrder($data) {
        $this->language->load('payment/paypal_express');

        $this->session->data['payment_method'] = array(
            'code' => 'paypal_express',
            'title' => html_entity_decode($this->config->get('paypal_express_title_' . $this->config->get('config_language_id'))),
            'sort_order' => $this->config->get('paypal_express_sort_order')
        );

        if ($this->config->get('paypal_express_skip_confirm') && isset($this->session->data['unpaid_order_id']) && (!$this->cart->hasShipping() || ($this->cart->hasShipping() && isset($this->session->data['shipping_method'])))) {
            $this->skip_confirm = true;
        }

        $this->tryLogin($data['EMAIL']);

        if ($this->session->data['pec']['noshipping']) {
            unset($this->session->data['pec']['address_id']);
            $this->session->data['pec']['address_already_exists'] = false;
        } else {
            $country_query = $this->db->query("SELECT country_id FROM " . DB_PREFIX . "country WHERE iso_code_2 = '" . $this->db->escape($data['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE']) . "'");
            if ($country_query->num_rows) {
                $data['country_id'] = $country_query->row['country_id'];
            } else {
                $data['country_id'] = 0;
            }

            if (!isset($data['PAYMENTREQUEST_0_SHIPTOSTATE'])) {
                $data['PAYMENTREQUEST_0_SHIPTOSTATE'] = '0';
            }

            if ($data['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE'] == 'US') {
                $zone_query = $this->db->query("SELECT zone_id FROM " . DB_PREFIX . "zone WHERE code = '" . $this->db->escape($data['PAYMENTREQUEST_0_SHIPTOSTATE']) . "' AND country_id = '" . $data['country_id'] . "'");
            } else {
                $zone_query = $this->db->query("SELECT zone_id FROM " . DB_PREFIX . "zone WHERE name = '" . $this->db->escape($data['PAYMENTREQUEST_0_SHIPTOSTATE'] ? ucwords(strtolower($data['PAYMENTREQUEST_0_SHIPTOSTATE'])) : $data['PAYMENTREQUEST_0_SHIPTOCITY']) . "' AND country_id = '" . $data['country_id'] . "'");
                if (!$zone_query->num_rows) {
                    $zone_query = $this->db->query("SELECT zone_id FROM " . DB_PREFIX . "zone WHERE name LIKE '%" . $this->db->escape($data['PAYMENTREQUEST_0_SHIPTOSTATE'] ? ucwords(strtolower($data['PAYMENTREQUEST_0_SHIPTOSTATE'])) : $data['PAYMENTREQUEST_0_SHIPTOCITY']) . "%' AND country_id = '" . $data['country_id'] . "'");
                }
            }

            if ($zone_query->num_rows) {
                $data['zone_id'] = $zone_query->row['zone_id'];
            } else {
                $data['zone_id'] = 0;
            }

            if (($data['FIRSTNAME'] . ' ' . $data['LASTNAME']) == $data['PAYMENTREQUEST_0_SHIPTONAME']) {
                $company = '';
                $firstname = $data['FIRSTNAME'];
                $lastname = $data['LASTNAME'];
            } else {
                $names = explode(' ', $data['PAYMENTREQUEST_0_SHIPTONAME']);
                if (sizeof($names) == 2) {
                    $firstname = $names['0'];
                    $lastname = $names['1'];
                    $company = '';
                } else {
                    $firstname = $data['FIRSTNAME'];
                    $lastname = $data['LASTNAME'];
                    $company = $data['PAYMENTREQUEST_0_SHIPTONAME'];
                }
            }

            if ($this->customer->isLogged()) {
                #Check PayPal address if it already exists
                $address_query = $this->db->query("SELECT address_id from " . DB_PREFIX . "address WHERE customer_id = '" . $this->customer->getId() . "' AND firstname = '" . $this->db->escape($firstname) . "' AND lastname = '" . $this->db->escape($lastname) . "' AND city = '" . $this->db->escape($data['PAYMENTREQUEST_0_SHIPTOCITY']) . "' AND address_1 = '" . $this->db->escape($data['PAYMENTREQUEST_0_SHIPTOSTREET']) . "' AND postcode = '" . $this->db->escape(isset($data['PAYMENTREQUEST_0_SHIPTOZIP']) ? $data['PAYMENTREQUEST_0_SHIPTOZIP'] : '') . "' AND country_id = '" . $this->db->escape($data['country_id']) . "' AND zone_id = '" . $this->db->escape($data['zone_id']) . "'");
                if ($address_query->num_rows > 0) {
                    $this->session->data['pec']['address_id'] = $address_query->row['address_id'];
                    $this->session->data['pec']['address_already_exists'] = true;
                } else {
                    unset($this->session->data['pec']['address_id']);
                    $this->session->data['pec']['address_already_exists'] = false;
                }

                #If it doesn't exist, create address
                if (!isset($this->session->data['pec']['address_id'])) {
                    $PaypalAddress = array();
                    $PaypalAddress['firstname'] = $firstname;
                    $PaypalAddress['lastname'] = $lastname;
                    $PaypalAddress['email'] = $data['EMAIL'];
                    if (isset($data['PAYMENTREQUEST_0_SHIPTOPHONENUM'])) {
                        $PaypalAddress['telephone'] = $data['PAYMENTREQUEST_0_SHIPTOPHONENUM'];
                    } elseif (isset($data['PHONENUM'])) {
                        $PaypalAddress['telephone'] = $data['PHONENUM'];
                    } else {
                        $PaypalAddress['telephone'] = '';
                    }
                    $PaypalAddress['fax'] = '';
                    $PaypalAddress['company'] = $company;
                    $PaypalAddress['address_1'] = $data['PAYMENTREQUEST_0_SHIPTOSTREET'];
                    $PaypalAddress['address_2'] = (isset($data['PAYMENTREQUEST_0_SHIPTOSTREET2'])) ? $data['PAYMENTREQUEST_0_SHIPTOSTREET2'] : '';
                    $PaypalAddress['postcode'] = (isset($data['PAYMENTREQUEST_0_SHIPTOZIP'])) ? $data['PAYMENTREQUEST_0_SHIPTOZIP'] : '';
                    $PaypalAddress['city'] = $data['PAYMENTREQUEST_0_SHIPTOCITY'];
                    $PaypalAddress['country_id'] = $data['country_id'];
                    $PaypalAddress['zone_id'] = $data['zone_id'];

                    $this->load->model('account/address');
                    $this->session->data['pec']['address_id'] = $this->model_account_address->addAddress($PaypalAddress);
                    $this->session->data['payment_address_id'] = $this->session->data['pec']['address_id'];
                    if ($this->cart->hasShipping()) {
                        $this->session->data['shipping_address_id'] = $this->session->data['pec']['address_id'];
                    }
                }

                if (!isset($this->session->data['payment_address_id'])) {
                    $this->session->data['payment_address_id'] = $this->session->data['pec']['address_id'];
                }

                if ($this->cart->hasShipping()) {
                    if (!isset($this->session->data['shipping_address_id'])) {
                        $this->session->data['shipping_address_id'] = $this->session->data['pec']['address_id'];
                    }
                }
            } else {
                #Check PayPal address if it is the same to guest
                $create_account = true;
                if (isset($this->session->data['guest'])) {
                    $use_address = ($this->config->get('paypal_express_send_address') ? $this->config->get('paypal_express_send_address') : 'shipping');
                    if (isset($this->session->data['guest'][$use_address]) &&
                        $this->session->data['guest'][$use_address]['firstname'] == $firstname &&
                        $this->session->data['guest'][$use_address]['lastname'] == $lastname &&
                        $this->session->data['guest'][$use_address]['city'] == $data['PAYMENTREQUEST_0_SHIPTOCITY'] &&
                        $this->session->data['guest'][$use_address]['address_1'] == $data['PAYMENTREQUEST_0_SHIPTOSTREET'] &&
                        $this->session->data['guest'][$use_address]['postcode'] == (isset($data['PAYMENTREQUEST_0_SHIPTOZIP']) ? $data['PAYMENTREQUEST_0_SHIPTOZIP'] : '') &&
                        $this->session->data['guest'][$use_address]['country_id'] == $data['country_id'] &&
                        $this->session->data['guest'][$use_address]['zone_id'] == $data['zone_id']) {
                        $this->session->data['pec']['address_already_exists'] = true;
                        $create_account = false;
                    } else {
                        unset($this->session->data['guest']);
                        $this->session->data['pec']['address_already_exists'] = false;
                    }
                }

                if (!isset($this->session->data['guest'])) {
                    $guest = array();
                    $guest['firstname'] = $firstname;
                    $guest['lastname'] = $lastname;
                    $guest['email'] = $data['EMAIL'];

                    if (isset($data['PAYMENTREQUEST_0_SHIPTOPHONENUM'])) {
                        $guest['telephone'] = $data['PAYMENTREQUEST_0_SHIPTOPHONENUM'];
                    } elseif (isset($data['PHONENUM'])) {
                        $guest['telephone'] = $data['PHONENUM'];
                    } else {
                        $guest['telephone'] = '';
                    }

                    $guest['fax'] = '';
                    $guest['company'] = $company;
                    $guest['address_1'] = $data['PAYMENTREQUEST_0_SHIPTOSTREET'];
                    $guest['address_2'] = (isset($data['PAYMENTREQUEST_0_SHIPTOSTREET2'])) ? $data['PAYMENTREQUEST_0_SHIPTOSTREET2'] : '';
                    $guest['postcode'] = (isset($data['PAYMENTREQUEST_0_SHIPTOZIP'])) ? $data['PAYMENTREQUEST_0_SHIPTOZIP'] : '';
                    $guest['city'] = $data['PAYMENTREQUEST_0_SHIPTOCITY'];
                    $guest['country_id'] = $data['country_id'];
                    $guest['zone_id'] = $data['zone_id'];

                    $this->load->model('localisation/country');
                    $country_info = $this->model_localisation_country->getCountry($data['country_id']);

                    if ($country_info) {
                        $guest['country'] = $country_info['name'];
                        $guest['iso_code_2'] = $country_info['iso_code_2'];
                        $guest['iso_code_3'] = $country_info['iso_code_3'];
                        $guest['address_format'] = $country_info['address_format'];
                    } else {
                        $guest['country'] = '';
                        $guest['iso_code_2'] = '';
                        $guest['iso_code_3'] = '';
                        $guest['address_format'] = '';
                    }

                    $this->load->model('localisation/zone');
                    $zone_info = $this->model_localisation_zone->getZone($data['zone_id']);

                    if ($zone_info) {
                        $guest['zone'] = $zone_info['name'];
                        $guest['zone_code'] = $zone_info['code'];
                    } else {
                        $guest['zone'] = '';
                        $guest['zone_code'] = '';
                    }

                    $this->session->data['guest'] = $guest;
                    $this->session->data['guest']['payment'] = $guest;
                    $this->session->data['guest']['shipping'] = $guest;
                }

                if ($create_account && $this->config->get('paypal_express_createaccount') == 'create') {
                    if ($this->CreateAccount($this->session->data['guest'])) {
                        unset($this->session->data['guest']);

                        $address_id = $this->customer->getAddressId();
                        $this->session->data['shipping_address_id'] = $address_id;
                        $this->session->data['payment_address_id'] = $address_id;
                    }
                }
            }
        }

        $this->session->data['comment'] = (isset($this->session->data['comment'])) ? $this->session->data['comment'] : '';

        if ($this->skip_confirm) {
            $json = $this->pay(true);
            if (isset($json['error'])) {
                $this->session->data['pec']['error'] = $json['error'];
                $this->redirect($this->url->link('checkout/pec_checkout', '', 'SSL'));
            } else {
                $this->redirect($this->url->link('checkout/success', '', 'SSL'));
            }
        } else {
            $this->redirect($this->url->link('checkout/pec_checkout', '', 'SSL'));
        }
    }

    private function tryLogin($login_email) {
        if ($this->customer->isLogged()) {
            return true;
        }
        $this->load->model('account/customer');

        #Check if account already exists
        if ($this->model_account_customer->getTotalCustomersByEmail($login_email)) {
            $password_query = $this->db->query("SELECT password FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($login_email) . "'");
            $original_password = $password_query->row['password'];
            $temp_password = 'paypal_express';
            $temp_md5_password = md5($temp_password);
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET password = '" . $temp_md5_password . "' WHERE email = '" . $this->db->escape($login_email) . "'");
            if ($this->skip_confirm) {
                $cart = $this->session->data['cart'];
            }
            if ($this->customer->login($login_email, $temp_password)) {
                $this->session->data['account'] = 1;
                if ($this->skip_confirm) {
                    $this->session->data['cart'] = $cart;
                }
                $this->db->query("UPDATE " . DB_PREFIX . "customer SET password = '" . $original_password . "' WHERE email = '" . $this->db->escape($login_email) . "'");
                return true;
            } else {
                return false;
            }
        }
    }

    private function CreateAccount($data) {
        $login_email = urldecode($data['email']);

        $login = $this->tryLogin($login_email);
        if ($login === true) {
            return true;
        } elseif ($login === false) {
            return false;
        }

        $login_password = $this->CreatePassword();

        $this->customer_data = array(
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $login_email,
            'telephone' => $data['telephone'],
            'fax' => '',
            'password' => $login_password,
            'newsletter' => '1',
            'customer_group_id' => $this->config->get('config_customer_group_id'),
            'status' => '1',
            'ip' => $this->request->server['REMOTE_ADDR'],
            'company' => $data['payment']['company'],
            'address_1' => $data['payment']['address_1'],
            'address_2' => $data['payment']['address_2'],
            'city' => $data['payment']['city'],
            'postcode' => $data['payment']['postcode'],
            'zone_id' => $data['payment']['zone_id'],
            'country_id' => $data['payment']['country_id'],
            'default' => '1'
        );

        $this->request->post['email'] = $data['email'];

        $this->load->model('account/customer');
        $this->model_account_customer->addCustomer($this->customer_data);

        $approve_query = $this->db->query("SELECT approved FROM " . DB_PREFIX . "customer");
        if ($approve_query->num_rows) {
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET approved = '1' WHERE email = '" . $login_email . "'");
        }

        $this->session->data['pec']['generated'] = true;
        $this->customer->login($login_email, $login_password);
        $this->session->data['account'] = 1;

        #Send email welcome with password generated
        $this->language->load('mail/customer');

        $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

        $message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";
        $message .= $this->language->get('text_login') . "\n";
        $message .= $this->url->link('account/login', '', 'SSL') . "\n\n";
        $message .= 'E-mail: ' . $login_email . "\n";
        $message .= 'Password: ' . $login_password . "\n\n";
        $message .= $this->language->get('text_services') . "\n\n";
        $message .= $this->language->get('text_thanks') . "\n";
        $message .= $this->config->get('config_name');

        $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->hostname = $this->config->get('config_smtp_host');
        $mail->username = $this->config->get('config_smtp_username');
        $mail->password = $this->config->get('config_smtp_password');
        $mail->port = $this->config->get('config_smtp_port');
        $mail->timeout = $this->config->get('config_smtp_timeout');
        $mail->setTo($login_email);
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender($this->config->get('config_name'));
        $mail->setSubject($subject);
        $mail->setText($message);
        $mail->send();

        return true;
    }

    private function CreatePassword($length = 8) {
        $chars = '0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!$%()=?[]#';
        $chars_length = (strlen($chars) - 1);

        $password = $chars{rand(0, $chars_length)};

        for ($i = 1; $i < $length; $i = strlen($password)) {
            $r = $chars{rand(0, $chars_length)};

            if ($r != $password{$i - 1})
                $password .= $r;
        }

        return $password;
    }

    private function getOrderTotals() {
        $this->load->model('setting/extension');

        $total_data = array();
        $total = 0;
        $taxes = $this->cart->getTaxes();

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

            $sort_order = array();

            foreach ($total_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $total_data);
        }

        $order_totals = array();
        $order_totals['subtotal'] = 0;
        $order_totals['shippingcost'] = 0;
        $order_totals['taxcost'] = 0;
        $order_totals['discount'] = 0;
        $order_totals['handling'] = 0;
        $order_totals['total'] = 0;

        foreach ($total_data as $order_total) {
            if ($order_total['code'] == 'sub_total') {
                $order_totals['subtotal'] += $order_total['value'];
            } elseif ($order_total['code'] == 'shipping') {
                $order_totals['shippingcost'] += $order_total['value'];
            } elseif ($order_total['code'] == 'tax') {
                $order_totals['taxcost'] += $order_total['value'];
            } elseif ($order_total['code'] == 'total') {
                $order_totals['total'] += $order_total['value'];
            } elseif ($order_total['code'] == 'coupon' || $order_total['code'] == 'voucher' || $order_total['code'] == 'credit' || $order_total['code'] == 'reward') {
                $order_totals['discount'] += $order_total['value'];
            } elseif ($order_total['code'] == 'handling' || $order_total['code'] == 'low_order_fee') {
                $order_totals['handling'] += $order_total['value'];
            } else {
                if ($order_total['value'] > 0) {
                    $order_totals['handling'] += $order_total['value'];
                } else {
                    $order_totals['discount'] += $order_total['value'];
                }
            }
        }

        return $order_totals;
    }

    public function isEnabled() {
        if ($this->config->get('paypal_express_status')) {
            $totals = $this->getOrderTotals();
            if ($this->config->get('paypal_express_total') > $totals['total']) {
                return false;
            } elseif ($this->config->get('paypal_express_total_over') > 0 && $this->config->get('paypal_express_total_over') < $totals['total']) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    public function exceptionsErrorHandler($severity, $message, $filename, $lineno) {
        if (error_reporting() == 0) {
            return;
        }
        switch ($severity) {
            case E_NOTICE:
            case E_USER_NOTICE:
                $errors = "Notice";
                break;
            case E_WARNING:
            case E_USER_WARNING:
                $errors = "Warning";
                break;
            case E_ERROR:
            case E_USER_ERROR:
                $errors = "Fatal Error";
                break;
            default:
                $errors = "Unknown";
                if ($this->config->get('config_error_log')) {
                    $this->log->write('PHP ' . $errors . ':  ' . $message . ' in ' . $filename . ' on line ' . $lineno);
                }
                return;
                break;
        }

        if ($this->config->get('config_error_display')) {
            throw new Exception($errors . ': ' . $message . ' in ' . $filename . ' on line ' . $lineno);
        }

        if ($this->config->get('config_error_log')) {
            $this->log->write('PHP ' . $errors . ':  ' . $message . ' in ' . $filename . ' on line ' . $lineno);
        }

        return true;
    }

    public function registerShutdownFunction() {
        $last_error = error_get_last();
        if ($last_error) {
            switch ($last_error['type']) {
                case E_NOTICE:
                case E_USER_NOTICE:
                    $errors = "Notice";
                    break;
                case E_WARNING:
                case E_USER_WARNING:
                    $errors = "Warning";
                    break;
                case E_ERROR:
                case E_USER_ERROR:
                    $errors = "Fatal Error";
                    break;
                default:
                    $errors = "Unknown";
                    if ($this->config->get('config_error_log')) {
                        $this->log->write('PHP ' . $errors . ':  ' . $last_error['message'] . ' in ' . $last_error['file'] . ' on line ' . $last_error['line']);
                    }
                    return;
                    break;
            }
            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP ' . $errors . ':  ' . $last_error['message'] . ' in ' . $last_error['file'] . ' on line ' . $last_error['line']);
            }
            echo '<b>' . $errors . '</b>: ' . $last_error['message'] . ' in <b>' . $last_error['file'] . '</b> on line <b>' . $last_error['line'] . '</b>';
            exit;
        }
    }

}

?>