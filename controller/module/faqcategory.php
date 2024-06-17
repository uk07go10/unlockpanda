<?php  
class ControllerModuleFaqCategory extends Controller {
	protected $faqcategory_id = 0;
	
	protected function index($module) {
		$this->language->load('module/faqcategory');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->load->model('catalog/faqcategory');
		
		
		if (isset($this->request->get['faqcategory_id'])) {			
			$this->faqcategory_id = $this->request->get['faqcategory_id'];
		}
		
		$this->data['category'] = $this->getCategories();
		if($this->data['category']){ 
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/faqcategory.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/faqcategory.tpl';
			} else {
				$this->template = 'default/template/module/faqcategory.tpl';
			}
			
			$this->render();
		}
	}
	
	protected function getCategories() {
	
		$output = '';
		
		$results = $this->model_catalog_faqcategory->getFaqCategories();
		
		if ($results) { 
			$output .= '<ul>';
    	}
		
		foreach ($results as $result) {	
			
			$output .= '<li>';
			
			
			if ($this->faqcategory_id == $result['faqcategory_id']) {
				$output .= '<a href="' . $this->url->link('information/faq/faq','faqcategory_id=' . $result['faqcategory_id'])  . '"><b>' . $result['title'] . '</b></a>';
			} else {
				$output .= '<a href="' . $this->url->link('information/faq/faq','faqcategory_id=' . $result['faqcategory_id'])  . '">' . $result['title'] . '</a>';
			}
			
        
        	$output .= '</li>'; 
		}
 
		if ($results) {
			$output .= '</ul>';
		}
		
		return $output;
	}		
}
?>