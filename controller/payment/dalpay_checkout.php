<?php
class ControllerPaymentDalPayCheckout extends Controller {
	protected function index() {
	
    	$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');

		$this->load->model('checkout/order');
		
		//$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		$currency = 'USD';
		$total = $this->currency->format( $this->cart->getSubTotal(), $currency, false, false);
		

		$this->data['action'] = 'https://secure.dalpay.is/cgi-bin/order2/processorder1.pl';

        $this->data['mer_id'] = $this->config->get('dalpay_checkout_account');
        $this->data['pageid'] = $this->config->get('dalpay_checkout_pageid');  
	    $this->data['language'] = $this->session->data['language'];
        $this->data['currency_code'] = $currency;
		$this->data['total'] = $total;
		$this->data['cart_order_id'] = 0;//$this->session->data['order_id'];		
		
		$this->data['cust_name'] = html_entity_decode($this->customer->getFirstName(), ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($this->customer->getLastName(), ENT_QUOTES, 'UTF-8');
		if($this->data['cust_name'] == ''){
			$this->data['cust_name'] == '';
		}
		/*$this->data['cust_company'] = $order_info['payment_company'];
		$this->data['cust_address1'] = $order_info['payment_address_1'];
		$this->data['cust_address2'] = $order_info['payment_address_2'];		                           
		$this->data['cust_city'] = $order_info['payment_city'];
		$this->data['cust_state'] = $order_info['payment_zone'];
		$this->data['cust_zip'] = $order_info['payment_postcode'];*/
		$this->data['cust_country_code'] = 'US'; //$order_info['payment_iso_code_2'];
		if(isset($this->session->data['email'])){
							$this->data['cust_email'] = $this->session->data['email'];
		} else {
							$this->data['cust_email'] = $this->customer->getEmail();
		}
		$this->data['cust_phone'] = ''; //$order_info['telephone'];
		
		
			$this->data['cust_state'] = 'CA';//$order_info['payment_zone_code'];
			
		/*if ($this->cart->hasShipping()) {
		  $this->data['ship_address1'] = $order_info['shipping_address_1'];
		  $this->data['ship_address2'] = $order_info['shipping_address_2'];
		  $this->data['ship_city'] = $order_info['shipping_city'];
		  $this->data['ship_state'] = $order_info['shipping_zone'];
		  $this->data['ship_zip'] = $order_info['shipping_postcode'];
		  $this->data['ship_country_code'] = $order_info['shipping_iso_code_2'];
		  $this->data['shipping_method'] = $this->session->data['shipping_method']['title'];
		  $this->data['shipping_cost'] = $this->session->data['shipping_method']['cost'];
		  
		  if ($order_info['shipping_iso_code_2'] == 'US' || $order_info['payment_iso_code_2'] == 'CA') {
			$this->data['ship_state'] = $order_info['shipping_zone_code'];
		  }
		}*/
		
		$this->data['user1'] = ''; //$order_info['comment'];
		
		
		$this->data['products'] = array();
		
		$products = $this->cart->getProducts();

		foreach ($this->cart->getProducts() as $product) {
			$this->load->model('catalog/manufacturer');
            $manufacturer = $this->model_catalog_manufacturer->getManufacturer($product['carrier']);
			$this->data['products'][] = array(
				'product_id'  => $product['key'],
                'name'        => $product['name'] . ' - ' . $this->data['cust_email'],
                'carrier'     => $manufacturer['name'],
                'imei'        => $product['imei'],
                'price'       => $this->currency->format($product['price'], $currency, false, false),
                'total'       => $this->currency->format($product['price'], $currency, false, false),
                'quantity'    => $product['quantity'],
			);
		}

		/*foreach ($products as $product) {
		    $option_data = array();
			
			foreach ($product['option'] as $option) {
				$option_data[] = @$option['name'] . ': ' . @$option['value'];
			}
		
			if ($option_data) {
				$name = $product['name'] . ' ' . implode('; ', $option_data);
			} else {
				$name = $product['name'];
			}
			
			$this->data['products'][] = array(
				'name'        => $name,
				'model'       => $product['model'] . ' (ID: ' . $product['product_id'] . ')',
				'quantity'    => $product['quantity'],
				'price'		  => $this->currency->format($product['price'], $order_info['currency_code'], $order_info['currency_value'], false)
			);
		}*/

		$this->data['comment'] = ''; //$order_info['comment'];
		$this->session->data['order_info'] = array(
                                    'products'       => $this->data['products'],
                                    'currency_code'  => $this->data['currency_code'],
                                    'customer_id'    => $this->customer->getId(),
                                    'email'          => $this->data['cust_email'],
                                    'firstname'      => html_entity_decode($this->customer->getFirstName(), ENT_QUOTES, 'UTF-8'),
                                    'lastname'       => html_entity_decode($this->customer->getLastName(), ENT_QUOTES, 'UTF-8'),
                                    'payment_method' => 'dalypay_checkout',
                                    'country'        => $this->data['cust_country_code'],
                                    'total'          => $total,
                        );
		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['return_url'] = HTTPS_SERVER . 'index.php?route=checkout/success';
		} else {
			$this->data['return_url'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_3';
		}
		
		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/cart';
		} else {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}

		$this->data['user2'] = sha1($this->data['mer_id'] . '-' . $this->data['total'] . '-' . $this->data['cart_order_id'] . '-' . $this->data['currency_code'] . '-' . $this->config->get('dalpay_checkout_secret')); 
        $this->data['user3'] = $this->data['mer_id'] . '-' . $this->data['total'] . '-' . $this->data['cart_order_id'] . '-' . $this->data['currency_code'];
		$this->data['user4'] = $product['imei'];
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/dalpay_checkout.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/dalpay_checkout.tpl';
		} else {
			$this->template = 'default/template/payment/dalpay_checkout.tpl';
		}	
		
