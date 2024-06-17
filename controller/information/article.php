<?php
class ControllerInformationArticle extends Controller {
    public function index() {

        # $this->language->load('information/article');
        $this->document->setTitle($this->language->get('text_title'));

        $this->data['heading_title'] = $this->language->get('text_title');

        $this->data['button_continue'] = $this->language->get('text_continue');
        $this->data['text_insert'] = $this->language->get('text_insert');
        $this->data['text_note'] = $this->language->get('text_note');

        var_dump($this->request->get['art']);

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/article.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/information/article.tpl';
        } else {
            $this->template = 'default/template/information/article.tpl';
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