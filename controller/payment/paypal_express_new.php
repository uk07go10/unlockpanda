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
class ControllerPaymentPaypalExpressNew extends Controller {

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
        if(isset($this->request->get['newPP']) || true) {
            $this->template = 'ur/template/payment/paypal_express_latest.tpl';
        } else {
            $this->template = 'ur/template/payment/paypal_express_new.tpl';
        }
        

        $this->render();
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
}

?>