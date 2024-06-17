<?php

/**
 * @property Cart cart
 * @property ModelCatalogManufacturer model_catalog_manufacturer
 * @property ModelCatalogProduct model_catalog_product
 * @property ModelCatalogCategory model_catalog_category
 * @property ModelToolImage model_tool_image
 * @property Currency currency
 */
class ControllerMainCheckout extends Controller {
    
    public function index() {

        $dt = new DateTime('America/Los_Angeles');
        $dt_format = $dt->format('d-m');
        $special_occasion = (in_array($dt_format, array('25-11', '26-11', '27-11')) ? true: false);
        if($special_occasion) {
            $this->session->data["coupon"] = "THANKSPANDA22";
        }
        
        $this->document->setTitle("Checkout");
        
        $products = $this->cart->getProducts();
        
        if(!count($products)) {
            $this->redirect($this->url->link("main/home", "", "SSL"));
        }

        $this->document->addScript("/catalog/view/theme/web/js/checkout.js");;
        $this->document->addScript("/catalog/view/theme/web/js/accordion.js");;

        $this->document->addStyle("/catalog/view/theme/web/css/pages/payment.css");

        $this->language->load("mail/order");
        $this->language->load("checkout/cart");
        $this->language->load("main/checkout");
        $this->data = array_merge($this->data, $this->language->getData());
        

        $this->load->model('tool/image');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        
        $this->data['products'] = array();
        
        foreach($products as $product) {

            if ($product['image']) {
                $image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
            } else {
                $image = '';
            }
            
            $carrier = $this->model_catalog_manufacturer->getManufacturer($product['carrier']);
            $category = $this->model_catalog_product->getCategories($product['product_id']);
            $category_info = $this->model_catalog_category->getCategory($category['category_id']);
            $delivery_time = html_entity_decode($product['delivery_time'], ENT_QUOTES, 'UTF-8');
            
            $competitors_price = $this->currency->format($product['price'] + 10.0);
            $save = $this->currency->format(10.0);
            
            $description = html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8');
            $dom = new DOMDocument();
            $dom->loadHTML('<?xml encoding="utf-8" ?>' . $description);
            $xpath = new DOMXPath($dom);
            $nodes = $xpath->query("//*[@style]");
            foreach($nodes as $node) {
                $node->removeAttribute("style");
            }
            
            $description = $dom->saveHTML();
            
            $this->data['products'][] = array(
                'key' => $product['key'],
                'product_id' => $product['product_id'],
                'thumb' => $image,
                'name' => $product['name'],
                'description' => $description,
                'quantity' => $product['quantity'],
                'carrier' => $carrier['name'],
                'carrier_id' => $product['carrier'],
                'imei' => $product['imei'],
                'category' => $category_info['name'],
                'stock' => $product['stock'],
                'price' => $this->currency->format($product['price'], 'USD', false, false),
                'competitors_price' => $competitors_price,
                'save' => $save,
                'total' => $this->currency->format($product['price'], 'USD', false, false),
                'delivery_time' => $delivery_time,
                'href' => $this->url->link('product/product', 'product_id=' . $product['product_id']),
                'remove' => $this->url->link('main/checkout/remove', 'key=' . $product['key'])
            );
        }

        $total_data = array();
        $total = 0;
        $taxes = $this->cart->getTaxes();
        $this->load->model('setting/extension');
        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {


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
        }

        $this->data['totals'] = $total_data;
        $currency = 'USD';
        
        if(isset($this->session->data['email'])){
            $this->data['email'] = $this->session->data['email'];
        } else {
            $this->data['email'] = $this->customer->getEmail();
        }
        
        $this->load->model('catalog/manufacturer');
        
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
        if ($discount <> 0){
            $this->data['discount_amount_cart'] -= $this->currency->format($total, $currency, false, false);
        }

        $total = $this->currency->format( $this->cart->getSubTotal(), $currency, false, false);
        $this->data['currency_code'] = $currency;


        $this->data['first_name'] = html_entity_decode($this->customer->getFirstName(), ENT_QUOTES, 'UTF-8');
        $this->data['last_name'] = html_entity_decode($this->customer->getLastName(), ENT_QUOTES, 'UTF-8');
        $this->data['country'] = 'US';

        $this->data['lc'] = $this->session->data['language'];
        $this->session->data['order_info'] = array(
            'products'       => $this->data['products'],
            'currency_code'  => $this->data['currency_code'],
            'customer_id'    => $this->customer->getId(),
            'email'          => $this->data['email'],
            'firstname'      => $this->data['first_name'],
            'lastname'       => $this->data['last_name'],
            'country'        => $this->data['country'],
            'total'          => $total,
        );

        $this->template = 'web/template/main/checkout.tpl';
        $this->children = array(
            'main/navigation',
            'main/scripts',
            'main/footer',
            'payment/stripe_new',
        );
        
        $this->data['flash'] = $this->_getFlash();

        $this->response->setOutput($this->render());
    }
    
