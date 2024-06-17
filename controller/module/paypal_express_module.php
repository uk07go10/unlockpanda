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
 *  @url  http://www.webprojectsol.com/moduli-di-pagamento/paypal-express-checkout.html
 */

class ControllerModulePaypalExpressModule extends Controller {

    protected function index() {
        $this->language->load('payment/paypal_express');
        $this->data['text_wait'] = $this->language->get('text_wait');
        
        $this->load->language('module/paypal_express_module');
        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['amount_total'] = $this->cart->getTotal();
        if (file_exists(DIR_TEMPLATE . 'default/image/btn_' . $this->session->data['language'] . '_xpressCheckout.gif')) {
            $this->data['btn_pec'] = 'catalog/view/theme/default/image/btn_' . $this->session->data['language'] . '_xpressCheckout.gif';
        } else {
            $this->data['btn_pec'] = 'catalog/view/theme/default/image/btn_xpressCheckout.gif';
        }

        $this->data['actionSetExpressCheckout'] = $this->url->link('payment/paypal_express/SetExpressCheckout', '', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'SSL' : 'NONSSL'));

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/paypal_express_module.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/paypal_express_module.tpl';
        } else {
            $this->template = 'default/template/module/paypal_express_module.tpl';
        }

        $this->render();
    }

}

?>