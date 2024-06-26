<?php
class ControllerPaymentAuthorizeNetAim extends Controller {
	protected function index() {
//        echo "<pre>";
//        print_r($this->session->data);
//        echo "</pre>";
//        die();
		$this->language->load('payment/authorizenet_aim');
		
		$this->data['text_credit_card'] = $this->language->get('text_credit_card');
		$this->data['text_wait'] = $this->language->get('text_wait');
		
		$this->data['entry_cc_owner'] = $this->language->get('entry_cc_owner');
		$this->data['entry_cc_number'] = $this->language->get('entry_cc_number');
		$this->data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
		$this->data['entry_cc_cvv2'] = $this->language->get('entry_cc_cvv2');
        $this->data['entry_email'] = $this->language->get('entry_email');
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		
		$this->data['months'] = array();
		
		for ($i = 1; $i <= 12; $i++) {
			$this->data['months'][] = array(
				'text'  => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)), 
				'value' => sprintf('%02d', $i)
			);
		}
		
		$today = getdate();

		$this->data['year_expire'] = array();

		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$this->data['year_expire'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)) 
			);
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/authorizenet_aim.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/authorizenet_aim.tpl';
		} else {
			$this->template = 'default/template/payment/authorizenet_aim.tpl';
		}	
		
		$this->render();		
	}
	
	public function send() {
		if ($this->config->get('authorizenet_aim_server') == 'live') {
    		$url = 'https://secure.authorize.net/gateway/transact.dll';
		} elseif ($this->config->get('authorizenet_aim_server') == 'test') {
			$url = 'https://test.authorize.net/gateway/transact.dll';		
		}
        
		$json = array();
        if($this->request->post['payer_email'] == '' ){
            $json['error']['payer_email'] = true;
        }
        if($this->request->post['cc_owner'] == '' ){
            $json['error']['cc_owner'] = true;
        }
		if($this->request->post['cc_number'] == '' ){
            $json['error']['cc_number'] = true;
        }
        if($this->request->post['cc_expire_date_month'] == '' ){
            $json['error']['cc_expire_date_month'] = true;
        }
        if($this->request->post['cc_expire_date_year'] == '' ){
            $json['error']['cc_expire_date_month'] = true;
        }
        if($this->request->post['cc_cvv2'] == '' ){
            $json['error']['cc_cvv2'] = true;
        }
        if(empty($json)) {
		//$url = 'https://secure.networkmerchants.com/gateway/transact.dll';	
		
		$this->load->model('checkout/order');
		//$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
        $data = array();
        
        $currency = 'USD';
        $total = $this->currency->format( $this->cart->getSubTotal(), $currency, false, false);
		$data['x_login'] = $this->config->get('authorizenet_aim_login');
		$data['x_tran_key'] = $this->config->get('authorizenet_aim_key');
		$data['x_version'] = '3.1';
		$data['x_delim_data'] = 'true';
		$data['x_delim_char'] = ',';
		$data['x_encap_char'] = '"';
		$data['x_relay_response'] = 'false';
        $name = $this->request->post['cc_owner'];
        $firstname = explode(' ', $name);
        $data['x_first_name'] = html_entity_decode($this->customer->getFirstName(), ENT_QUOTES, 'UTF-8');
        $data['x_last_name'] = html_entity_decode($this->customer->getLastName(), ENT_QUOTES, 'UTF-8');
        foreach ($this->cart->getProducts() as $product) {
                        $option_data = array();

//				$this->debug($product);
                        $this->load->model('catalog/manufacturer');
                        $manufacturer = $this->model_catalog_manufacturer->getManufacturer($product['carrier']);
                        $this->data['products'][] = array(
                                'product_id'  => $product['key'],
                                'name'        => $product['name'],
                                'carrier'     => $manufacturer['name'],
                                'imei'        => $product['imei'],
                                'price'       => $this->currency->format($product['price'], $currency, false, false),
                                'total'       => $this->currency->format($product['price'], $currency, false, false),
                                'quantity'    => $product['quantity'],
                        );
                }
        $this->session->data['first_name'] = $firstname[0];
        $this->session->data['last_name'] = $firstname[1];
        $this->session->data['payer_email'] = $this->request->post['payer_email'];
        $this->data['email'] = $this->customer->getEmail();
		//$data['x_first_name'] = html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');
		//$data['x_last_name'] = html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
//		$data['x_company'] = html_entity_decode($order_info['payment_company'], ENT_QUOTES, 'UTF-8');
//		$data['x_address'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
//		$data['x_city'] = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
//		$data['x_state'] = html_entity_decode($order_info['payment_zone'], ENT_QUOTES, 'UTF-8');
//		$data['x_zip'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
//		$data['x_country'] = html_entity_decode($order_info['payment_country'], ENT_QUOTES, 'UTF-8');
		//$data['x_phone'] = $order_info['telephone'];
		$data['x_customer_ip'] = $this->request->server['REMOTE_ADDR'];
		$data['x_email'] = $this->customer->getEmail();
		$data['x_description'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
//		$data['x_amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], 1.00000, false);
        $data['x_amount'] = $this->request->post['total'];
		$data['x_currency_code'] = 'USD';
		$data['x_method'] = 'CC';
		$data['x_type'] = ($this->config->get('authorizenet_aim_method') == 'capture') ? 'AUTH_CAPTURE' : 'AUTH_ONLY';
		$data['x_card_num'] = str_replace(' ', '', $this->request->post['cc_number']);
		$data['x_exp_date'] = $this->request->post['cc_expire_date_month'] . $this->request->post['cc_expire_date_year'];
		$data['x_card_code'] = $this->request->post['cc_cvv2'];
		//$data['x_invoice_num'] = $this->session->data['order_id'];
        $this->data['first_name'] = html_entity_decode($this->customer->getFirstName(), ENT_QUOTES, 'UTF-8');	
			$this->data['last_name'] = html_entity_decode($this->customer->getLastName(), ENT_QUOTES, 'UTF-8');	
//			$this->data['address1'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');	
//			$this->data['address2'] = html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8');	
//			$this->data['city'] = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');	
//			$this->data['zip'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');	
			$this->data['country'] = 'US';
			$this->data['notify_url'] = $this->url->link('payment/ppstandard/callback');
//			$this->data['invoice'] = $this->session->data['order_id'] . ' - ' . html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
			$this->data['lc'] = $this->session->data['language'];
			$this->data['return'] = $this->url->link('checkout/success');
			$this->data['notify_url'] = $this->url->link('payment/ppstandard/callback');
			$this->data['cancel_return'] = $this->url->link('checkout/cart', '', 'SSL');
			
//                        set session for order_creation after completing paypal payment
                        $this->session->data['order_info'] = array(
                                    'products'       => $this->data['products'],
                                    //'currency_code'  => $this->data['currency_code'],
                                    'customer_id'    => $this->customer->getId(),
                                    'email'          => $data['x_email'],
                                    'firstname'      => $data['x_first_name'],
                                    'lastname'       => $data['x_last_name'],
                                    'payment_method' => 'authorizenet_aim',
                                    'country'        => $this->data['country'],
                                    'total'          => $total,
                        );
		if ($this->config->get('authorizenet_aim_mode') == 'test') {
			$data['x_test_request'] = 'true';
		}	
				
		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_PORT, 443);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
 
		$response = curl_exec($curl);
		
		
		if (curl_error($curl)) {
			$json['error'] = 'CURL ERROR: ' . curl_errno($curl) . '::' . curl_error($curl);
			
			$this->log->write('AUTHNET AIM CURL ERROR: ' . curl_errno($curl) . '::' . curl_error($curl));	
		} elseif ($response) {
			$i = 1;
			
			$response_data = array();
			
			$results = explode(',', $response);
			
			foreach ($results as $result) {
				$response_data[$i] = trim($result, '"');
				
				$i++;
			}
		
			if ($response_data[1] == '1') {
				if (strtoupper($response_data[38]) != strtoupper(md5($this->config->get('authorizenet_aim_hash') . $this->config->get('authorizenet_aim_login') . $response_data[6] . $this->currency->format($this->request->post['total'], 'USD', 1.00000, false)))) {
//					$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));
					$this->model_checkout_order->confirm('', $this->config->get('config_order_status_id'));
					$message = '';
					
					if (isset($response_data['5'])) {
						$message .= 'Authorization Code: ' . $response_data['5'] . "\n";
					}
					
					if (isset($response_data['6'])) {
						$message .= 'AVS Response: ' . $response_data['6'] . "\n";
					}
			
					if (isset($response_data['7'])) {
						$message .= 'Transaction ID: ' . $response_data['7'] . "\n";
					}
	
					if (isset($response_data['39'])) {
						$message .= 'Card Code Response: ' . $response_data['39'] . "\n";
					}
					
					if (isset($response_data['40'])) {
						$message .= 'Cardholder Authentication Verification Response: ' . $response_data['40'] . "\n";
					}				
	
//					$this->model_checkout_order->update($this->session->data['order_id'], $this->config->get('authorizenet_aim_order_status_id'), $message, false);
                    $this->model_checkout_order->update('', $this->config->get('authorizenet_aim_order_status_id'), $message, false);
				}
				
				$json['success'] = $this->url->link('checkout/success', '', 'SSL');
			} else {
				$json['error'] = $response_data[4];
			}
		} else {
			$json['error'] = 'Empty Gateway Response';
			
			$this->log->write('AUTHNET AIM CURL ERROR: Empty Gateway Response');
		}
		
		curl_close($curl);
        }
		$this->response->setOutput(json_encode($json));
	}
}
//355144844
?>