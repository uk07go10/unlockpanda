<?php

/**
 * @property Loader load
 * @property ModelCheckoutOrder $model_checkout_order
 * @property RedisCache cache
 */
class ModelFraudFraud extends Model {

    static $CHECK_OUTCOME_PASS = 1;
    static $CHECK_OUTCOME_HOLD = 2;
    static $CHECK_OUTCOME_REJECT = 3;

    private $_config = array(
        "mail_notify" => "pawel@devsti.me",
        "debug" => true,
        "debug_file" => "fraud.txt",
        "classifier_url" => "http://91.121.83.40:2522/predict/%s"
    );

    private $_logger;

    public function __construct($registry) {
        parent::__construct($registry);
        if($this->_config["debug"]) {
            $this->_logger = new Log($this->_config["debug_file"]);
        }
        $this->load->model("checkout/order");
    }

    private function _log($content) {
        if($this->_config["debug"]) {
            $this->_logger->write($content);
        }
    }

    private function _mail($subject, $content) {
        if($this->_config["mail_notify"]) {
            $this->sendMail(
                $this->_config["mail_notify"],
                $subject,
                $content
            );
        }
    }

    public function addFromOrder($order_id) {
        $this->addCardFromOrder($order_id);
        $this->addIdentityFromOrder($order_id);
    }

