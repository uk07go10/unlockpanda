<?php

/**
 * @property ModelCatalogProduct model_catalog_product
 * @property ModelCheckoutOrder model_checkout_order
 * @property ModelCatalogManufacturer model_catalog_manufacturer
 */
class ControllerMainAjax extends Controller
{
    public function carriers() {
        $product_id = isset($this->request->get['product_id']) ? $this->request->get['product_id'] : false;
        $this->load->model('catalog/product');
        $this->load->model('catalog/manufacturer');

        if ($product_id) {
            $carriers = $this->model_catalog_product->getCarriersByProduct($product_id);
        } else {
            $carriers = $this->model_catalog_manufacturer->getManufacturers();
        }
        
        $results = array();
        array_push($results, array(
            "value" => "-1",
            "text" => $this->session->data["language"] == "en" ? "Please select.." : "Por favor selecciona.."
        ));
        
        foreach($carriers as $carrier) {
            array_push($results, array(
                "value" => $carrier["manufacturer_id"],
                "text" => html_entity_decode($carrier["name"])
            ));
        }

        $this->response->addHeader("Content-Type: application/json");
        $this->response->setOutput(json_encode($results));
    }

    public function brands()
    {
        $carrier_id = $this->request->get['carrier_id'];
        $this->load->model('catalog/product');
        $brands = $this->model_catalog_product->getBrandsByCarrier($carrier_id);


        $results = array();
        array_push($results, array(
            "value" => "-1",
            "text" => $this->session->data["language"] == "en" ? "Select Manufacturer" : "Por favor selecciona"
        ));

        foreach ($brands as $brand) {
            array_push($results, array(
                "value" => $brand["category_id"],
                "text" => htmlspecialchars_decode($brand["name"])
            ));
        }

        $this->response->addHeader("Content-Type: application/json");
        $this->response->setOutput(json_encode($results));
    }


    public function products()
    {
        $category_id = $this->request->get['category_id'];
        $this->load->model('catalog/product');
        $data['filter_category_id'] = $category_id;
        $data['skip_description'] = true;
        if (isset($this->request->get['carrier_id'])) {
            $data['filter_carrier_id'] = $this->request->get['carrier_id'];
        }
        $data["sort"] = "pd.name";
        $data["order"] = "ASC";

        $products = $this->model_catalog_product->getProducts($data, true);
        

        $results = array();
        array_push($results, array(
            "value" => "-1",
            "text" => $this->session->data["language"] == "en" ? "Select Model" : "Selecciona modelo"
        ));

        foreach ($products as $product) {
            array_push($results, array(
                "value" => $product["product_id"],
                "text" => htmlspecialchars_decode($product["name"])
            ));
        }

        $this->response->addHeader("Content-Type: application/json");
        $this->response->setOutput(json_encode($results));
    }

    public function product()
    {
        $product_id = $this->request->get['product_id'];
        $this->load->model('catalog/product');

        $product = $this->model_catalog_product->getProduct($product_id);
        if($product !== false && array_key_exists("delivery_time", $product)) {
            $product["delivery_time"] = $this->model_catalog_product->convertDeliveryTime(
                $product["delivery_time"], $this->session->data["language"]
            );
        }

        $this->response->addHeader("Content-Type: application/json");
        $this->response->setOutput(json_encode($product));
    }

    public function phoneNumberAdvised() {
        $carrier_id = (int) (isset($this->request->get['carrier_id']) ? $this->request->get['carrier_id'] : 0); // carrier
        $category_id = (int) (isset($this->request->get['category_id']) ? $this->request->get['category_id'] : 0); // brand

        $this->load->model('checkout/order');

        $response = array(
            'advise' => true // $this->model_checkout_order->phoneNumberAdvised($carrier_id, $category_id)
        );

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($response));

    }
    
    public function language() {
        $allowedLanguages = array("en", "es");
        
        $language = isset($this->request->get['language']) ? $this->request->get['language'] : "en";
        if(in_array($language, $allowedLanguages)) {
            $this->session->data['language'] = $this->request->get['language'];
        }
    }

    public function status() {
        $order_id = isset($this->request->get['order_id']) ? $this->request->get['order_id'] : 1;
        $email = isset($this->request->get['order_email']) ? trim($this->request->get['order_email']) : '';
        $this->load->model('checkout/order');


        $status = $this->model_checkout_order->getOrderStatus($order_id, true);
        if(is_array($status)) {
            if ($status['email'] === $email) {
                // hide the status name from the client

                $name = $status["name"];
                $comment = $status["comment"];

                switch($status["name"]) {
                    case "Pending Refund":
                    case "Fraud Detected": {
                        $name = "Pending";
                        break;
                    }

                    case "Pending Approval": {
                        $name = "Pending";
                        $comment = "";
                        break;
                    }

                    case "Pending Approval (Unpaid)": {
                        $name = "Pending Payment";
                        break;
                    }

                    case "Reprocessing": {
                        $name = "Processing";
                        break;
                    }

                    case "InstantUnlock": {
                        $name = "Processing";
                        break;
                    }
                }

                $response = array(
                    "error" => false,
                    "name" => $name,
                    "order_id" => $status["order_id"],
                    "comment" => preg_replace_callback("/[0-9]{15,17}/", function($matches) {
                        return substr($matches[0], 0, 3) . str_repeat("X", 12);
                    }, $comment)
                );
            } else {
                $response = array(
                    "error" => true,
                    "message" => "The email didn't match the order's email."
                );
            }

        } else {
            $response = array(
                "error" => true,
                "message" => $status
            );
        }

        $this->response->addHeader("Content-Type: application/json");
        $this->response->setOutput(json_encode($response));
    }
}

?>