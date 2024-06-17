<?php

/**
 * @property ModelCatalogProduct model_catalog_product
 */
class ControllerCheckoutCart extends Controller
{
    public function index()
    {
        // $this->session->data["coupon"] = "CBRPANDA17";
        
        return $this->redirect($this->url->link('main/checkout'));

        $this->language->load('checkout/cart');
        $this->setAllFromLanguage();
        $this->data["text_gift_empty"] = $this->language->get("text_gift");
        $this->data["text_gift_full"] = $this->language->get("text_gift_to");
        if(isset($this->session->data["gift"])) {
            $this->data['text_gift'] = sprintf($this->language->get('text_gift_to'), $this->session->data["gift"]["gifted_client_name"]);
            $this->data["gift_exists"] = true;
            $this->data["gift_client_name"] = $this->session->data["gift"]["client_name"];
            $this->data["gift_gifted_client_name"] = $this->session->data["gift"]["gifted_client_name"];
            $this->data["gift_gifted_client_notify"] = $this->session->data["gift"]["gifted_client_notify"];
            $this->data["gift_gifted_client_email"] = $this->session->data["gift"]["gifted_client_email"];
        } else {
            $this->data['text_gift'] = $this->language->get('text_gift');
            $this->data["gift_exists"] = false;
            $this->data["gift_client_name"] = "";
            $this->data["gift_gifted_client_name"] = "";
            $this->data["gift_gifted_client_notify"] = "";
            $this->data["gift_gifted_client_email"] = "";
        }

        // Remove
        if (isset($this->request->get['remove'])) {
            $this->cart->remove($this->request->get['remove']);

            $this->redirect($this->url->link('checkout/cart'));
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {

            /*if (isset($this->request->get['remove'])) {
              foreach ($this->request->get['remove'] as $key => $details) {
                              $parts = explode('__', $details);
                              $imei = $parts[1];
                              $carrier = $parts[2];
                  echo 'harsh';
                    $this->cart->remove($key, $imei, $carrier);
              }
            }*/

            if (isset($this->request->post['voucher']) && $this->request->post['voucher']) {
                foreach ($this->request->post['voucher'] as $key) {
                    if (isset($this->session->data['vouchers'][$key])) {
                        unset($this->session->data['vouchers'][$key]);
                    }
                }
            }

            if (isset($this->request->post['redirect'])) {
                $this->session->data['redirect'] = $this->request->post['redirect'];
            }

            if (isset($this->request->post['quantity']) || isset($this->request->post['remove']) || isset($this->request->post['voucher'])) {
                unset($this->session->data['shipping_methods']);
                unset($this->session->data['shipping_method']);
                unset($this->session->data['payment_methods']);
                unset($this->session->data['payment_method']);
//				unset($this->session->data['reward']);

                $this->redirect($this->url->link('checkout/cart'));
            }
        }

        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['flash'] = $this->_getFlash();

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/home'),
            'text' => $this->language->get('text_home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('checkout/cart'),
            'text' => $this->language->get('heading_title'),
            'separator' => $this->language->get('text_separator')
        );

        if ($this->cart->hasProducts() || (isset($this->session->data['vouchers']) && $this->session->data['vouchers'])) {
            $this->data['heading_title'] = $this->language->get('heading_title');

            $this->data['text_select'] = $this->language->get('text_select');
            $this->data['text_weight'] = $this->language->get('text_weight');
            $this->data['text_deltime'] = $this->language->get('text_deltime');

            $this->data['text_newsletter'] = $this->language->get('text_newsletter');
            $this->data['text_agree'] = $this->language->get('text_agree');
            $this->data['text_terms'] = $this->language->get('text_terms');
            $this->data['text_payoption'] = $this->language->get('text_payoption');

            $this->data['column_action'] = $this->language->get('column_action');
            $this->data['column_remove'] = $this->language->get('column_remove');
            $this->data['column_image'] = $this->language->get('column_image');
            $this->data['column_name'] = $this->language->get('column_name');
            $this->data['column_model'] = $this->language->get('column_model');
            $this->data['column_quantity'] = $this->language->get('column_quantity');
            $this->data['column_price'] = $this->language->get('column_price');
            $this->data['column_total'] = $this->language->get('column_total');

            $this->data['button_update'] = $this->language->get('button_update');
            $this->data['button_shopping'] = $this->language->get('button_shopping');
            $this->data['button_checkout'] = $this->language->get('button_checkout');
            $this->data['text_currency'] = $this->language->get('text_currency');

            if ($this->config->get('config_customer_price') && !$this->customer->isLogged()) {
                $this->data['attention'] = sprintf($this->language->get('text_login'), $this->url->link('account/login'), $this->url->link('account/register'));
            } else {
                $this->data['attention'] = '';
            }

            if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
                $this->data['error_warning'] = $this->language->get('error_stock');
            } elseif (isset($this->session->data['error'])) {
                $this->data['error_warning'] = $this->session->data['error'];

                unset($this->session->data['error']);
            } else {
                $this->data['error_warning'] = '';
            }

            if (isset($this->session->data['success'])) {
                $this->data['success'] = $this->session->data['success'];

                unset($this->session->data['success']);
            } else {
                $this->data['success'] = '';
            }

            $this->data['action'] = $this->url->link('checkout/cart', '', 'SSL');

            if ($this->config->get('config_cart_weight')) {
                $this->data['weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
            } else {
                $this->data['weight'] = false;
            }

            $this->load->model('tool/image');

            $this->data['products'] = array();

            $products = $this->cart->getProducts();

//                        $this->debug($products);

            $max_delivery_time = "";
            $max_delivery_time_hours = 0;

            foreach ($products as $product) {

                if ($product['image']) {
                    $image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
                } else {
                    $image = '';
                }

                $option_data = array();


                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($product['price']);
                } else {
                    $price = false;
                }

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $total = $this->currency->format($product['total']);
                } else {
                    $total = false;
                }

                $this->load->model('catalog/manufacturer');
                $this->load->model('catalog/product');
                $this->load->model('catalog/category');
                $carrier = $this->model_catalog_manufacturer->getManufacturer($product['carrier']);

                $category = $this->model_catalog_product->getCategories($product['product_id']);
                $category_info = $this->model_catalog_category->getCategory($category['category_id']);

                $delivery_time = html_entity_decode($product['delivery_time'], ENT_QUOTES, 'UTF-8');

                $delivery_time_parsed = $this->model_catalog_product->deliveryTimeToHours($delivery_time);

                if($delivery_time_parsed['to']['hours'] > $max_delivery_time_hours) {
                    $max_delivery_time_hours = $delivery_time_parsed['to']['hours'];
                    $max_delivery_time = $delivery_time_parsed['original']['to'] . ' ' . $delivery_time_parsed['original']['span'];
                }

                $this->data['products'][] = array(
                    'key' => $product['key'],
                    'product_id' => $product['product_id'],
                    'thumb' => $image,
                    'name' => $product['name'],
                    'description' => html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8'),
                    'option' => $option_data,
                    'quantity' => $product['quantity'],
                    'carrier' => $carrier['name'],
                    'carrier_id' => $product['carrier'],
                    'imei' => $product['imei'],
                    'category' => $category_info['name'],
                    'stock' => $product['stock'],
                    'price' => $price,
                    'total' => $total,
                    'delivery_time' => $delivery_time,
                    'href' => $this->url->link('product/product', 'product_id=' . $product['product_id']),
                    'remove' => $this->url->link('checkout/cart', 'remove=' . $product['key'])
                );
            }

            if($max_delivery_time_hours >= $this->config->get('config_delivery_time_notice')) {
                $this->data['delivery_time_notice'] = sprintf($this->language->get('text_delivery_time_notice'), $max_delivery_time);
            } else {
                $this->data['delivery_time_notice'] = false;
            }

            // Gift Voucher
            $this->data['vouchers'] = array();

            if (isset($this->session->data['vouchers']) && $this->session->data['vouchers']) {
                foreach ($this->session->data['vouchers'] as $key => $voucher) {
                    $this->data['vouchers'][] = array(
                        'key' => $key,
                        'description' => $voucher['description'],
                        'amount' => $this->currency->format($voucher['amount'])
                    );
                }
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

            // Modules
            $this->data['modules'] = array();

            if (isset($results)) {
                foreach ($results as $result) {
                    if ($this->config->get($result['code'] . '_status') && file_exists(DIR_APPLICATION . 'controller/total/' . $result['code'] . '.php')) {
                        $this->data['modules'][] = $this->getChild('total/' . $result['code']);
                    }
                }
            }

            if (isset($this->session->data['redirect'])) {
                $this->data['continue'] = $this->session->data['redirect'];

                unset($this->session->data['redirect']);
            } else {
                $this->data['continue'] = $this->url->link('common/home');
            }

            $this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');

            if(!array_key_exists("new", $this->request->get)) {
                // production
                $this->data['stripe_enabled'] = $this->config->get('stripe_enabled_production');
            } else {
                // test (?new)
                $this->data['stripe_enabled'] = $this->config->get('stripe_enabled_new');
            }

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/cart.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/checkout/cart.tpl';
            } else {
                $this->template = 'default/template/checkout/cart.tpl';
            }

            $this->children = array(
                'payment/helpers',
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'payment/ppstandard',
                'payment/stripe',
                'common/content_bottom',
                'common/footer',
                'module/newslettersubscribe',
                'common/header'
            );

            // if(isset($this->request->get['new'])) {
                $this->template = $this->config->get('config_template') . '/template/checkout/cart_new.tpl';
                array_push($this->children, 'payment/paypal_express');
            //}

            //if(isset($this->request->get["gift"])) {
                $this->template = $this->config->get('config_template') . '/template/checkout/cart_gift.tpl';
            //}

            $this->response->setOutput($this->render());
        } else {
            $this->data['heading_title'] = $this->language->get('heading_title');

            $this->data['text_error'] = $this->language->get('text_empty');

            $this->data['button_continue'] = $this->language->get('button_continue');

            $this->data['continue'] = $this->url->link('common/home');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
            } else {
                $this->template = 'default/template/error/not_found.tpl';
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

    public function update()
    {
        $this->language->load('checkout/cart');

        $this->cart->clear();

        $json = array();

        $product_id = isset($this->request->post['product_id']) ? (int)$this->request->post['product_id'] : false;
        $carrier_id = isset($this->request->post['carrier_id']) ? (int)$this->request->post['carrier_id'] : false;
        $category_id = isset($this->request->post['category_id']) ? (int)$this->request->post['category_id'] : false;
        $force = isset($this->request->post['force']) && $this->request->post['force'] == 'true' ? true : false;
        
        if(!$product_id || !$carrier_id || !$category_id) {
            $this->notifier->add(
                (new Notification())
                    ->setError("PreliminaryAddToCartCheck", "Error")
            )->notify();

            $json["error"] = array(
                "warning" => "Error - invalid product, carrier or category."
            );
            $this->response->setOutput(json_encode($json));
            return;
        }


        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('checkout/order');

        if(!$this->model_checkout_order->validate($product_id, $carrier_id, false)) {
            $this->notifier->add(
                (new Notification())
                    ->setError("DetailedAddToCartCheck", "Error")
            )->notify();

            $json["error"] = array(
                "warning" => "Error - invalid product, carrier or category."
            );
            $this->response->setOutput(json_encode($json));
            return;
        }

        if (isset($this->request->post['product_id']) && isset($this->request->post['carrier_id']) && isset($this->request->post['category_id']) && isset($this->request->post['imei']) && isset($this->request->post['email'])) {
            
//			$this->load->model('catalog/carrier');
//			$this->load->model('catalog/manufacturer');

            $product_info = $this->model_catalog_product->getProduct($this->request->post['product_id']);

            if(!$product_info || $product_info['status'] == '2') {
                $json["error"] = array(
                    "warning" => $this->language->get("error_disabled_temporarily")
                );

                $this->response->setOutput(json_encode($json));
                return;
            }

            if(!$this->model_catalog_product->isValidOrder($this->request->post['product_id'], $this->request->post['carrier_id'], $this->request->post['imei'])) {
                $json["error"] = array(
                  "warning" => $this->language->get("error_cdma")
                );

                $this->response->setOutput(json_encode($json));
                return;
            }

            if($this->model_catalog_product->isBlocked($this->request->server['REMOTE_ADDR'], $this->request->post['email'], $this->request->post['imei'])) {
                $json["error"] = array(
                    "warning" => sprintf($this->language->get("error_blocked"), str_rot13(base64_encode($this->request->server['REMOTE_ADDR'] . ":" . $this->request->post['email'])))
                );

                $this->response->setOutput(json_encode($json));
                return;
            }

            if($this->model_catalog_product->isDelayed($this->request->post['category_id'], $this->request->post['product_id'], $this->request->post['carrier_id'])) {
                $json["delayed"] = true;
            } else {
                $json["delayed"] = false;
            }


            if (!isset($this->request->post['email'])) {
                $this->session->data['email'] = '';
            } else {
                $this->session->data['email'] = $this->request->post['email'];
            }

            if(strlen($this->session->data['email']) > 96) {
                $json["error"] = array(
                    "warning" => $this->language->get("error_email")
                );

                $this->response->setOutput(json_encode($json));
                return;
            }

            if (!isset($this->request->post['phone']) || trim($this->request->post['phone']) === '') {
                $this->session->data['phone'] = '';
            } else {
                $phone = $this->request->post['phone'];

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
                            ->setError("PhoneNumberException", $this->request->post['phone'])
                    )->notify();
                }

                if(!$valid) {
                    $json["error"] = array(
                        "warning" => sprintf($this->language->get("error_phone"))
                    );

                    $this->response->setOutput(json_encode($json));
                    return;
                }

                $this->session->data['phone'] = $phone;
            }

            $duplicate = $this->cart->existsIMEI($this->request->post['imei']);
            if ($duplicate && !$force) {
                $json['duplicate'] = true;
            } else {
                $json['duplicate'] = false;
                $this->cart->add($this->request->post['product_id'], $this->request->post['carrier_id'], $this->request->post['category_id'], $this->request->post['imei']);
            }

            $json['redirect'] = $this->url->link('checkout/cart', '', 'SSL');
            $json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));


            unset($this->session->data['shipping_methods']);
            unset($this->session->data['shipping_method']);
            unset($this->session->data['payment_methods']);
            unset($this->session->data['payment_method']);

        }

        if (isset($this->request->post['remove'])) {
            $this->cart->remove($this->request->post['remove']);

            unset($this->session->data['shipping_methods']);
            unset($this->session->data['shipping_method']);
            unset($this->session->data['payment_methods']);
            unset($this->session->data['payment_method']);
        }

        if (isset($this->request->post['voucher'])) {
            if ($this->session->data['vouchers'][$this->request->post['voucher']]) {
                unset($this->session->data['vouchers'][$this->request->post['voucher']]);
            }
        }

        $this->load->model('tool/image');

        $this->data['text_empty'] = $this->language->get('text_empty');

        $this->data['button_checkout'] = $this->language->get('button_checkout');
        $this->data['button_remove'] = $this->language->get('button_remove');

        $this->data['products'] = array();

        foreach ($this->cart->getProducts() as $result) {
            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], 40, 40);
            } else {
                $image = '';
            }

            $option_data = array();


            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($result['price']);
            } else {
                $price = false;
            }

            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $total = $this->currency->format($result['total']);
            } else {
                $total = false;
            }

            $this->data['products'][] = array(
                'key' => $result['key'],
                'product_id' => $result['product_id'],
                'thumb' => $image,
                'name' => $result['name'],
//				'model'      => $result['model'],
//				'option'     => $option_data,
                'quantity' => $result['quantity'],
                'stock' => $result['stock'],
                'price' => $price,
                'total' => $total,
                'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
            );
        }

        // Gift Voucher
