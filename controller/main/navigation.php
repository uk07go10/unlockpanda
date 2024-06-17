<?php

class ControllerMainNavigation extends Controller {
    protected function index() {
$this->load->model('localisation/language');
if (isset($this->request->post['language_code'])) {
            $this->session->data['language'] = $this->request->post['language_code'];
           $redirect = $this->request->server['REQUEST_URI'];
           $this->response->redirect($redirect);
        }
        // Get all languages
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

        // Set template path
        $this->template = 'web/template/main/navigation.tpl';

        // Add custom JavaScript for navigation
        $this->document->addScript("/catalog/view/theme/web/js/navigation.js");

        // Render the output
        $this->response->setOutput($this->render());
    }
}
?>
