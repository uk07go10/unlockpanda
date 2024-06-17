<?php

class ControllerMainNotFound extends Controller
{
    public function index()
    {
        $this->language->load("main/home");
        $this->data = array_merge($this->data, $this->language->getData());

        $this->template = 'ur/template/main/not_found.tpl';


        $this->children = array(
            'main/header',
            'main/scripts',
            'main/footer'
        );

        $this->response->setOutput($this->render());
    }
}