//		$this->data['vouchers'] = array();

        if (isset($this->session->data['vouchers']) && $this->session->data['vouchers']) {
            foreach ($this->session->data['vouchers'] as $key => $voucher) {
                $this->data['vouchers'][] = array(
                    'key' => $key,
                    'description' => $voucher['description'],
                    'amount' => $this->currency->format($voucher['amount'])
                );
            }
        }

        // Calculate Totals
        $total_data = array();
        $total = 0;
        $taxes = $this->cart->getTaxes();

        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
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
        }

        $json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));

        $this->data['totals'] = $total_data;

        $this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/cart.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/common/cart.tpl';
        } else {
            $this->template = 'default/template/common/cart.tpl';
        }

        $json['output'] = $this->render();

        $this->response->setOutput(json_encode($json));
    }

    public function gift() {
        $this->response->addHeader("Content-Type: application/json");

        if(isset($this->request->get["action"]) && $this->request->get["action"] == "cancel") {
            unset($this->session->data["gift"]);
            return $this->response->setOutput(json_encode(array(
                "result" => true
            )));
        }

        $this->load->language("checkout/cart");

        $client_name = isset($this->request->post["client_name"]) ? $this->request->post["client_name"] : "";
        $gifted_client_name = isset($this->request->post["gifted_client_name"]) ? $this->request->post["gifted_client_name"] : "";
        $gifted_client_notify = isset($this->request->post["gifted_client_notify"]) ? $this->request->post["gifted_client_notify"] : false;
        $gifted_client_email = isset($this->request->post["gifted_client_email"]) ? $this->request->post["gifted_client_email"] : "";
        $gift_delivery_time = isset($this->request->post["gift_delivery_time"]) ? $this->request->post["gift_delivery_time"] : "";

        $error = array();

        if(strlen($client_name) < 2) {
            $error[] = $this->language->get("text_gift_error_name_short");
        }
        if(strlen($client_name) > 20) {
            $error[] = $this->language->get("text_gift_error_name_long");
        }
        if(strlen($gifted_client_name) < 2) {
            $error[] = $this->language->get("text_gift_error_gifted_name_short");
        }
        if(strlen($gifted_client_name) > 20) {
            $error[] = $this->language->get("text_gift_error_gifted_name_long");
        }
        if($gifted_client_notify == "true" && !filter_var($gifted_client_email, FILTER_VALIDATE_EMAIL)) {
            $error[] = $this->language->get("text_gift_error_gifted_email_valid");
        }
        if(DateTime::createFromFormat("Y-m-d", $gift_delivery_time) < new DateTime()) {
            $error[] = "The gift delivery time is not a future date.";
        }

        $response = array(
            "result" => true,
            "message" => ""
        );
        if(empty($error)) {
            $this->session->data["gift"] = array(
                "client_name" => $client_name,
                "gifted_client_name" => $gifted_client_name,
                "gifted_client_notify" => $gifted_client_notify,
                "gifted_client_email" => $gifted_client_email,
                "gift_delivery_time" => $gift_delivery_time
            );
        } else {
            $response["result"] = false;
            $response["message"] = implode("\n", $error);
        }

        $this->response->setOutput(json_encode($response));
    }

    public function clear() {
        $this->cart->clear();

        $this->response->setOutput(json_encode(array()));
    }
}

?>