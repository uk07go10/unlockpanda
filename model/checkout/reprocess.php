<?php

class AlreadyDecidedException extends Exception {

}

class NoReprocessPendingException extends Exception {

}


/**
 * @property Loader load
 * @property ModelToolAPIFactory model_tool_api_factory
 * @property ModelSaleOrder model_sale_order
 * @property Config config
 * @property ModelCatalogProduct model_catalog_product
 * @property Url frontend_url
 * @property DB db
 * @property Url backend_url
 */
class ModelCheckoutReprocess extends Model {

    public function setReprocessDecision($id, $order_product_id, $key, $decision) {

        $url = $this->backend_url->link(
            "common/automation/reprocess",
            sprintf("id=%s&opid=%s&key=%s&decision=%s", $id, $order_product_id, $key, $decision ? "true" : "false"),
            "SSL");

        $url = str_replace("&amp;", "&", $url);

        $response = json_decode(file_get_contents($url), true);
        if($response["result"]) {
            return true;
        }

        if ($response["reason"] == "ALREADY_DECIDED") {
            throw new AlreadyDecidedException();
        }

        throw new NoReprocessPendingException();
    }

}