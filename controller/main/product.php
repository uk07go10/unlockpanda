<?php

/**
 * @property ModelCatalogCategory model_catalog_category
 * @property ModelCatalogProduct model_catalog_product
 * @property ModelCatalogManufacturer model_catalog_manufacturer
 */
class ControllerMainProduct extends Controller
{
    private $error = array();

    public function index()
    {

        $this->document->addScript("/catalog/view/javascript/mailcheck.js");
        $this->document->addScript("/catalog/view/javascript/jquery/jquery.scombobox.js");
        $this->document->addScript("/catalog/view/theme/ur/js/product.js");

        $this->document->addStyle("/catalog/view/theme/ur/css/jquery.scombobox.css");
        
        $this->language->load('product/product');
        
        $this->load->model('catalog/category');
        $this->load->model('catalog/product');

        $this->language->load("main/checkout");
        $this->language->load("main/home");
        $this->data = array_merge($this->data, $this->language->getData());

        if (isset($this->request->get['path'])) {
            $path = $this->request->get['path'];
            $category_info = $this->model_catalog_category->getCategory($path);

        } else {
            $category_info = $this->model_catalog_product->getCategories($this->request->get['product_id']);

        }

        $this->data['category_info'] = $category_info;

        $this->load->model('catalog/manufacturer');

        $manufacturers = $this->model_catalog_manufacturer->getManufacturers();
        $this->data['manufacturers'] = array();

        foreach ($manufacturers as $key => $manufacturer) {
            $this->data['manufacturers'][] = array(
                'manufacturer_id' => $manufacturer['manufacturer_id'],
                'name' => $manufacturer['name']
            );
        }

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_tag'])) {
            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . $this->request->get['filter_name'];
            }

            if (isset($this->request->get['filter_tag'])) {
                $url .= '&filter_tag=' . $this->request->get['filter_tag'];
            }

            if (isset($this->request->get['filter_description'])) {
                $url .= '&filter_description=' . $this->request->get['filter_description'];
            }

