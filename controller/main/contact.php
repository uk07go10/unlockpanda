<?php

/**
 * @property ModelCatalogInformation model_catalog_information
 */
class ControllerMainContact extends Controller {
    public function index() {
        
        $this->document->addScript("/catalog/view/theme/web/js/contact.js");
        
        $this->language->load("information/contact");
        $this->data = array_merge($this->data, $this->language->getData());
        
        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->template = 'web/template/main/contact.tpl';
        $this->children = array(
            'main/navigation',
            'main/scripts',
            'main/footer'
        );
        
        $this->response->setOutput($this->render());
    }
}