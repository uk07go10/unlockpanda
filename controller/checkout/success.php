<?php

class ControllerCheckoutSuccess extends Controller
{
    public function index()
    {
        $order_id = '';
        $this->language->load('checkout/success');
        $data['text'] = $this->language->get('text_guest');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),
            'separator' => false
        );

        if (isset($this->session->data['order_info']) && isset($this->request->get['st']) && $this->request->get['st'] == "Completed" ) {

            $data = array();

            $data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
            $data['store_id'] = $this->config->get('config_store_id');
            $data['store_name'] = $this->config->get('config_name');

            if ($data['store_id']) {
                $data['store_url'] = $this->config->get('config_url');
            } else {
                $data['store_url'] = HTTP_SERVER;
            }

            $this->log->write("POST: " . json_encode($this->request->post));
            $this->log->write("GET: " . json_encode($this->request->get));

            $data['customer_id'] = $this->session->data['order_info']['customer_id'];
            $data['firstname'] = isset($this->request->post['first_name']) ? $this->request->post['first_name'] : $this->session->data['order_info']['firstname'];
            $data['lastname'] = isset($this->request->post['last_name']) ? $this->request->post['last_name'] : $this->session->data['order_info']['lastname'];
            $data['email'] = (isset($this->request->post['payer_email']) AND $this->request->post['payer_email'] != '') ? $this->request->post['payer_email'] : $this->session->data['order_info']['email'];
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

            //Shipping Address
            if (isset($this->session->data['guest'])) {
                $shipping_address = $this->session->data['guest']['shipping'];
                $data['shipping_firstname'] = $shipping_address['firstname'];
                $data['shipping_lastname'] = $shipping_address['lastname'];
                $data['shipping_email'] = $shipping_address['email'];
                $data['shipping_company'] = $shipping_address['company'];
                $data['shipping_address_1'] = $shipping_address['address_1'];
                $data['shipping_address_2'] = $shipping_address['address_2'];
                $data['shipping_city'] = $shipping_address['city'];
                $data['shipping_postcode'] = $shipping_address['postcode'];
                $data['shipping_zone'] = $shipping_address['zone'];
                $data['shipping_zone_id'] = $shipping_address['zone_id'];
                $data['shipping_country'] = $shipping_address['country'];
                $data['shipping_country_id'] = $shipping_address['country_id'];
                $data['shipping_address_format'] = $shipping_address['address_format'];
                $data['text'] = $this->language->get('text_iphone_guest');
            } else {
                $data['shipping_firstname'] = '';
                $data['shipping_lastname'] = '';
                $data['shipping_email'] = '';
                $data['shipping_company'] = '';
                $data['shipping_address_1'] = '';
                $data['shipping_address_2'] = '';
                $data['shipping_city'] = '';
                $data['shipping_postcode'] = '';
                $data['shipping_zone'] = '';
                $data['shipping_zone_id'] = '';
                $data['shipping_country'] = '';
                $data['shipping_country_id'] = '';
                $data['shipping_address_format'] = '';
                $data['text'] = $this->language->get('text_guest');
            }

            $this->load->model('checkout/order');

            $order_id = $this->session->data["unpaid_order_id"];

            //Remove Abandoned Cart History
            $cookie_name = "abo";
            if (isset($this->session->data['a_order_id'])) {
                $a_order_id = $this->session->data['a_order_id'];
                $this->db->query("DELETE FROM " . DB_PREFIX . "aorder WHERE order_id = '" . (int)$a_order_id . "'");
                $this->db->query("DELETE FROM " . DB_PREFIX . "aorder_product WHERE order_id = '" . (int)$a_order_id . "'");
            }

            if(isset($this->session->data['gift'])) {
                unset($this->session->data["gift"]);
            }

            unset($this->session->data["unpaid_order_id"]);
            unset($this->session->data["pec"]);
            unset($_COOKIE[$cookie_name]);
            unset($this->session->data['a_order_id']);
            //End

            $this->cart->clear();

            unset($this->session->data['order_info']);

            unset($this->session->data['guest']['shipping']);
            unset($this->session->data['email']);
            unset($this->session->data['shipping_methods']);
            unset($this->session->data['payment_method']);
            unset($this->session->data['payment_methods']);
            unset($this->session->data['guest']);
            unset($this->session->data['comment']);
            unset($this->session->data['order_id']);
            unset($this->session->data['coupon']);
            unset($this->session->data['reward']);
            unset($this->session->data['voucher']);
            unset($this->session->data['vouchers']);
        } else {
            $this->redirect($this->url->link("common/home"));
        }


        $this->document->setTitle($this->language->get('heading_title'));


        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['text_form'] = 'To complete your order please fill in the form below.';

        if ($this->customer->isLogged()) {
            $this->data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/download', '', 'SSL'), $this->url->link('information/contact'));
        } else {
            $this->data['text_message'] = sprintf($data['text'], $data['email'], $order_id, $this->model_checkout_order->getOrderDeliveryTime($order_id));
            $this->data['text_ord'] = sprintf($this->language->get('text_ord'), $order_id);
        }

        $this->data['button_continue'] = $this->language->get('button_continue');

        $this->data['continue'] = $this->url->link('common/home', '', 'SSL');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/common/success.tpl';
        } else {
            $this->template = 'default/template/common/success.tpl';
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
}

?>