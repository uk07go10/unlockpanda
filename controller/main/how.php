<?php

class ControllerMainHow extends Controller {
    public function index() {
        $this->template = 'web/template/main/how.tpl';
        $this->children = array(
            'main/navigation',
            'main/scripts',
            'main/footer'
        );

        $this->response->setOutput($this->render());
    }
}
?>