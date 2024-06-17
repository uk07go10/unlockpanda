<?php  
class ControllerCommonHome extends Controller {
	public function index() {

        $this->redirect($this->url->link("main/home", "", "SSL"));

		if(isset($this->request->get['r'])) {
			$this->load->model('referral/referral');
			$this->model_referral_referral->setReferral($this->request->get['r']);
		}

if (isset($this->request->post['language_code'])) {
            $this->session->data['language'] = $this->request->post['language_code'];

            if (isset($this->request->post['redirect'])) {
                $this->redirect($this->request->post['redirect']);
            } else {
                $this->redirect(HTTP_SERVER . 'index.php?route=common/home');
            }
        }

		if(isset($this->session->data['language'])){
			$this->data['lang'] = $this->session->data['language'];
		} else {
			$this->data['lang'] = 'en';
		}

		if (isset($this->request->post['category_id'])) {
			$this->data['carrier_id'] = $this->request->post['category_id'];
		} else {
			$this->data['carrier_id'] = '';
		}

		$this->language->load('common/home');
		$this->setAllFromLanguage();

        $this->document->setTitle($this->language->get('heading_title'));
		$this->document->setDescription($this->language->get('heading_description'));

		$this->data['config_less_models'] = $this->config->get('config_less_models');
		$this->data['config_models_notice'] = $this->config->get('config_models_notice');


		$this->load->model("catalog/testimonial");
		$testimonials = $this->model_catalog_testimonial->getTestimonials();
		$this->data['testimonials'] = $testimonials;

		$this->load->model('catalog/manufacturer');

		$this->data['text_selectmodel'] = $this->language->get("text_selectmodel");

		$this->data['carriers'] = $this->model_catalog_manufacturer->getManufacturers();
				
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/home.tpl';
		} else {
			$this->template = 'default/template/common/home.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			// 'common/content_bottom',
			'common/footer',
			'common/header'
		);
										
		$this->response->setOutput($this->render());
	}
}
?>
