<?php

/**
 * @property ModelCatalogProduct model_catalog_product
 */
class ControllerMainFooter extends Controller {
	protected function index() {
	    
	    // $this->document->addScript("/catalog/view/theme/ur/js/footer.js?v=3");

        $this->data['scripts'] = $this->document->getScripts();
        $this->data['styles'] = $this->document->getStyles();

        $this->language->load("main/home");
        $this->data = array_merge($this->data, $this->language->getData());
        
        $this->load->model('catalog/product');
        
        $this->data['most_popular'] = $this->model_catalog_product->getBestSellerProductsLinks(7);

        $this->template = 'web/template/main/footer.tpl';
		$this->render();
	}
}
?>