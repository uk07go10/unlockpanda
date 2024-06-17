<?php

/**
 * @property ModelCheckoutOrder model_checkout_order
 * @property ModelCheckoutReprocess model_checkout_reprocess
 */
class ControllerInformationOrderstatus extends Controller {
    public function index() {

        $this->language->load('information/orderstatus');
        $this->document->setTitle($this->language->get('text_title'));

        $this->data['heading_title'] = $this->language->get('text_title');

        $this->data['button_continue'] = $this->language->get('text_continue');
        $this->data['text_insert'] = $this->language->get('text_insert');
        $this->data['text_note'] = $this->language->get('text_note');

        $this->data['flash'] = $this->_getFlash();

        $this->data['action'] = $this->url->link('information/orderstatus/checkOrderStatus', '', 'SSL');

        $this->data['continue'] = $this->url->link('common/home');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/order_status.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/information/order_status.tpl';
        } else {
            $this->template = 'default/template/information/order_status.tpl';
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

    public function reprocess() {
        $this->load->model("checkout/reprocess");
        $this->language->load("information/orderstatus");

        $id = isset($this->request->get["id"]) ? $this->request->get["id"] : false;
        $order_product_id = isset($this->request->get["opid"]) ? $this->request->get["opid"] : false;
        $key = isset($this->request->get["key"]) ? $this->request->get["key"] : false;
        $decision = isset($this->request->get["decision"]) ? $this->request->get["decision"] == "true" : false;

        if($id === false || !$order_product_id || !$key) {
            // todo: get from lang
            $this->_setFlash($this->language->get("text_invalid_request"), "warning");
        } else {
            try {
                $this->model_checkout_reprocess->setReprocessDecision($id, $order_product_id, $key, $decision);
                // todo: get from lang
                $this->_setFlash(sprintf($this->language->get("text_operation_successful"), $decision ? "CONFIRM" : "DECLINE"));

            } catch (NoReprocessPendingException $e) {
                // todo: get from lang
                $this->_setFlash($this->language->get("text_no_such_reprocess_decision"), "warning");

            } catch (AlreadyDecidedException $e) {
                // todo: get from lang
                $this->_setFlash($this->language->get("text_already_decided"), "warning");
            }
        }

        return $this->redirect($this->url->link("information/orderstatus"));

    }

    public function checkOrderStatus(){
        $order_id = $this->request->post['order_id'];
        $this->load->model('checkout/order');

        $status = $this->model_checkout_order->getOrderStatus($order_id, true);
        if(is_array($status)) {
            // hide the status name from the client

            $name = $status["name"];
            switch($status["name"]) {
                case "Fraud Detected": {
                    $name = "Pending";
                    break;
                }

                case "Pending Approval": {
                    $name = "Pending";
                    break;
                }

                case "Pending Approval (Unpaid)": {
                    $name = "Pending Payment";
                    break;
                }

                case "Pending Refund": {
                    $name = "Pending";
                    break;
                }

                case "InstantUnlock": {
                    $name = "Processing";
                    break;
                }
            }

            $response = array(
                "name" => $name,
                "order_id" => $status["order_id"],
                "comment" => preg_replace_callback("/[0-9]{15,17}/", function($matches) {
                    return substr($matches[0], 0, 3) . str_repeat("X", 12);
                }, $status["comment"])
            );
        } else {
            $response = $status;
        }

        $this->response->setOutput(json_encode($response));
    }
}
?>