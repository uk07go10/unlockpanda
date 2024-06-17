<?php

/**
 * @property ModelCatalogManufacturer model_catalog_manufacturer
 * @property ModelToolZendesk model_tool_zendesk
 */
class ControllerMainHome extends Controller
{
    public function index()
    {

        $this->document->addScript("/catalog/view/theme/web/js/accordion.js");
//print_r($this->request);die;
        // faq
        $this->load->model('catalog/faq');
        $this->load->model('catalog/faqcategory');
if (isset($this->request->post['language_code'])) {
            $this->session->data['language'] = $this->request->post['language_code'];
        }
        $language = $this->session->data['language'];
        $this->data['language'] = $language;
//print_r($language);die;
        $this->data['text_empty'] = $this->language->get('text_empty');
        $this->data['text_faq'] = $this->language->get('text_faq');

        $this->data['button_continue'] = $this->language->get('button_continue');

        $this->data['faqs'] = array();

        $data = array(
            'sort' => 'fq.sort_order',
            'order' => 'ASC',
            'start' => 0,
            'limit' => $this->config->get('config_catalog_limit')
        );

        $results = $this->model_catalog_faq->getFaqs($data);

        foreach ($results as $result) {

            $this->data['faqs'][] = array(
                'faq_id' => $result['faq_id'],
                'title' => $result['title'],
                'description' => strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))
            );
        }

       // faq end
$this->load->model('localisation/language');

$this->data['languages'] = $this->model_localisation_language->getLanguages();
$this->data['action'] = $this->url->link('common/language/language', '', true);
$this->data['redirect'] = $this->url->link($this->request->get['route'], '', true);
$this->data['text_language'] = $this->language->get('text_language');

        $this->language->load("main/home");
        $this->data = array_merge($this->data, $this->language->getData());

        $this->template = 'web/template/main/home.tpl';
        $this->children = array(
            'main/navigation',
            'main/header_form',
            'main/scripts',
            'main/footer'
        );


        $this->response->setOutput($this->render());
    }

    public function contact()
    {
        $this->response->addHeader('Content-Type: application/json');

        $this->load->model('catalog/manufacturer');
        $this->load->model('tool/zendesk');

        $name = isset($this->request->post["name"]) ? $this->request->post["name"] : false;
        $email = isset($this->request->post["email"]) ? $this->request->post["email"] : false;
        $message = isset($this->request->post["message"]) ? $this->request->post["message"] : false;

        $this->model_catalog_manufacturer->sendMail(
            $this->config->get('config_support_email'),
            "New message from footer form",
            sprintf("Client name: %s\r\nClient mail: %s\r\n\r\n", $name, $email) . $message
        );

        $this->model_tool_zendesk->setCredentials(
            getenv("ZENDESK_DOMAIN"),
            getenv("ZENDESK_EMAIL"),
            getenv("ZENDESK_PASSWORD")
        );

        $this->model_tool_zendesk->createTicket($name, $email, "New message from ${name}", $message);

        $this->response->setOutput(json_encode(array("result" => true)));
    }
}
