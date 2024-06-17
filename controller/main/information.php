<?php

/**
 * @property ModelCatalogInformation model_catalog_information
 */
class ControllerMainInformation extends Controller {
    public function index() {
        
//        $this->document->addScript("/catalog/view/javascript/jquery/jquery.scombobox.js");
//        $this->document->addScript("/catalog/view/theme/ur/js/home.js");
//        
//        $this->document->addStyle("/catalog/view/theme/ur/css/jquery.scombobox.css");

        $this->language->load("main/home");
        $this->data = array_merge($this->data, $this->language->getData());

        $this->load->model('catalog/information');
        
        if (isset($this->request->get['information_id'])) {
            $information_id = $this->request->get['information_id'];
        } else {
            $information_id = 0;
        }

        $information_info = $this->model_catalog_information->getInformation($information_id);
        
        if ($information_info) {
            $this->document->setTitle($information_info['title']);


            $this->data['heading_title'] = $information_info['title'];

            $this->data['button_continue'] = $this->language->get('button_continue');

            $this->data['description'] = html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8');

            $this->template = 'web/template/main/information.tpl';
        } else {
            $this->template = 'ur/template/main/not_found.tpl';
        }

        $this->children = array(
            'main/navigation',
            'main/scripts',
            'main/footer'
        );
        
        $this->response->setOutput($this->render());
    }
}