    public function remove() {
        if(isset($this->request->get["key"])) {
            $this->cart->remove($this->request->get["key"]);
        }
        
        $this->redirect($this->url->link("main/checkout", "", "SSL"));
    }

    public function completed() {
        // Amazon Pay
        
        $this->log->write(json_encode($this->request->get));
        
        if(array_key_exists("resultCode", $this->request->get)) {
            $resultCode = array_key_exists("resultCode", $this->request->get) ? $this->request->get["resultCode"] : false;
            $sellerOrderId = array_key_exists("sellerOrderId", $this->request->get) ? $this->request->get["sellerOrderId"] : false;
            $orderReferenceId = array_key_exists("orderReferenceId", $this->request->get) ? $this->request->get["orderReferenceId"] : false;
            $unpaidOrderId = array_key_exists("unpaid_order_id", $this->session->data) ? $this->session->data["unpaid_order_id"] : false;
            
            if ($resultCode === "Success" && $sellerOrderId == $unpaidOrderId) {
                $this->load->model('checkout/order');
                $this->model_checkout_order->setPaymentProviderTransactionID($sellerOrderId, $orderReferenceId);
            }
        }
        
        return $this->redirect($this->url->link('main/checkout/success', 'st=Completed', 'SSL'));
    }

    public function success() {
        
        // $this->document->addScript('/catalog/view/theme/ur/js/success.js');
        $this->document->addStyle("/catalog/view/theme/web/css/pages/payment.css");
        
        $order_id = '';
        $this->language->load('checkout/success');
        $data['text'] = $this->language->get('text_guest');

        if ((isset($this->session->data['order_info']) && isset($this->request->get['st']) && $this->request->get['st'] == "Completed") || isset($this->request->get['st']) && $this->request->get['st'] == "Force") {

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

            $data['payment_method'] = isset($this->session->data['order_info']['payment_method']) ? $this->session->data['order_info']['payment_method'] : false;
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

            $query = $this->db->query("SELECT information_id, description FROM " . DB_PREFIX . "information_description WHERE information_id = '24' AND language_id = " . (int)$this->config->get('config_language_id'));
            if ($query->num_rows) {
                $this->data['text_promo'] = html_entity_decode($query->row['description'], ENT_QUOTES, 'UTF-8');
            } else {
                $this->data['text_promo'] = '';
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

            unset($this->session->data["unpaid_order_id"]);
            unset($this->session->data["pec"]);
            unset($_COOKIE[$cookie_name]);
            unset($this->session->data['a_order_id']);
            //End

            $this->cart->clear();
            
        } else {
            $this->redirect($this->url->link("main/home"));
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

        $this->data['order_id'] = $order_id;
        $this->data['email'] = $data['email'];
        $this->data['delivery_time'] = $this->model_checkout_order->getOrderDeliveryTime($order_id);

        $this->data['button_continue'] = $this->language->get('button_continue');

        $this->data['continue'] = $this->url->link('main/home', '', 'SSL');

        $this->template = 'web/template/main/success.tpl';
        $this->children = array(
            'main/navigation',
            'main/scripts',
            'main/footer'
        );

        $this->data['flash'] = $this->_getFlash();
        $this->session->destroy();
        $this->response->setOutput($this->render());
    }

    public function link() {

        $required_params = array('carrier_id', 'brand_id', 'product_id', 'email', 'imei', 'phone', 'discount_code');

        $data = isset($this->request->get['data']) ? $this->request->get['data'] : '';

        if(!$data) {
            return $this->redirect($this->url->link('main/home'));
        }

        $this->load->library('encryption');
        $encryption = new Encryption($this->config->get('config_encryption'));
        $data = base64_decode($data);
        $data = $encryption->decrypt($data);

        $result = array();
        parse_str($data, $result);
        $valid = true;

        foreach($required_params as $param) {
            if (!array_key_exists($param, $result)) {
                $valid = false;
            }
        }

        if (!$valid) {
            return $this->redirect($this->url->link('main/home'));
        }

        $this->cart->clear();

        $this->language->load('checkout/cart');

        $json = array();

        $product_id = $result['product_id'];
        $carrier_id = $result['carrier_id'];
        $category_id = $result['brand_id'];
        $force = true;
        $imei = $result['imei'];
        $email = $result['email'];
        $discount_code = $result['discount_code'];

        if(!$product_id || !$carrier_id || !$category_id) {
            $this->notifier->add(
                (new Notification())
                    ->setError("PreliminaryAddToCartCheck", "Error")
            )->notify();

            $this->_setFlash("Error - invalid product, carrier or category.", "error");
            return $this->redirect($this->url->link('main/home'));
        }


        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');

        $carrier = $this->model_catalog_manufacturer->getManufacturer($carrier_id);
        $category = $this->model_catalog_product->getCategories($product_id);
        if($category) {
            $category_info = $this->model_catalog_category->getCategory($category['category_id']);
        } else {
            $category_info = false;
        }
        $product = $this->model_catalog_product->getProduct($product_id);
        $carrier_products = $this->model_catalog_product->getCarriersByProduct($product_id);
        $carrier_product_exists = false;
        foreach($carrier_products as $carrier_product) {
            if ($carrier_product['manufacturer_id'] == $carrier_id) {
                $carrier_product_exists = true;
            }
        }

        if(!$carrier || !$category_info || !$product || !$carrier_product_exists) {
            $this->notifier->add(
                (new Notification())
                    ->setError("DetailedAddToCartCheck", "Error")
            )->notify();

            $this->_setFlash("Error - invalid product, carrier or category.", "error");
            return $this->redirect($this->url->link('main/home'));
        }


        if (isset($product_id) && $carrier_id && $category_id && $imei && $email) {

//			$this->load->model('catalog/carrier');
//			$this->load->model('catalog/manufacturer');

            $product_info = $this->model_catalog_product->getProduct($product_id);

            if(!$product_info || $product_info['status'] == '2') {
                $this->_setFlash($this->language->get("error_disabled_temporarily"), "error");
                return $this->redirect($this->url->link('main/home'));
            }

            if(!$this->model_catalog_product->isValidOrder($product_id, $carrier_id, $imei)) {
                $this->_setFlash($this->language->get("error_cdma"), "error");
                return $this->redirect($this->url->link('main/home'));
            }

            if($this->model_catalog_product->isBlocked($this->request->server['REMOTE_ADDR'], $email, $imei)) {
                $this->_setFlash(sprintf($this->language->get("error_blocked"), str_rot13(base64_encode($this->request->server['REMOTE_ADDR'] . ":" . $email))), "error");
                return $this->redirect($this->url->link('main/home'));
            }

            if (!isset($result['phone']) || trim($result['phone']) === '') {
                $this->session->data['phone'] = '';
            } else {
                $phone = $result['phone'];

                $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
                $valid = false;

                try {
                    $phoneParsed = $phoneUtil->parse($phone);
                    if($phoneUtil->isValidNumber($phoneParsed)) {
                        $valid = true;
                        $phone = $phoneUtil->format($phoneParsed, \libphonenumber\PhoneNumberFormat::E164);
                    }
                } catch (\libphonenumber\NumberParseException $e) {
                    $this->notifier->add(
                        (new Notification())
                            ->setError("PhoneNumberException", $phone)
                    )->notify();
                }

                $this->session->data['phone'] = $phone;
            }

            $this->session->data['email'] = $email;
            $this->cart->add($product_id, $carrier_id, $category_id, $imei);

            unset($this->session->data['shipping_methods']);
            unset($this->session->data['shipping_method']);
            unset($this->session->data['payment_methods']);
            unset($this->session->data['payment_method']);


            if ($discount_code) {
                $this->load->model('checkout/coupon');
                $coupon_info = $this->model_checkout_coupon->getCoupon($discount_code);
                if ($coupon_info) {
                    $this->session->data['coupon'] = $discount_code;


                }
            }
        }

        return $this->redirect($this->url->link('main/checkout'));
    }
}