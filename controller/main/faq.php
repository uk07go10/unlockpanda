<?php

/**
 * @property ModelCatalogFaq model_catalog_faq
 */
class ControllerMainFaq extends Controller {
    
    public function index() {

        $this->document->addScript("/catalog/view/theme/web/js/accordion.js");
        $this->document->addScript('https://cdn.jsdelivr.net/npm/jquery-collapse@1.1.2/src/jquery.collapse.js');
        $this->document->addScript('https://cdn.jsdelivr.net/npm/jquery-collapse@1.1.2/src/jquery.collapse_storage.js');
        $this->document->addScript('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.js');
        $this->document->addScript('https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.21/moment-timezone-with-data.min.js');


        $this->language->load("information/faq");
        $this->document->setTitle($this->language->get("heading_title"));
        $this->data = array_merge($this->data, $this->language->getData());

        $this->load->model('catalog/faq');
        $this->load->model('catalog/faqcategory');
        
        $language = $this->session->data['language'];
        $this->data['language'] = $language;

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'fq.sort_order';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['description'] = '';

        $this->data['text_empty'] = $this->language->get('text_empty');
        $this->data['text_faq'] = $this->language->get('text_faq');

        $this->data['button_continue'] = $this->language->get('button_continue');

        $this->data['faqs'] = array();

        $data = array(
            'sort'                   => $sort,
            'order'                  => $order,
            'start'                  => ($page - 1) * $this->config->get('config_catalog_limit'),
            'limit'                  => $this->config->get('config_catalog_limit')
        );

        $results = $this->model_catalog_faq->getFaqs($data);

        foreach ($results as $result) {

            $this->data['faqs'][] = array(
                'faq_id'  => $result['faq_id'],
                'title'       => $result['title'],
                'description' => strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))
            );
        }


        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }
        
        $this->template = 'web/template/main/faq.tpl';
        $this->children = array(
            'main/navigation',
            'main/scripts',
            'main/footer'
        );
        
        $this->response->setOutput($this->render());
    }
}