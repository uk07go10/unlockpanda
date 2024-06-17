<?php

/**
 * @property ModelCatalogProduct model_catalog_product
 * @property ModelCheckoutCoupon model_checkout_coupon
 * @property ModelCheckoutOrder model_checkout_order
 * @property Cart cart
 */
class ControllerCheckoutCode extends Controller {

    private $_max_tries = 20;
    private $_cache_key = "redeemtries:%s";

    public function index() {
        return $this->redirect($this->url->link("checkout/cart", "", "SSL"));
    }

    public function redeem() {
        $log = new Log("cart_notifications.txt");

        $this->load->language("checkout/code");

        $this->load->model("checkout/coupon");
        $this->load->model("checkout/order");
        $this->load->library("encryption");

        $ip = $this->request->server["REMOTE_ADDR"];
        $code = isset($this->request->get["code"]) ? $this->request->get["code"] : false;
        $restore_state = isset($this->request->get["restore_state"]) ? $this->request->get["restore_state"] : false;

        $this->notifier->add(
            (new Notification())
                ->setError("AbandonedCartRedeem", $code)
        )->notify();

        $request_key = sprintf($this->_cache_key, $ip);
        $tries_number = $this->cache->get($request_key);

        if($tries_number > $this->_max_tries) {
            $log->write(sprintf("%s: exceeded max retries", $ip));
            return $this->redirect($this->url->link("main/checkout", "", "SSL"));
        }

        $coupon_data = $this->model_checkout_coupon->getCoupon($code);
        $log->write(sprintf("Coupon %s, data: %s", $code, json_encode($coupon_data)));

        if(!$coupon_data && $code !== false) {
            $log->write(sprintf("%s: failed try number %s", $ip, (int)$tries_number));
            $this->cache->set($request_key, $tries_number + 1);
            $this->_setFlash($this->language->get("flash_invalid"), "attention");
            return $this->redirect($this->url->link("main/checkout", "", "SSL"));
        }

        $order_id = 0;
        if($restore_state) {
            $encryption = new Encryption($this->config->get("config_encryption"));
            $order_id_temp = $encryption->decrypt($restore_state);
            if(is_numeric($order_id_temp)) {
                $order_id = $order_id_temp;
            }

            $log->write(sprintf("Restoring %s", $order_id));
        }

        $log->write(sprintf("Incoming redeem: %s, order: %s, code: %s", $ip, $order_id, $code));
        $this->load->model('checkout/order');
        if($order_id) {
            $order_data = $this->model_checkout_order->getOrder($order_id);
            $order_products = $this->model_checkout_order->getOrderProducts($order_id);
            $this->session->data['email'] = $order_data['email'];
            if (!$order_data['email']) {
                return $this->redirect($this->url->link("main/checkout", "", "SSL"));
            }
            foreach($order_products as $order_product) {
                if ($this->model_checkout_order->validate($order_product["product_id"], $order_product["carrier_id"], true)) {
                    $this->cart->add(
                        $order_product["product_id"],
                        $order_product["carrier_id"],
                        $order_product["category"],
                        $order_product["imei"]
                    );
                }
            }
        }


        $log->write(sprintf("%s: coupon applied", $ip));
        if(!$this->cart->getProducts()) {
            $log->write(".. but no products in the cart :(");
        }

        if ($code) {
            $this->session->data["coupon"] = $code;
            $this->_setFlash($this->language->get("flash_applied"));
        }

        return $this->redirect($this->url->link("main/checkout", "utm_source=discount_email&utm_medium=email&utm_campaign=abandoned_cart", "SSL"));
    }
}

?>