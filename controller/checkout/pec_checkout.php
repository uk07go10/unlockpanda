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

class ControllerCheckoutPECCheckout extends Controller {

    public function index() {
        if (!isset($this->session->data['pec']['token']) && !isset($this->session->data['pec']['login'])) {
            $this->redirect($this->url->link('checkout/checkout'));
        }

        if ((!$this->cart->hasProducts() && !empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $this->redirect($this->url->link('checkout/cart'));
        }

        $products = $this->cart->getProducts();

        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['minimum'] > $product_total) {
                $this->redirect($this->url->link('checkout/cart'));
            }
        }

        $this->language->load('checkout/checkout');
        $this->language->load('payment/paypal_express');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
          'text' => $this->language->get('text_home'),
          'href' => $this->url->link('common/home'),
          'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
          'text' => $this->language->get('text_cart'),
          'href' => $this->url->link('checkout/cart'),
          'separator' => $this->language->get('text_separator')
        );

        $this->data['breadcrumbs'][] = array(
          'text' => $this->language->get('heading_title'),
          'href' => $this->url->link('checkout/pec_checkout', '', 'SSL'),
          'separator' => $this->language->get('text_separator')
        );

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_checkout_option'] = sprintf($this->language->get('text_checkout_option'));
        $this->data['text_checkout_account'] = $this->language->get('text_checkout_account');
        $this->data['text_checkout_payment_address'] = $this->language->get('text_checkout_payment_address');
        $this->data['text_checkout_shipping_address'] = $this->language->get('text_checkout_shipping_address');
        $this->data['text_checkout_shipping_method'] = $this->language->get('text_checkout_shipping_method');
        $this->data['text_checkout_payment_method'] = $this->language->get('text_checkout_payment_method');
        $this->data['text_checkout_confirm'] = $this->language->get('text_checkout_confirm');
        $this->data['text_modify'] = $this->language->get('text_modify');
        $this->data['text_use_paypal_data'] = $this->language->get('text_use_paypal_data');

        $this->data['logged'] = $this->customer->isLogged();
        $this->data['shipping_required'] = $this->cart->hasShipping();

        if (isset($this->session->data['pec']['address_already_exists'])) {
            $this->data['address_already_exists'] = $this->session->data['pec']['address_already_exists'];
        } else {
            $this->data['address_already_exists'] = false;
        }

        if ($this->config->get('paypal_express_skip_confirm') && $this->data['address_already_exists'] && (($this->data['shipping_required'] && isset($this->session->data['shipping_method'])) || !$this->data['shipping_required'])) {
            $this->session->data['pec']['skip_confirm'] = true;
        } else {
            $this->session->data['pec']['skip_confirm'] = false;
        }

        $this->session->data['pec']['payment_method'] = array(
          'code' => 'paypal_express',
          'title' => html_entity_decode($this->config->get('paypal_express_title_' . $this->config->get('config_language_id'))),
          'sort_order' => '1'
        );

        $this->data['payment_title'] = html_entity_decode($this->config->get('paypal_express_title_' . $this->config->get('config_language_id')));

        if (isset($this->session->data['shipping_method'])) {
            $this->session->data['pec']['shipping_method'] = $this->session->data['shipping_method'];
        }

        if (isset($this->session->data['pec']['error'])) {
            $this->data['error_warning'] = $this->session->data['pec']['error'];
            unset($this->session->data['pec']['error']);
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/pec_checkout.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/checkout/pec_checkout.tpl';
        } else {
            $this->template = 'default/template/checkout/pec_checkout.tpl';
        }

        $this->children = array(
          'common/column_left',
          'common/column_right',
          'common/content_top',
          'common/content_bottom',
          'common/footer',
          'common/header'
        );

        $this->response->setOutput($this->render());
    }

    public function session_method() {
        if (!isset($this->session->data['comment'])) {
            $this->session->data['comment'] = '';
        }
        if (isset($this->session->data['pec']['shipping_method'])) {
            $this->session->data['shipping_method'] = $this->session->data['pec']['shipping_method'];
        }
        $this->session->data['payment_method'] = $this->session->data['pec']['payment_method'];
    }

    public function guest() {

    }

    public function register() {
        if (isset($this->session->data['guest'])) {
            $json = array();
            foreach ($this->session->data['guest'] as $input => $value) {
                if (!is_array($value)) {
                    $json[$input] = $value;
                }
            }
            $this->response->setOutput(json_encode($json));
        }
    }

}

?>