<?php 
class ControllerCheckoutIcart extends Controller {
	public function index() { 
		$this->language->load('checkout/cart');
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') { 

      		if (isset($this->request->post['remove'])) {
	    		foreach ($this->request->post['remove'] as $key => $details) {
                                $parts = explode('__', $details);
                                $imei = $parts[1];
                                $carrier = $parts[2];
          			$this->cart->remove($key, $imei, $carrier);
				}
      		}
			
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
				
				$this->redirect($this->url->link('checkout/icart'));
			}
    	}

    	$this->document->setTitle($this->language->get('heading_title'));

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'href'      => $this->url->link('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(
        	'href'      => $this->url->link('checkout/icart'),
        	'text'      => $this->language->get('heading_title'),
        	'separator' => $this->language->get('text_separator')
      	);
			
    	if ($this->cart->hasProducts() || (isset($this->session->data['vouchers']) && $this->session->data['vouchers'])) { 
      		$this->data['heading_title'] = $this->language->get('heading_title');
			
                $this->data['text_select'] = $this->language->get('text_select');
                $this->data['text_weight'] = $this->language->get('text_weight');
		
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

                $this->data['action'] = $this->url->link('checkout/icart', '', 'SSL');

                if ($this->config->get('config_cart_weight')) {
                        $this->data['weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
                } else {
                        $this->data['weight'] = false;
                }

                $this->load->model('tool/image');

                $this->data['products'] = array();

                $products = $this->cart->getProducts();

//                        $this->debug($products);

                foreach ($products as $product) {
				
						if ($product['product_id'] == 1297) {
                                $this->data['delivery_time'] = html_entity_decode($product['delivery_time'], ENT_QUOTES, 'UTF-8');
                        } else {
								$this->data['delivery_time'] = '';
                        }

                        if ($product['image']) {
                                $image = $this->model_tool_image->resize($product['image'], 150, 54);
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

                        $this->data['products'][] = array(
                                        'key'      => $product['key'],
                                        'product_id'      => $product['product_id'],
                                        'thumb'    => $image,
                                        'name'     => $product['name'],
										'description'     => html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8'),
                                        'option'   => $option_data,
                                        'quantity' => $product['quantity'],
                                        'carrier'  => $carrier['name'],
                                        'carrier_id' => $product['carrier'],
                                        'imei'     => $product['imei'],
                                        'category' => $category_info['name'],
                                        'stock'    => $product['stock'],
                                        'price'    => $price,
                                        'total'    => $total,
										'delivery_time' => html_entity_decode($product['delivery_time'], ENT_QUOTES, 'UTF-8'),
                                        'href'     => $this->url->link('product/product', 'product_id=' . $product['product_id'])
                        );
                }

                // Gift Voucher
                $this->data['vouchers'] = array();

                if (isset($this->session->data['vouchers']) && $this->session->data['vouchers']) {
                        foreach ($this->session->data['vouchers'] as $key => $voucher) {
                                $this->data['vouchers'][] = array(
                                        'key'         => $key,
                                        'description' => $voucher['description'],
                                        'amount'      => $this->currency->format($voucher['amount'])
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

                        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/icart.tpl')) {
                                $this->template = $this->config->get('config_template') . '/template/checkout/icart.tpl';
                        } else {
                                $this->template = 'default/template/checkout/icart.tpl';
                        }

                        $this->children = array(
                                'common/column_left',
                                'common/column_right',
                                'common/content_top',
                                'payment/ppstandard',
								'payment/dalpay_checkout',
                                'payment/authorizenet_aim',
                                'common/content_bottom',
                                'common/footer',
								'module/newslettersubscribe',
                                'common/header'	
                        );
						
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
	
	public function update() {
		$this->language->load('checkout/cart');
		
		$json = array();
		
		if (isset($this->request->post['product_id']) && isset($this->request->post['carrier_id']) && isset($this->request->post['category_id']) && isset($this->request->post['imei'])) {
			$this->load->model('catalog/product');
//			$this->load->model('catalog/carrier');
//			$this->load->model('catalog/manufacturer');
			
			$product_info = $this->model_catalog_product->getProduct($this->request->post['product_id']);
			
		   
			if (!isset($this->request->post['email'])) {
                       $this->session->data['email'] = '';
            } else {
                         $this->session->data['email'] = $this->request->post['email'];
            }
			
						$json['redirect'] = $this->url->link('checkout/icart', '', 'SSL');
						$this->cart->add($this->request->post['product_id'], $this->request->post['carrier_id'], $this->request->post['category_id'], $this->request->post['imei'], $this->request->post['model']);

                        $json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/icart'));

                        unset($this->session->data['shipping_methods']);
                        unset($this->session->data['shipping_method']);
                        unset($this->session->data['payment_methods']);
                        unset($this->session->data['payment_method']);			
			
		}	
		
                if (isset($this->request->post['remove'])) {
                        $this->cart->remove($this->request->post['remove'], $this->request->post['imei'], $this->request->post['carrier_id']);

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
				'key'        => $result['key'],
				'product_id' => $result['product_id'],
				'thumb'      => $image,
				'name'       => $result['name'],
//				'model'      => $result['model'],
//				'option'     => $option_data,
				'quantity'   => $result['quantity'],
				'stock'      => $result['stock'],
				'price'      => $price,
				'total'      => $total,
				'href'       => $this->url->link('product/product', 'product_id=' . $result['product_id'])
			);
		}
		
		// Gift Voucher
//		$this->data['vouchers'] = array();
		
		if (isset($this->session->data['vouchers']) && $this->session->data['vouchers']) {
			foreach ($this->session->data['vouchers'] as $key => $voucher) {
				$this->data['vouchers'][] = array(
					'key'         => $key,
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'])
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
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/icart.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/icart.tpl';
		} else {
			$this->template = 'default/template/checkout/icart.tpl';
		}
		
		$json['output'] = $this->render();
		
		$this->response->setOutput(json_encode($json));
	}
}
?>