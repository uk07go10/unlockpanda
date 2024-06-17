<?php   
class ControllerMainScripts extends Controller {
	protected function index() {
        if(!$this->document->getTitle()) {
            $this->document->setTitle($this->language->get('heading_title'));
        }
        $this->document->setDescription($this->language->get('heading_description'));

        $this->data['title'] = $this->document->getTitle();
        $this->data['description'] = $this->document->getDescription();

        // JS
        $this->data["language"] = $this->session->data["language"];
        $this->data['config_less_models'] = $this->config->get('config_less_models');
        $this->data['config_models_notice'] = $this->config->get('config_models_notice');

        $this->template = 'web/template/main/scripts.tpl';
    	$this->render();
	}
}
?>