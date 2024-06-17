<?php 
class ControllerInformationFaq extends Controller {  
	public function index() { 
		
		$this->language->load('information/faq');
		
		$this->load->model('catalog/faq');

		$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/faq.css');

		$this->load->model('catalog/faqcategory');		
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_empty'] = $this->language->get('text_empty');
		
		$this->data['button_continue'] = $this->language->get('button_continue');
		
		$this->data['breadcrumbs'] = array();
		
      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	);
		
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('information/faq'),
			'separator' => $this->language->get('text_separator')
		);
		
		$this->data['faqcategories'] = array();

		$faqcategories = $this->model_catalog_faqcategory->getFaqCategories();		
		if($faqcategories){
			foreach ($faqcategories as $result) {
				
				$data = array(
					'faqcategory_id' => $result['faqcategory_id']
				);
				$faqs = array();
				$faq_results = $this->model_catalog_faq->getFaqs($data);
				if(count($faq_results)) {
					foreach ($faq_results as $faq_result) {	
						$faqs[] = array(
							'faq_id' => $faq_result['faq_id'],
							'title' => $faq_result['title'],
							'description' => html_entity_decode($faq_result['description'], ENT_QUOTES, 'UTF-8')
						);
					}
				}
				
				$this->data['faqcategories'][] = array(
					'faqcategory_id' => $result['faqcategory_id'],				
					'title' => $result['title'],
					'faqs'	=> $faqs				
				);


			}
			
			$this->data['continue'] = $this->url->link('common/home');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/faq_list.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/information/faq_list.tpl';
			} else {
				$this->template = 'default/template/information/faq_list.tpl';
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

		}else{

			$this->document->setTitle($this->language->get('heading_title'));

			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
			} else {
				$sort = 'fq.sort_order';
			} 

			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
			} else {
				$order = 'ASC';
			} 
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	
	
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
					
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
		
			$this->data['heading_title'] = $this->language->get('heading_title');
			$this->data['description'] = '';

			$this->data['text_empty'] = $this->language->get('text_empty');
			$this->data['text_faq'] = $this->language->get('text_faq');		
			 
			$this->data['button_continue'] = $this->language->get('button_continue');
			
			$this->data['faqs'] = array();
			
			$data = array(
				'sort'                   => $sort,
				'order'                  => $order,
				'start'                  => ($page - 1) * $this->config->get('config_catalog_limit'),
				'limit'                  => $this->config->get('config_catalog_limit')
			);
					
			$product_total = $this->model_catalog_faq->getTotalFaqs($data);
								
			$results = $this->model_catalog_faq->getFaqs($data);
					
			foreach ($results as $result) {
				
				$this->data['faqs'][] = array(
					'faq_id'  => $result['faq_id'],
					'title'       => $result['title'],
					'description' => html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')
				);
			}
						
	
			$url = '';
					
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	
	
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
					
			$url = '';
							
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	
	
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
				
			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_catalog_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('information/faq', $url . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			
			$this->data['continue'] = $this->url->link('common/home');
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/faq_info.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/information/faq_info.tpl';
			} else {
				$this->template = 'default/template/information/faq_info.tpl';
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

	public function faq() {
    	
		$this->language->load('information/faq');

		$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/faq.css');
		
		$this->load->model('catalog/faqcategory');
		
		$this->load->model('catalog/faq');
		
		if (isset($this->request->get['faqcategory_id'])) {
			$faqcategory_id = $this->request->get['faqcategory_id'];
		} else {
			$faqcategory_id = 0;
		} 
										
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'fq.sort_order';
		} 

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		} 
  		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
			
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array( 
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
      		'separator' => false
   		);
   		
		$this->data['breadcrumbs'][] = array( 
       		'text'      => $this->language->get('text_faq'),
			'href'      => $this->url->link('information/faq'),
      		'separator' => $this->language->get('text_separator')
   		);
		
		$faq_category_info = $this->model_catalog_faqcategory->getFaqCategory($faqcategory_id);
	
		if ($faq_category_info) {

			$this->document->setTitle($faq_category_info['title']);
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	
	
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
					
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}	
		   			
			$this->data['breadcrumbs'][] = array(
       			'text'      => $faq_category_info['title'],
				'href'      => $this->url->link('information/faq/faq', 'faqcategory_id=' . $this->request->get['faqcategory_id'] . $url),
      			'separator' => $this->language->get('text_separator')
   			);
		
			$this->data['heading_title'] = $faq_category_info['title'];
			$this->data['description'] = html_entity_decode($faq_category_info['description'], ENT_QUOTES, 'UTF-8');

			$this->data['text_empty'] = $this->language->get('text_empty');
			$this->data['text_faq'] = $this->language->get('text_faq');		
			 
			$this->data['button_continue'] = $this->language->get('button_continue');
			
			$this->data['faqs'] = array();
			
			$data = array(
				'faqcategory_id'		=> $faqcategory_id, 
				'sort'                   => $sort,
				'order'                  => $order,
				'start'                  => ($page - 1) * $this->config->get('config_catalog_limit'),
				'limit'                  => $this->config->get('config_catalog_limit')
			);
					
			$product_total = $this->model_catalog_faq->getTotalFaqs($data);
								
			$results = $this->model_catalog_faq->getFaqs($data);
					
			foreach ($results as $result) {
				
				$this->data['faqs'][] = array(
					'faq_id'  => $result['faq_id'],
					'title'       => $result['title'],
					'description' => html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')
				);
			}
						
	
			$url = '';
					
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	
	
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
					
			$url = '';
							
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	
	
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
				
			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_catalog_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('information/faq/faq', 'faqcategory_id=' . $this->request->get['faqcategory_id'] . $url . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			
			$this->data['continue'] = $this->url->link('common/home');
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/faq_info.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/information/faq_info.tpl';
			} else {
				$this->template = 'default/template/information/faq_info.tpl';
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
		} else {
			$url = '';
			
			if (isset($this->request->get['faqcategory_id'])) {
				$url .= '&faqcategory_id=' . $this->request->get['faqcategory_id'];
			}
									
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
				
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
						
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
						
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_error'),
				'href'      => $this->url->link('information/faq', $url),
				'separator' => $this->language->get('text_separator')
			);
				
			$this->document->setTitle($this->language->get('text_error'));

      		$this->data['heading_title'] = $this->language->get('text_error');

      		$this->data['text_error'] = $this->language->get('text_error');

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
}
?>