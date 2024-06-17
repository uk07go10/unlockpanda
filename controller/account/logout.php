<?php 
class ControllerAccountLogout extends Controller {
	public function index() {
                if ($this->customer->isLogged()) {
                        $this->customer->logout();
                                $this->cart->clear();

                                unset($this->session->data['wishlist']);
                                unset($this->session->data['shipping_address_id']);
                                unset($this->session->data['shipping_method']);
                                unset($this->session->data['shipping_methods']);
                                unset($this->session->data['payment_address_id']);
                                unset($this->session->data['payment_method']);
                                unset($this->session->data['payment_methods']);
                                unset($this->session->data['comment']);
                                unset($this->session->data['order_id']);
                                unset($this->session->data['coupon']);
                                unset($this->session->data['reward']);			
                                unset($this->session->data['voucher']);
                                unset($this->session->data['vouchers']);

                        $this->redirect($this->url->link('common/home', '', 'SSL'));
                }
                $this->redirect($this->url->link('common/home', '', 'SSL'));
    	}
}
?>