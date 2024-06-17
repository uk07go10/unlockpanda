<?php   
class ControllerCommonHeader extends Controller {
	protected function index() {
		$this->data['title'] = $this->document->getTitle();
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = $this->config->get('config_ssl');
		} else {
			$this->data['base'] = $this->config->get('config_url');
		}

		if (isset($this->session->data['error']) && !empty($this->session->data['error'])) {
			$this->data['error'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} else {
			$this->data['error'] = '';
		}
                
		$this->data['description'] = $this->document->getDescription();
		$this->data['keywords'] = $this->document->getKeywords();
		$this->data['links'] = $this->document->getLinks();	 
		$this->data['styles'] = $this->document->getStyles();
		$this->data['scripts'] = $this->document->getScripts();
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
		$this->data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
		$this->data['zendesk'] = html_entity_decode($this->config->get('config_zendesk'), ENT_QUOTES, 'UTF-8');
		$this->data['crazyegg'] = html_entity_decode($this->config->get('config_crazyegg'), ENT_QUOTES, 'UTF-8');

		$this->language->load('common/header');
		$this->setAllFromLanguage();

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = HTTPS_IMAGE;
		} else {
			$server = HTTP_IMAGE;
		}	
				
		if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->data['icon'] = $server . $this->config->get('config_icon');
		} else {
			$this->data['icon'] = '';
		}
		
		$this->data['name'] = $this->config->get('config_name');
				
		if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
			$this->data['logo'] = $server . $this->config->get('config_logo');
		} else {
			$this->data['logo'] = '';
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
		}
		
		$this->data['text_home'] = $this->language->get('text_home');
		$this->data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		$this->data['text_cart'] = $this->language->get('text_cart');
		//$this->data['text_items'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0));
                $this->data['text_search'] = $this->language->get('text_search');
		$this->data['text_welcome'] = sprintf('<a href="%s">Login</a> | <a href="%s">Register</a>', $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
		$this->data['text_logged'] = sprintf('<a href="%s">%s</a> | <a href="%s">LogOut</a>', $this->url->link('account/account', '', 'SSL'),$this->customer->getFirstName(). ' ' . $this->customer->getLastName(), $this->url->link('account/logout', '', 'SSL'));
		$this->data['text_account'] = $this->language->get('text_account');
            	$this->data['text_checkout'] = $this->language->get('text_checkout');
		$this->data['text_language'] = $this->language->get('text_language');
        $this->data['text_currency'] = $this->language->get('text_currency');

		$this->data['text_howitworks'] = $this->language->get('text_howitworks');
		$this->data['text_testimonials'] = $this->language->get('text_testimonials');
		$this->data['text_faq'] = $this->language->get('text_faq');
		$this->data['text_orderstatus'] = $this->language->get('text_orderstatus');
		$this->data['text_codeintructions'] = $this->language->get('text_codeintructions');
		$this->data['text_troubleshooting'] = $this->language->get('text_troubleshooting');
				
		$this->data['home'] = $this->url->link('common/home', '', 'SSL');
		$this->data['about_us'] = $this->url->link('information/information&information_id=4', '', 'SSL');
		$this->data['how_it_works'] = $this->url->link('information/information&information_id=3', '', 'SSL');
		$this->data['contact'] = $this->url->link('information/contact', '', 'SSL');
		$this->data['logged'] = $this->customer->isLogged();
		$this->data['account'] = $this->url->link('account/account', '', 'SSL');
		$this->data['cart'] = $this->url->link('checkout/cart', '', 'SSL');
		$this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
		
		if (isset($this->request->get['filter_name'])) {
			$this->data['filter_name'] = $this->request->get['filter_name'];
		} else {
			$this->data['filter_name'] = '';
		}
		
		$this->data['action'] = $this->url->link('common/home', '', 'SSL');

		if (!isset($this->request->get['route'])) {
			$this->data['redirect'] = $this->url->link('common/home', '', 'SSL');
		} else {
			$data = $this->request->get;
			
			unset($data['_route_']);
			
			$route = $data['route'];
			
			unset($data['route']);
			
			$url = '';
			
			if ($data) {
				$url = '&' . urldecode(http_build_query($data, '', '&'));
			}			
			
			$this->data['redirect'] = $this->url->link($route, $url);
		}

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['language_code'])) {
			$this->session->data['language'] = $this->request->post['language_code'];
		
			if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect']);
			} else {
				$this->redirect($this->url->link('common/home'));
			}
    	}		
						
		$this->data['language_code'] = $this->session->data['language'];
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = array();
		
		$results = $this->model_localisation_language->getLanguages();
		
		foreach ($results as $result) {
			if ($result['status']) {
				$this->data['languages'][] = array(
					'name'  => $result['name'],
					'code'  => $result['code'],
					'image' => $result['image']
				);	
			}
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['currency_code'])) {
      		// $this->currency->set($this->request->post['currency_code']);
			
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['shipping_method']);
				
			if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect']);
			} else {
				$this->redirect($this->url->link('common/home'));
			}
   		}
						
		$this->data['currency_code'] = $this->currency->getCode(); 
		
		$this->load->model('localisation/currency');
		 
		 $this->data['currencies'] = array();
		 
		$results = $this->model_localisation_currency->getCurrencies();	
		
		foreach ($results as $result) {
			if ($result['status']) {
   				$this->data['currencies'][] = array(
					'title'        => $result['title'],
					'code'         => $result['code'],
					'symbol_left'  => $result['symbol_left'],
					'symbol_right' => $result['symbol_right']				
				);
			}
		}
		
		// Menu
		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
                
                $manufacturers = $this->model_catalog_manufacturer->getManufacturers();
                $this->data['manufacturers'] = array();
		
                foreach($manufacturers as $key => $manufacturer){
                    $this->data['manufacturers'][] = array(
                                                            'manufacturer_id' => $manufacturer['manufacturer_id'],
                                                            'name' => $manufacturer['name']
                                                            ); 
                }
             
		$this->data['categories'] = array();
					
		$categories = $this->model_catalog_category->getCategories(0);
		
		foreach ($categories as $category) {
//			if ($category['top']) {
				
				// Level 1
				$this->data['categories'][] = array(
					'category_id' => $category['category_id'],
					'fullname' => $category['name'],
					'name'     => (strlen($category['name']) <= 15) ? $category['name'] : substr($category['name'], 0, 12) . '...' ,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
//			}
		}
		
		$this->data['cart_items_count'] = $this->cart->countProducts();
				
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/header.tpl';
		} else {
			$this->template = 'default/template/common/header.tpl';
		}
		
    	$this->render();
	}

	public function ajaxGetBrands() {
		$carrier_id = $this->request->get['carrier_id'];
		$this->load->model('catalog/product');
		$brands = $this->model_catalog_product->getBrandsByCarrier($carrier_id);

		uasort($brands, function ($i, $j) {
			$a=$i['name'];
			$b=$j['name'];
			if ($a == $b) return 0;
			elseif ($a > $b) return 1;
			else return -1;
		});

		if(isset($this->request->get['json']) && $this->request->get['json'] == "true") {
			$results = array();
			array_push($results, array(
				"value" => "-1",
				"text" => $this->session->data["language"] == "en" ? "-- Select Manufacturer --" : "-- Selecciona Marca --"
			));

			foreach($brands as $brand) {
				array_push($results, array(
					"value" => $brand["category_id"],
					"text" => htmlspecialchars_decode($brand["name"])
				));
			}

			echo json_encode($results);
			die();
		}

	}
        
    public function ajaxGetProducts(){

        $category_id = $this->request->get['category_id'];
        $this->load->model('catalog/product');
        $data['filter_category_id'] = $category_id;
		$data['skip_description'] = true;
		if(isset($this->request->get['carrier_id'])) {
			$data['filter_carrier_id'] = $this->request->get['carrier_id'];
		}

		$transaction_id = rand(1, 5000);
		$cache_key = "requests:" . $this->request->server['REMOTE_ADDR'];
		$requests_number = $this->cache->get($cache_key);
		$requests_number = ($requests_number ? (int)$requests_number + 1 : 1);
		$this->cache->set($cache_key, $requests_number);

		$log = new Log("ajax_log.txt");
		$log->write("TID start: " . $transaction_id . " " . json_encode($data) . " (" . $this->request->server['REMOTE_ADDR'] . " - " . $requests_number . ")");
		$products = $this->model_catalog_product->getProducts($data, true);
        $log->write("TID end: " . $transaction_id);

        uasort($products, function ($i, $j) {
            $a=$i['name'];
            $b=$j['name'];
            if ($a == $b) return 0;
            elseif ($a > $b) return 1;
            else return -1;
        });

		if(isset($this->request->get['json']) && $this->request->get['json'] == "true") {

			$results = array();
			array_push($results, array(
				"value" => "-1",
				"text" => $this->session->data["language"] == "en" ? "-- Select Model --" : "-- Selecciona Modelo --"
			));

			foreach($products as $product) {
				array_push($results, array(
					"value" => $product["product_id"],
					"text" => htmlspecialchars_decode($product["name"])
				));
			}

			echo json_encode($results);
			die();

		} else {
			$output = '<select name="product" id="default-usage-select3">';
			$output .= '<option value="">--Select Product--</option>';

			foreach($products as $key => $product){
				if($product['product_id'] != 1297){
					$output .= '<option value="' . $product['product_id'] . '">' . $product['name'] . '</option>';
				}
			}
			$output .= '</select>';
			$this->response->setOutput($output);
		}
    }
    
        public function ajaxGetProduct() {
            
            $prod_id = $this->request->get['prod_id'];
            $this->load->model('catalog/product');
        
            $product = $this->model_catalog_product->getProduct($prod_id);
            
            //let's let json take care of the rest      
            $this->response->setOutput(json_encode($product));
        }
		
	public function ajaxGetCarriers() {
            
            $country_name = $this->request->get['country_name'];
            $this->load->model('catalog/manufacturer');
        
            $carriers = $this->model_catalog_manufacturer->getCarriers($country_name);
            
			$output = '<select name="carrier" id="default-usage-select1">';
			$output .= '<option value="">--Select Carrier--</option>';
						
			foreach($carriers as $key => $carrier){
				$output .= '<option value="' . $carrier['manufacturer_id'] . '">' . $carrier['name'] . '</option>';
			}
			$output .= '</select>';
            //let's let json take care of the rest      
            $this->response->setOutput($output);
    }

	public function ajaxGetManufacturers() {
            
            $manufacturer_id = $this->request->get['manufacturer_id'];
            $this->load->model('catalog/manufacturer');
        
            $categors = $this->model_catalog_manufacturer->getManufacturerCategories($manufacturer_id);
            
			$output = '<select name="category" id="default-usage-select2">';
			$output .= '<option value="">--Select Manufacturer--</option>';
						
			foreach($categors as $key => $categor){
				$output .= '<option value="' . $categor['category_id'] . '">' . $categor['name'] . '</option>';
			}
			$output .= '</select>';
            //let's let json take care of the rest      
            $this->response->setOutput($output);
    }

}
?>