    public function addFailedFromOrder($order_id, $card_data=array()) {
        $order = $this->model_checkout_order->getOrder($order_id);

        $order_query = $this->db->query("
                INSERT INTO " . DB_DATABASE_FRAUDS . "." . DB_PREFIX . "fraud_failed
                SET date_created = NOW(),
                date_modified = NOW(),
                fingerprint = '" . $order["fingerprint"] . "',
                uuid = '" . $order["uuid"] . "',
                first_name = '" . $order["firstname"] . "',
                last_name = '" . $order["lastname"] . "',
                email = '" . $order["email"] . "',
                ip = '" . $order["ip"] . "',
                order_id = '" . $order["order_id"] . "',
                card_last_four = " . (!empty($card_data) ? "'" . $card_data["last4"] . "'" : "NULL") . ",
                card_raw = " . (!empty($card_data) ? "'" . $this->db->escape(json_encode($card_data)) . "'" : "NULL") . "
            ");

        $this->_log("Added FAILED for order " . $order_id);
    }

    public function addCardFromOrder($order_id) {
        $order = $this->model_checkout_order->getOrder($order_id);
        if($order["txn_id"]) {

            try {
                $this->_addCardFromCharge($order["txn_id"]);

            } catch (Exception $e) {
                $this->_log("addCardFromOrder exception: " . $e->getMessage());
                $this->_mail(
                    "addCardToMail exception",
                    "Order ID: " . $order_id . ", " . $e->getMessage()
                );
            }
        } else {
            $this->_log("No card data for order ID " . $order_id);
        }
    }

    protected function _addCardFromCharge($charge_id) {
        $api_key = $this->config->get('stripe_production_secret_key');
        \Stripe\Stripe::setApiKey($api_key);

        $charge = \Stripe\Charge::retrieve($charge_id)->__toArray(true);
        $card = $charge["payment_method_details"]["card"];
        // todo: check if present
        $this->addCardFromStripeObject($card);
    }

    protected function _addCardFromToken($token_id) {
        $token_data = \Stripe\Token::retrieve($token_id);
        $card = $token_data["card"];
        $this->addCardFromStripeObject($card);
    }

    public function addCardFromStripeObject($card) {
        $card_query = $this->db->query("
                        INSERT INTO " . DB_DATABASE_FRAUDS . "." . DB_PREFIX . "fraud_card
                        SET date_created = NOW(),
                        date_modified = NOW(),
                        object_id = '',
                        fingerprint = '" . $card["fingerprint"] . "',
                        last_four = '" . $card["last4"] . "',
                        raw = '" . json_encode($card) . "'
                    ");
    }

    public function addIdentityFromOrder($order_id) {
        $order = $this->model_checkout_order->getOrder($order_id);

        $order_query = $this->db->query("
                INSERT INTO " . DB_DATABASE_FRAUDS . "." . DB_PREFIX . "fraud_identity
                SET date_created = NOW(),
                date_modified = NOW(),
                fingerprint = '" . $order["fingerprint"] . "',
                uuid = '" . $order["uuid"] . "',
                first_name = '" . $order["firstname"] . "',
                last_name = '" . $order["lastname"] . "',
                email = '" . $order["email"] . "',
                ip = '" . $order["ip"] . "'
            ");

        $this->_log("Added IDENTITY from order " . $order_id);
    }

    public function addIMEIFromOrder($order_id) {
        $order_products = $this->model_checkout_order->getOrderProducts($order_id);

        foreach($order_products as $order_product) {
            $imei_listed_query = $this->db->query("
                    SELECT id FROM " . DB_DATABASE_FRAUDS . "." . DB_PREFIX . "fraud_imei
                    WHERE imei = '" . $order_product['imei'] . "'
                ");

            if($imei_listed_query->num_rows) {
                continue;
            }

            $this->db->query("
                    INSERT INTO " . DB_DATABASE_FRAUDS . "." . DB_PREFIX . "fraud_imei
                    SET imei = '" . $order_product['imei'] . "'
                ");
        }

        $this->_log("Added IMEI from order " . $order_id);
    }

    public function addCardToOrder($order_id, $fingerprint, $last4, $card) {
        $this->db->query("
                INSERT INTO " . DB_PREFIX . "order_card
                SET fingerprint = '" . $this->db->escape($fingerprint) . "',
                last_four = '" . $this->db->escape($last4) . "',
                order_id = '" . $this->db->escape($order_id) . "',
                card_json = '" . $this->db->escape(json_encode($card)) . "'
            ");
    }

    public function checkOrder($order_id, $mail=true) {
        /**
         * Deprecated
         */

        if(!$mail) {
            $this->_config["mail_notify"] = false;
        }

        $result = array(
            "result" => ModelFraudFraud::$CHECK_OUTCOME_PASS,
            "reason" => array()
        );

        $order = $this->model_checkout_order->getOrder($order_id);
        $order_products = $this->model_checkout_order->getOrderProducts($order_id);

        // rule: check order value
        if($order["total"] > 150) {
            $result["result"] = ModelFraudFraud::$CHECK_OUTCOME_HOLD;
            $result["reason"][] = "Hold: high value of the order.";
        }

        // rule: check number of products in order
        if(count($order_products) >= 3) {
            $result["result"] = ModelFraudFraud::$CHECK_OUTCOME_HOLD;
            $result["reason"][] = "Hold: multiple items in one order";
        }

        // rule: check if has multiple successful orders already today
        $has_success_orders_today = $this->db->query("
                SELECT DISTINCT o1.order_id FROM `" . DB_PREFIX . "order` o1
                WHERE (uuid = '" . $order["uuid"] . "'
                OR email = '" . $order["email"] . "') 
                AND o1.order_id <> '" . $this->db->escape($order_id) . "'
                AND DATE(o1.date_added) = DATE(NOW())
                AND o1.order_status_id IN ('1', '2', '5', '17') # processing, pending, complete, pending echeck
            ");

        //  OR ip = '" . $order["ip"] . "'

        if($has_success_orders_today->num_rows > 2) {
            $result["result"] = ModelFraudFraud::$CHECK_OUTCOME_HOLD;
            $result["reason"][] = sprintf(
                "Hold: has multiple successful transactions today (%s).", array_reduce($has_success_orders_today->rows,
                function($carry, $item) {
                    if(!$carry) {
                        return $item["order_id"];
                    }
                    return $carry .= ", " . $item["order_id"];
                }
            ));
        }


        // rule: check if comes from a suspicious IP range
        if($this->checkIP($order["ip"], true) === "1") {
            $result["result"] = ModelFraudFraud::$CHECK_OUTCOME_REJECT;
            $result["reason"][] = "Reject: IP comes from a fraud IP range with 100% probability";
        }

        // rule: check if has more than x failed payments
        $failed_query = $this->db->query("
                SELECT DISTINCT card_last_four FROM " . DB_DATABASE_FRAUDS . "." . DB_PREFIX . "fraud_failed
                WHERE uuid = '" . $order["uuid"] . "'
                OR email = '" . $order["email"] . "'
            ");

        // OR ip = '" . $order["ip"] . "'

        if($failed_query->num_rows >= 3) {
            $result["result"] = ModelFraudFraud::$CHECK_OUTCOME_REJECT;
            $result["reason"][] = "Reject: client with this credentials has more than 2 failed payments with different cards.";
        }

        // rule: check if has a chargeback in history
        $identity_query = $this->db->query("
                SELECT * FROM " . DB_DATABASE_FRAUDS . "." . DB_PREFIX . "fraud_identity
                WHERE uuid = '" . $order["uuid"] . "'
                OR email = '" . $order["email"] . "'
            ");

        // OR ip = '" . $order["ip"] . "'

        if($identity_query->num_rows) {
            $result["result"] = ModelFraudFraud::$CHECK_OUTCOME_REJECT;
            $result["reason"][] = "Rejected: client with this credentials has a chargeback in history.";
        }

        // rule: IMEI is blacklisted
        $any_imei_blocked = false;
        foreach($order_products as $order_product) {
            $imei_query = $this->db->query("
                SELECT id FROM " . DB_DATABASE_FRAUDS . "." . DB_PREFIX . "fraud_imei
                WHERE imei = '" . $order_product['imei'] . "'
            ");

            if($imei_query->num_rows) {
                $any_imei_blocked = true;
            }
        }

        if($any_imei_blocked) {
            $result["result"] = ModelFraudFraud::$CHECK_OUTCOME_REJECT;
            $result["reason"][] = "Rejected: one of IMEIs in this order was blocked";
        }

        if($order["payment_method"] == "stripe") {
            $different_cards = false;

            $recent_related = $this->findRelated($order_id);
            foreach($recent_related as $item) {
                $old_order_id = $item["order_id"];
                if(!$this->similarOrderCards($order_id, $old_order_id)) {
                    $different_cards = true;
                    break;
                }
            }

            if($different_cards) {
                $result["result"] = ModelFraudFraud::$CHECK_OUTCOME_REJECT;
                $result["reason"][] = "Rejected: cards details differ between transactions!";
            }
        }

        $this->_log("Fraud check: order ID: " . $order_id . ": " . json_encode($result));
        return $result;
    }

    public function classifyOrder($order_id) {

        $fraud_module_enabled = $this->config->get("config_enable_fraud_module_client");
        if($fraud_module_enabled == '1') {
            $c = curl_init();
            curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($c, CURLOPT_TIMEOUT, 10);
            curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($c, CURLOPT_URL, sprintf($this->_config["classifier_url"], $order_id));

            $response = curl_exec($c);
            $curl_errno = curl_errno($c);
            $curl_error = curl_error($c);

            if ($curl_errno > 0) {
                $result = array(
                    "result" => false,
                    "decision" => 2,
                    "message" => sprintf("Request error: code %s, %s", $curl_errno, $curl_error)
                );
                $this->notifier->add(
                    (new Notification())
                        ->setError("ClassifierRequestCurlError", $result["message"])
                )->notify();
            } else {
                $result = json_decode($response, true);
            }

            curl_close($c);
        } else {
            $result = array(
                "result" => true,
                "decision" => 1,
                "message" => "Fraud module disabled for client"
            );
        }

        return new FraudResponse($result);
    }

    public function similarOrderCards($order_id_1, $order_id_2) {
        $card_1 = $this->getOrderCard($order_id_1);
        $card_2 = $this->getOrderCard($order_id_2);

        if(!$card_1 || !$card_2) {
            return true;
        }

        similar_text(strtolower($card_1["name"]), strtolower($card_2["name"]), $percent);
        if($percent < 50.0) {
            $this->_log(sprintf(
                "SimilarOrderCards %s vs %s: names too different (%s), %s vs %s",
                $order_id_1, $order_id_2, $percent, $card_1["name"], $card_2["name"]
            ));
            return false;
        }

        if($card_1["country"] !== $card_1["country"]) {
            $this->_log(sprintf(
                "SimilarOrderCards %s vs %s: countries don't match, %s vs %s",
                $order_id_1, $order_id_2, $card_1["country"], $card_2["country"]
            ));
            return false;
        }

        if($card_1["country"] === "US") {
            if($card_1["address_state"] !== $card_2["address_state"]) {
                $this->_log(sprintf(
                    "SimilarOrderCards %s vs %s: US stated don't match, %s vs %s",
                    $order_id_1, $order_id_2, $card_1["address_state"], $card_2["address_state"]
                ));
                return false;
            }
        }

        return true;
    }

    protected function getOrderCard($order_id) {
        $card_query = $this->db->query("
                SELECT card_json FROM `" . DB_PREFIX . "order_card`
                WHERE order_id = '" . $this->db->escape($order_id) . "'
            ");

        if($card_query->num_rows) {
            return json_decode($card_query->row["card_json"], true);
        }

        return false;
    }

    public function findRelated($order_id) {

        $order = $this->model_checkout_order->getOrder($order_id);
        $order_products = $this->model_checkout_order->getOrderProducts($order_id);

        $imeis = array();
        foreach($order_products as $order_product) {
            $imeis[] = $order_product["imei"];
        }

        // todo: look only for NOT completed / processing orders?
        $related_query = $this->db->query("
                SELECT DISTINCT o.order_id FROM `" . DB_PREFIX . "order` o
                WHERE (o.uuid = '" . $order["uuid"] . "'
                OR o.email = '" . $order["email"] . "'
                OR o.ip = '" . $order["ip"] . "'
                OR EXISTS (
                    SELECT op.order_id FROM `" . DB_PREFIX . "order_product` op
                    WHERE op.imei IN (" . implode(", ", $imeis) . ")
                    AND op.order_id = o.order_id
                ))
                AND o.order_id != " . $order["order_id"] . "
                AND o.payment_method = 'stripe'
            ");

        return $related_query->rows;
    }

    public function checkIP($ip, $raw=false) {
        $cache_key = "fraud:lookup:" . $ip;

        if(!($response = $this->cache->get($cache_key))) {

            $url = "http://ipcheck.devsti.me/v1/check/$ip";

            $c = curl_init();
            curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($c, CURLOPT_TIMEOUT, 5);
            curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($c, CURLOPT_URL, $url);
            $response = curl_exec($c);
            curl_close($c);
            $this->_log($response);

            $this->cache->set($cache_key, $response, 60 * 60 * 48);
        }

        $response_json = json_decode($response, true);
        $this->_log("New IP scan: " . str_replace("/\r|\n/", "", $response));

        if($raw) {
            return $response_json["result"];
        }

        if((float)$response_json["result"] > 0.99) {
            return false;
        }

        return true;
    }

    public function handleOrderCheck($order_id, $paid=false) {
        $fraud_check = $this->classifyOrder($order_id);
        $related_orders_enabled = $this->config->get("config_enable_fraud_module_client");
        if($related_orders_enabled == '1') {
            $reason = $fraud_check->getReason();
        } else {
            $reason = "Fraud module disabled for client";
        }

        $order_status_override = false;

        if($fraud_check->decision == ModelFraudFraud::$CHECK_OUTCOME_PASS) {
            $this->model_checkout_order->update($order_id, "18", $reason);

        } else if($fraud_check->decision == ModelFraudFraud::$CHECK_OUTCOME_HOLD) {
            // pending approval
            if($paid) {
                // when paid, mark as "Pending Approval" instead of "Pending Approval (Unpaid)"
                $order_status_override = "20";
            } else {
                $order_status_override = "22";
            }

            $this->model_checkout_order->update($order_id, $order_status_override, $reason);
            $this->notifier->add(
                (new Notification())
                    ->setError("CheckOutcomeHold", $reason)
            )->notify();

        } else if($fraud_check->decision == ModelFraudFraud::$CHECK_OUTCOME_REJECT) {
            // fraud detected
            // todo: some enum instead of manual response codes?
            $order_status_override = "21";
            $this->addIdentityFromOrder($order_id);
            $this->addIMEIFromOrder($order_id);

            $this->model_checkout_order->update($order_id, "21", $reason);
            $order_data = $this->model_checkout_order->getOrder($order_id);
            if($order_data["txn_id"]) {
                // Pending Refund
                $order_status_override = "23";
                $this->model_checkout_order->update($order_id, "23", "Scheduled for a refund.");
            }

            $this->notifier->add(
                (new Notification())
                    ->setError("CheckOutcomeReject", $reason)
            )->notify();
        }

        return $order_status_override;
    }

    public function sendFraudWarningEmail($order_id) {
        $this->load->model("checkout/order");

        $order_data = $this->model_checkout_order->getOrder($order_id);
        $this->language->setLanguage($this->db, $order_data["language_id"]);

        $order_products = $this->model_checkout_order->getOrderProducts($order_id);

        if($order_data["language_id"] == "1") {
            $language = "en";
            $subject = sprintf("Problem with your order number %s", $order_id);
            $order_details_line = "%s, carrier: %s, IMEI: %s";
        } else {
            $language = "es";
            $subject = sprintf("Problema con su orden de número de %s", $order_id);
            $order_details_line = "%s, operador: %s, IMEI: %s";
        }

        $order_details = array();

        foreach($order_products as $order_product) {
            $order_details[] = sprintf($order_details_line,
                $order_product["name"], $order_product["carrier"], $order_product["imei"]);
        }

        $content = $this->getMailContent("fraud_detected", $language, array(
            "{order_id}" => $order_id,
            "{order_details}" => implode("\r\n", $order_details)
        ));

        $message = str_replace("\n", "<br>", $content);
        $content = $this->getMailContent('generic.html', $language, array('{content}' => $message));

        $this->sendMail(array($order_data["email"], "carimany@unlockriver.com"), $subject, $content, true);
    }

}