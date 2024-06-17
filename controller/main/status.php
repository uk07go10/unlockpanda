<?php

/**
 * @property ModelCatalogInformation model_catalog_information
 */
class ControllerMainStatus extends Controller {
    public function index() {

        $this->language->load('information/orderstatus');
        $this->document->setTitle($this->language->get("text_title"));

        $this->data = array_merge($this->data, $this->language->getData());
        
        $this->document->addScript("/catalog/view/theme/web/js/status.js");
        $this->document->addStyle("/catalog/view/theme/web/css/pages/status.css");

        $this->data['heading_title'] = $this->language->get('text_title');
        $this->data['button_continue'] = $this->language->get('text_continue');
        $this->data['text_insert'] = $this->language->get('text_insert');
        $this->data['text_note'] = $this->language->get('text_note');

        $this->data['flash'] = $this->_getFlash();
        
        $this->template = 'web/template/main/status.tpl';
        $this->children = array(
            'main/navigation',
            'main/scripts',
            'main/footer'
        );
        
        $this->response->setOutput($this->render());
    }
}