		$this->render();
	}
	
	public function callback() {

		$this->load->model('checkout/order');
		$post = $this->request->post;
        
        $control = explode('-', $post['user3']);

        /*$old_order = $this->model_checkout_order->getOrder($control[2]);
        
        if($old_order['order_status_id'] > 0) {
            #---this is old order---
            $order = $old_order;
        } else {
            #---this is new order---
            $order = $this->model_checkout_order->getOrder($control[2]);
        }*/

        $validIP = 0;
        $errors = array();  
        $message = '<!--success-->';
        $currency_code = 'USD'; //$order['currency_code'];
		$total = $this->currency->format( $this->cart->getSubTotal(), $currency_code, false, false);
		
         //fix for JPY (nearest integer)
        if($currency_code == 'JPY'){
            $decimal_places = 0;
        }else{
            $decimal_places = 2;
        }

        $cart_total = number_format($total, $decimal_places, '.', '');
       
        $failed_silent_post_msg = '<!-- order attempt failed validation --><center>Your order has been charged but was not updated at the store. Look for the confirmation email from robot@dalpay.com, then contact the store and tell them about this message.</center>';

        $dalpay_processor_ips = array('72.32.15.242', '72.3.193.254');

        if(count(explode(',', $this->check_ip())) > 1){
            $iparray = explode(',', $this->check_ip());  
            $post_ip = $iparray[0];
        } else {
            $post_ip = $this->check_ip();   
        }

        if(!in_array($post_ip, $dalpay_processor_ips)) {
            $message .= '<!-- POST IP '.$post_ip.' is not a valid DalPay Server IP -->';
        } else {
            $validIP = 1;
        }
                    
        if($validIP != 1){
            $message .= $failed_silent_post_msg;
            echo $message;
            exit;
        }

                        
        // Basic sanity checking and validation.
        $mer_id                 = $this->config->get('dalpay_checkout_account');
        $silentpass             = $this->config->get('dalpay_checkout_secret');
        $silent_post_password   = $post['SilentPostPassword'];
        $pay_type               = $post['pay_type'];
        $dalpay_order_num       = $post['order_num'];
        $total_amount           = $post['total_amount'];
        $order_currency         = $post['valuta_code'];
		$cust_email         = $post['cust_email'];
		$cust_name         = explode(' ', $post['cust_name']);
		$fname = $cust_name[0];
		$lname = $cust_name[1];
		
        $return_sig             = sha1($mer_id . '-' . $total_amount . '-' . '0' . '-' . $currency_code . '-' . $silentpass);
        
        $requiredFields = array('SilentPostPassword', 'total_amount', 'valuta_code', 'order_num', 'user2', 'user3');
        foreach ($requiredFields as $field){
            if(!isset($post[$field])){
                $errors[] = '<!-- Missing POST field: '.$field.' -->';
            }
        }        

        if($silentpass != $post['SilentPostPassword']) {
			$this->log->write('DALPAY_CALLBACK :: SilentPostPassword ' . $post['SilentPostPassword']);
            $errors[] = '<!-- Silent Post Password received does not match one set in DalPay Checkout OpenCart module -->';
        } 

        if($total_amount != $total_amount) {
			$this->log->write('DALPAY_CALLBACK :: cart_total ' . $cart_total);
            $errors[] = '<!-- Charged total received from DalPay and OpenCart cart total do not match -->';
        }

        if($order_currency != $currency_code) {
			$this->log->write('DALPAY_CALLBACK :: currency_code ' . $currency_code);
            $errors[] = '<!-- Currency received from DalPay and OpenCart cart currency do not match -->';
        }

        if(preg_match("/^\d{6}\.(\d{5}|\d{7})$/", $dalpay_order_num) == false){
			$this->log->write('DALPAY_CALLBACK :: dalpay_order_num ' . $dalpay_order_num);
            $errors[] = '<!-- DalPay order number received not in correct format -->';
        } 

        if($mer_id != (substr($dalpay_order_num, 0, 6))) {
			$this->log->write('DALPAY_CALLBACK :: mer_id ' . $mer_id);
            $errors[] = '<!-- DalPay order number received does not match MerchantID set in DalPay Checkout OpenCart module -->';
        }

        if($post['user2'] != $return_sig){
			$this->log->write('DALPAY_CALLBACK :: return_sig ' . $post['user2']);
            $errors[] = '<!-- Transaction fingerprint received from DalPay does not match -->';
        }            
            
        if(!empty($errors)){
            $message .= count($errors).' error(s):'."\n";
            
            foreach ($errors as $error){
                $message .= $error."\n";
            }
			
            echo $message;
           // exit;
        } else {
		
			//$this->model_checkout_order->confirm($control[2], $this->config->get('dalpay_checkout_order_status_id'));
			$this->session->data['order_info'] = array(
                    'email'          => $cust_email,
                    'firstname'      => html_entity_decode($fname, ENT_QUOTES, 'UTF-8'),
                    'lastname'       => html_entity_decode($lname, ENT_QUOTES, 'UTF-8')
            );
			//setcookie('cust_name', $fname.' '.$lname, time() + 60 * 60 * 24 * 1, '/', $this->request->server['HTTP_HOST']);
			//$this->log->write('DALPAY_CALLBACK :: fname ' . $fname . $lname);
			$return_url = HTTP_SERVER . 'index.php?route=checkout/success';
			echo $message . '<a href="'.$return_url.'">Click here to return to your account<script>window.location = "'.$return_url.'"</script></a>';
			exit;
		}

	}

	public function check_ip(){
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        	$ip = $_SERVER['HTTP_CLIENT_IP'];
	    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
           	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    } else {
        	$ip = $_SERVER['REMOTE_ADDR'];
	    }
	    return $ip;
	}
}
?>