            if (isset($this->request->get['filter_category_id'])) {
                $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
            }
        }

        if (isset($this->request->get['product_id'])) {
            $product_id = $this->request->get['product_id'];
        } else {
            $product_id = 0;
        }
        
        $this->data['product_id'] = $product_id;
        $this->data['brand_id'] = $this->model_catalog_product->getCategories($product_id)["category_id"];

        $product_info = $this->model_catalog_product->getProduct($product_id);

        $this->data['product_info'] = $product_info;

        if ($product_info) {
            $url = '';

            if (isset($this->request->get['path'])) {
                $url .= '&path=' . $this->request->get['path'];
            }

            if (isset($this->request->get['manufacturer_id'])) {
                $url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
            }

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . $this->request->get['filter_name'];
            }

            if (isset($this->request->get['filter_tag'])) {
                $url .= '&filter_tag=' . $this->request->get['filter_tag'];
            }

            if (isset($this->request->get['filter_description'])) {
                $url .= '&filter_description=' . $this->request->get['filter_description'];
            }

            if (isset($this->request->get['filter_category_id'])) {
                $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
            }

            $this->document->setTitle($product_info['name'] . " Unlock");
            $this->document->setDescription($product_info['meta_description']);
            $this->document->setKeywords($product_info['meta_keyword']);
            $this->document->addLink($this->url->link('product/product', 'product_id=' . $this->request->get['product_id']), 'canonical');

            $this->data['heading_title'] = $product_info['name'];
            $this->data['delivery_time'] = $product_info['delivery_time'];

            $this->data['text_select'] = $this->language->get('text_select');
            $this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
            $this->data['text_model'] = $this->language->get('text_model');
            $this->data['text_reward'] = $this->language->get('text_reward');
            $this->data['text_points'] = $this->language->get('text_points');
            $this->data['text_discount'] = $this->language->get('text_discount');
            $this->data['text_stock'] = $this->language->get('text_stock');
            $this->data['text_price'] = $this->language->get('text_price');
            $this->data['text_tax'] = $this->language->get('text_tax');
            $this->data['text_discount'] = $this->language->get('text_discount');
            $this->data['text_option'] = $this->language->get('text_option');
            $this->data['text_qty'] = $this->language->get('text_qty');
            $this->data['text_or'] = $this->language->get('text_or');
            $this->data['text_write'] = $this->language->get('text_write');
            $this->data['text_note'] = $this->language->get('text_note');
            $this->data['text_share'] = $this->language->get('text_share');
            $this->data['text_wait'] = $this->language->get('text_wait');
            $this->data['text_tags'] = $this->language->get('text_tags');

            $this->data['entry_name'] = $this->language->get('entry_name');
            $this->data['entry_review'] = $this->language->get('entry_review');
            $this->data['entry_rating'] = $this->language->get('entry_rating');
            $this->data['entry_good'] = $this->language->get('entry_good');
            $this->data['entry_bad'] = $this->language->get('entry_bad');
            $this->data['entry_captcha'] = $this->language->get('entry_captcha');

            $this->data['button_cart'] = $this->language->get('button_cart');
            $this->data['button_wishlist'] = $this->language->get('button_wishlist');
            $this->data['button_compare'] = $this->language->get('button_compare');
            $this->data['button_upload'] = $this->language->get('button_upload');
            $this->data['button_continue'] = $this->language->get('button_continue');

            $this->load->model('catalog/review');

            $this->data['tab_description'] = $this->language->get('tab_description');
            $this->data['tab_attribute'] = $this->language->get('tab_attribute');
            $this->data['tab_related'] = $this->language->get('tab_related');

            $this->data['product_id'] = $this->request->get['product_id'];

            if ($product_info['quantity'] <= 0) {
                $this->data['stock'] = $product_info['stock_status'];
            } elseif ($this->config->get('config_stock_display')) {
                $this->data['stock'] = $product_info['quantity'];
            } else {
                $this->data['stock'] = $this->language->get('text_instock');
            }

            $this->load->model('tool/image');

            if ($product_info['image']) {
                $this->data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
            } else {
                $this->data['popup'] = '';
            }

            if ($product_info['image']) {
                $this->data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
            } else {
                $this->data['thumb'] = '';
            }
            
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $this->data['price'] = $this->currency->format($product_info['price'], 'USD', false, false);
            } else {
                $this->data['price'] = false;
            }

            $this->data['competitors_price'] = $this->currency->format($this->data['price'] + 10.0);
            $this->data['save'] = $this->currency->format(10.0);

            if ((float)$product_info['special']) {
                $this->data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $this->data['special'] = false;
            }

            if ($this->config->get('config_tax')) {
                $this->data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
            } else {
                $this->data['tax'] = false;
            }

            $discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);

            $this->data['discounts'] = array();

            foreach ($discounts as $discount) {
                $this->data['discounts'][] = array(
                    'quantity' => $discount['quantity'],
                    'price' => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')))
                );
            }
            
            $this->data['minimum'] = 1;


            $this->data['review_status'] = $this->config->get('config_review_status');
            $this->data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']);
            $this->data['rating'] = (int)$product_info['rating'];

            $description = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
            $dom = new DOMDocument();
            $dom->loadHTML('<?xml encoding="utf-8" ?>' . $description);
            $xpath = new DOMXPath($dom);
            $nodes = $xpath->query("//*[@style]");
            foreach($nodes as $node) {
                $node->removeAttribute("style");
            }

            $description = $dom->saveHTML();
            
            $this->data['description'] = $description;
            

            $this->model_catalog_product->updateViewed($this->request->get['product_id']);
            
            $this->template = 'ur/template/main/product.tpl';

        } else {
            $this->template = 'ur/template/main/not_found.tpl';
        }

        $this->children = array(
            'main/header',
            'main/scripts',
            'main/footer'
        );

        $this->response->setOutput($this->render());
    }
}

