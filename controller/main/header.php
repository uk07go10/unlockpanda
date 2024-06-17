<?php

/**
 * @property ModelCatalogManufacturer model_catalog_manufacturer
 */
class ControllerMainHeader extends Controller
{
    protected function index()
    {

        $this->load->model('catalog/manufacturer');
        $this->data['carriers'] = $this->model_catalog_manufacturer->getManufacturers();
        
        $this->language->load("main/home");
        $this->data = array_merge($this->data, $this->language->getData());

        $this->load->model('tool/image');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');

        $this->data['products'] = array();
        $products = $this->cart->getProducts();
        $this->data["products_count"] = count($products);
        
        foreach($products as $product) {

            if ($product['image']) {
                $image = $this->model_tool_image->resize($product['image'], 100, 100);
            } else {
                $image = '';
            }

            $carrier = $this->model_catalog_manufacturer->getManufacturer($product['carrier']);
            $category = $this->model_catalog_product->getCategories($product['product_id']);
            $category_info = $this->model_catalog_category->getCategory($category['category_id']);
            $delivery_time = html_entity_decode($product['delivery_time'], ENT_QUOTES, 'UTF-8');

            $price = $this->currency->format($product['price']);
            $total = $this->currency->format($product['total']);
            

            $this->data['products'][] = array(
                'key' => $product['key'],
                'product_id' => $product['product_id'],
                'thumb' => $image,
                'name' => $product['name'],
                'carrier' => $carrier['name'],
                'imei' => $product['imei'],
                'category' => $category_info['name'],
                'price' => $price,
                'total' => $total,
                'delivery_time' => $delivery_time
                );
        }

        $total_data = array();
        $total = $this->cart->getSubTotal();
        $taxes = $this->cart->getTaxes();

        $currency = "USD";
        $this->data['currency'] = $currency;
        $this->load->model('total/coupon');
        $this->model_total_coupon->getTotal($total_data, $total, $taxes);
        $amount = $this->currency->format($total, $currency, false, false);
        $this->data['amount'] = $amount;

        $this->template = 'ur/template/main/header.tpl';
        $this->render();
    }

}
?>