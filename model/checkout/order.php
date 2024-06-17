<?php

/**
 * @property Loader  load
 * @property ModelFraudGraph model_fraud_graph
 * @property ModelCatalogProduct model_catalog_product
 * @property ModelCheckoutCoupon model_checkout_coupon
 */
class ModelCheckoutOrder extends Model {
    public function create($data) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET 
                                                invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "',
                                                store_id = '" . (int)$data['store_id'] . "', 
                                                store_name = '" . $this->db->escape($data['store_name']) . "', 
                                                store_url = '" . $this->db->escape($data['store_url']) . "', 
                                                customer_id = '" . (int)$data['customer_id'] . "', 
                                                firstname = '" . $this->db->escape($data['firstname']) . "', 
                                                lastname = '" . $this->db->escape($data['lastname']) . "', 
                                                email = '" . $this->db->escape($data['email']) . "', 
                                                telephone = '" . $this->db->escape($data['telephone']) . "', 
												shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "',
												shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "',
												shipping_company = '" . $this->db->escape($data['shipping_company']) . "',
												shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "',
												shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "',
												shipping_city = '" . $this->db->escape($data['shipping_city']) . "',
												shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "',
												shipping_country = '" . $this->db->escape($data['shipping_country']) . "',
												shipping_country_id = '" . (int)$data['shipping_country_id'] . "',
												shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "',
												shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "',
												shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "',
												shipping_email = '" . $this->db->escape($data['shipping_email']) . "',
                                                payment_method = '" . $this->db->escape($data['payment_method']) . "', 
                                                total = '" . (float)$data['total'] . "', 
                                                order_status_id = '18',
                                                language_id = '" . (int)$data['language_id'] . "', 
                                                currency_id = '" . (int)$data['currency_id'] . "', 
                                                currency_code = '" . $this->db->escape($data['currency_code']) . "', 
                                                currency_value = '" . (float)$data['currency_value'] . "', 
                                                ip = '" . $this->db->escape($data['ip']) . "',
                                                fingerprint = '" . $this->db->escape($data['fingerprint']) . "',
                                                uuid = '" . $this->db->escape($data['uuid']) . "',
                                                date_added = NOW(), 
                                                date_modified = NOW()
                                        ");

        $order_id = $this->db->getLastId();

        foreach ($data['products'] as $product) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', 
                                                                                    product_id = '" . (int)$product['product_id'] . "', 
                                                                                    name = '" . $this->db->escape($product['name']) . "', 
                                                                                    imei = '" . $this->db->escape($product['imei']) . "', 
                                                                                    carrier = '" . $this->db->escape($product['carrier']) . "',
                                                                                    carrier_id = '" . (isset($product['carrier_id']) ? ((int)$product['carrier_id']) : "") . "',
                                                                                    quantity = '" . (int)$product['quantity'] . "', 
                                                                                    price = '" . (float)$product['price'] . "', 
                                                                                    total = '" . (float)$product['total'] . "',
                                                                                    advertised_delivery_time = '" . $this->db->escape($product['delivery_time']) . "'  
                                                                                    
                                                                                        ");
            $order_product_id = $this->db->getLastId();


        }

        foreach ($data['totals'] as $total) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', text = '" . $this->db->escape($total['text']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
            if($total['code'] === 'coupon') {
                $this->db->query("UPDATE `" . DB_PREFIX . "order` SET coupon = 1 WHERE order_id = '" . (int)$order_id . "'");
            }
        }

        return $order_id;
    }

    public function createunpaid($data) {
        $data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
        $data['store_id'] = $this->config->get('config_store_id');
        $data['store_name'] = $this->config->get('config_name');

        if ($data['store_id']) {
            $data['store_url'] = $this->config->get('config_url');
        } else {
            $data['store_url'] = HTTP_SERVER;
        }

        // $data['telephone'] = '';

        return $this->create($data);
    }

    public function createabandonedorder($data) {
        /*$query = $this->db->query("SELECT MAX(order_id) as LASTID FROM `" . DB_PREFIX . "order`");

        if($query->num_rows == 1){
                $order_id = $query->row['LASTID'] + 1;
        }else{
                $order_id = $this->db->getLastId();
        }*/

        $this->db->query("INSERT INTO `" . DB_PREFIX . "aorder` SET 
												firstname = '" . $this->db->escape($data['a_firstname']) . "', 
                                                lastname = '" . $this->db->escape($data['a_lastname']) . "', 
                                                store_name = '" . $this->db->escape($data['a_store_name']) . "', 
                                                store_url = '" . $this->db->escape($data['a_store_url']) . "', 
                                                email = '" . $this->db->escape($data['a_email']) . "', 
                                                total = '" . (float)$data['a_total'] . "', 
                                                order_status_id = '0', 
                                                language_id = '" . (int)$data['a_language_id'] . "', 
                                                date_added = NOW()
                                        ");

        $order_id = $this->db->getLastId();
        foreach ($data['a_products'] as $product) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "aorder_product SET order_id = '" . (int)$order_id . "', 
                                                                                    product_id = '" . (int)$product['product_id'] . "', 
                                                                                    name = '" . $this->db->escape($product['name']) . "', 
                                                                                    model = '" . $this->db->escape($product['imei']) . "', 
                                                                                    carrier = '" . $this->db->escape($product['carrier']) . "', 
                                                                                    quantity = '" . (int)$product['quantity'] . "', 
                                                                                    price = '" . (float)$product['price'] . "', 
                                                                                    total = '" . (float)$product['total'] . "' 
                                                                                        ");
            $order_product_id = $this->db->getLastId();

        }

        return $order_id;
    }

    public function getOrderStatus($order_id, $client = false) {
        $query = $this->db->query("SELECT o.*, os.name, oh.comment FROM `order` o LEFT JOIN `order_status` os ON (o.order_status_id = os.order_status_id) LEFT JOIN `order_history` oh ON (oh.order_id = o.order_id " . ($client ? " AND oh.notify = '1'" : "") . ") WHERE o.order_id = '". $this->db->escape((int)$order_id) ."' ORDER BY oh.date_added DESC LIMIT 0,1");
        //$query = $this->db->query("SELECT o.*, os.name FROM `order` o LEFT JOIN `order_status` os ON (o.order_status_id = os.order_status_id) WHERE o.order_id = '". $order_id ."'");
        if($query->num_rows == 1){
            return $query->row;
        }else{
            return 'Order not found';
        }
    }

    public function updateOrderFirstLastNames($order_id, $first_name, $last_name) {
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET firstname = '" . $this->db->escape($first_name) .  "', lastname =  '" . $this->db->escape($last_name) . "' WHERE order_id ='" . (int)$order_id . "'");
    }

    public function updateOrderSecondEmail($order_id, $email) {
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET shipping_email = '" . $this->db->escape($email) .  "' WHERE order_id ='" . (int)$order_id . "'");
    }

    public function setPaymentProviderTransactionID($order_id, $txn_id) {
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET txn_id = '" . $this->db->escape($txn_id) . "' WHERE order_id = '" . $this->db->escape($order_id) . "'");
    }
    
    public function setPaymentCaptureID($order_id, $capture_id) {
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape($capture_id) . "' WHERE order_id = '" . $this->db->escape($order_id) . "'");
    }

    public function deleteOrder($orderID) {
        $this->db->query("DELETE FROM `order` WHERE order_id = '" . $orderID . "'");
        $this->db->query("DELETE FROM `order_product` WHERE order_id = '" . $orderID . "'");
    }

    public function moveToAbandoned($orderID) {

        $order = $this->getOrder($orderID);
        if($order) {
            $products = $this->getOrderProducts($orderID);
            $this->deleteOrder($orderID);

            foreach ($order as $key => $value) {
                $order["a_" . $key] = $value;
                unset($order[$key]);
            }

            $this->createabandonedorder(array_merge(
                $order,
                array(
                    "a_products" => $products
                )
            ));
        }
    }

    public function getByIMEI($imei, $onlyUnpaid = true) {
        $query = "SELECT op.order_id FROM `" . DB_PREFIX . "order_product` op, `" . DB_PREFIX . "order` o WHERE op.imei = '" . $this->db->escape($imei) . "'";
        if($onlyUnpaid) {
            $query .= " AND op.order_id = o.order_id AND o.order_status_id = '18'";
        }

        $queryResult = $this->db->query($query);
        $results = array();
        foreach($queryResult->rows as $row) {
            array_push($results, $row["order_id"]);
        }

        return $results;
    }

    public function getByTXNID($txn_id) {
        $query = "SELECT DISTINCT o.order_id FROM `order` o WHERE o.txn_id = '" . $this->db->escape($txn_id) . "'";
        $query_result = $this->db->query($query);

        return (isset($query_result->row['order_id']) ? $query_result->row['order_id'] : false);
    }

    public function getOrderCardFingerprint($order_id) {
        $query = "SELECT oc.fingerprint FROM `order_card` oc WHERE oc.order_id = '" . $this->db->escape($order_id) . "'";
        $query_result = $this->db->query($query);
        if($query_result->num_rows) {
            return $query_result->row["fingerprint"];
        }

        return false;
    }

    public function getOrderProducts($order_id) {
        $query = $this->db->query("SELECT DISTINCT op.*, p.delivery_time, ptc.category_id, mtp.unlockapp FROM " . DB_PREFIX . "order_product op
				LEFT JOIN product_to_category ptc ON (ptc.product_id = op.product_id)
				LEFT JOIN product p ON (p.product_id = op.product_id)
				LEFT JOIN manufacturer_to_product mtp ON (mtp.product_id = op.product_id AND mtp.manufacturer_id = IFNULL(op.carrier_id, mtp.manufacturer_id))
				WHERE op.order_id = '" . (int)$order_id . "'"
        );

        return $query->rows;
    }

    public function getOrder($order_id) {
        $order_query = $this->db->query("SELECT *, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");

        if ($order_query->num_rows) {
            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

            if ($country_query->num_rows) {
                $shipping_iso_code_2 = $country_query->row['iso_code_2'];
                $shipping_iso_code_3 = $country_query->row['iso_code_3'];
            } else {
                $shipping_iso_code_2 = '';
                $shipping_iso_code_3 = '';
            }

            $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

            if ($zone_query->num_rows) {
                $shipping_zone_code = $zone_query->row['code'];
            } else {
                $shipping_zone_code = '';
            }

            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)(isset($order_query->row['payment_country_id']) ? $order_query->row['payment_country_id'] : -1) . "'");

            if ($country_query->num_rows) {
                $payment_iso_code_2 = $country_query->row['iso_code_2'];
                $payment_iso_code_3 = $country_query->row['iso_code_3'];
            } else {
                $payment_iso_code_2 = '';
                $payment_iso_code_3 = '';
            }

            $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)(isset($order_query->row['payment_zone_id']) ? $order_query->row['payment_zone_id'] : -1 ). "'");

            if ($zone_query->num_rows) {
                $payment_zone_code = $zone_query->row['code'];
            } else {
                $payment_zone_code = '';
            }

            $this->load->model('localisation/language');

            $language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

            if ($language_info) {
                $language_code = $language_info['code'];
                $language_filename = $language_info['filename'];
                $language_directory = $language_info['directory'];
            } else {
                $language_code = '';
                $language_filename = '';
                $language_directory = '';
            }

            return array(
                'order_id'                => $order_query->row['order_id'],
                'txn_id'				  => $order_query->row['txn_id'],
                'invoice_no'              => $order_query->row['invoice_no'],
                'invoice_prefix'          => $order_query->row['invoice_prefix'],
                'store_id'                => $order_query->row['store_id'],
                'store_name'              => $order_query->row['store_name'],
                'store_url'               => $order_query->row['store_url'],
                'customer_id'             => $order_query->row['customer_id'],
                'firstname'               => $order_query->row['firstname'],
                'lastname'                => $order_query->row['lastname'],
                'telephone'               => $order_query->row['telephone'],
                // 'fax'                     => $order_query->row['fax'],
                'email'                   => $order_query->row['email'],
                'shipping_firstname'      => $order_query->row['shipping_firstname'],
                'shipping_lastname'       => $order_query->row['shipping_lastname'],
                'shipping_company'        => $order_query->row['shipping_company'],
                'shipping_address_1'      => $order_query->row['shipping_address_1'],
                'shipping_address_2'      => $order_query->row['shipping_address_2'],
                'shipping_postcode'       => $order_query->row['shipping_postcode'],
                'shipping_city'           => $order_query->row['shipping_city'],
                'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
                'shipping_zone'           => $order_query->row['shipping_zone'],
                'shipping_zone_code'      => $shipping_zone_code,
                'shipping_country_id'     => $order_query->row['shipping_country_id'],
                'shipping_country'        => $order_query->row['shipping_country'],
                'shipping_iso_code_2'     => $shipping_iso_code_2,
                'shipping_iso_code_3'     => $shipping_iso_code_3,
                'shipping_address_format' => $order_query->row['shipping_address_format'],
                'shipping_email' => $order_query->row['shipping_email'],
                // 'shipping_method'         => $order_query->row['shipping_method'],
                // 'payment_firstname'       => $order_query->row['payment_firstname'],
                // 'payment_lastname'        => $order_query->row['payment_lastname'],
                // 'payment_company'         => $order_query->row['payment_company'],
                // 'payment_address_1'       => $order_query->row['payment_address_1'],
                // 'payment_address_2'       => $order_query->row['payment_address_2'],
                // 'payment_postcode'        => $order_query->row['payment_postcode'],
                // 'payment_city'            => $order_query->row['payment_city'],
                // 'payment_zone_id'         => $order_query->row['payment_zone_id'],
                // 'payment_zone'            => $order_query->row['payment_zone'],
                // 'payment_zone_code'       => $payment_zone_code,
                // 'payment_country_id'      => $order_query->row['payment_country_id'],
                // 'payment_country'         => $order_query->row['payment_country'],
                'payment_iso_code_2'      => $payment_iso_code_2,
                'payment_iso_code_3'      => $payment_iso_code_3,
                // 'payment_address_format'  => $order_query->row['payment_address_format'],
                'payment_method'          => $order_query->row['payment_method'],
                // 'comment'                 => $order_query->row['comment'],
                'total'                   => $order_query->row['total'],
                'order_status_id'         => $order_query->row['order_status_id'],
                'order_status'            => $order_query->row['order_status'],
                'language_id'             => $order_query->row['language_id'],
                'language_code'           => $language_code,
                'language_filename'       => $language_filename,
                'language_directory'      => $language_directory,
                'currency_id'             => $order_query->row['currency_id'],
                'currency_code'           => $order_query->row['currency_code'],
                'currency_value'          => $order_query->row['currency_value'],
                'date_modified'           => $order_query->row['date_modified'],
                'date_added'              => $order_query->row['date_added'],
                'ip'                      => $order_query->row['ip'],
                'uuid'					  => $order_query->row['uuid'],
                'fingerprint'             => $order_query->row['fingerprint']
            );
        } else {
            return false;
        }
    }

    public function getUnpaidOrdersOlderThan($hours=3, $limit=300) {
        $queryResults = $this->db->query("SELECT * FROM `order` o WHERE o.order_status_id = '18' AND o.date_added < NOW() - INTERVAL {$hours} HOUR LIMIT {$limit}");
        $results = array();

        foreach($queryResults->rows as $result) {
            array_push($results, $result["order_id"]);
        }

        return $results;
    }

    public function confirm($order_id, $order_status_id, $comment = '', $notify = false) {
        $order_info = $this->getOrder($order_id);

        if ($order_info && !$order_info['order_status_id']) {
            $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

            $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '1', comment = '" . $this->db->escape(($comment && $notify) ? $comment : '') . "', date_added = NOW()");

            $order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

            foreach ($order_product_query->rows as $order_product) {
                $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");

                $order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product['order_product_id'] . "'");

                foreach ($order_option_query->rows as $option) {
                    $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
                }
            }

            $this->cache->delete('product');

            $order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "'");

            foreach ($order_total_query->rows as $order_total) {
                $this->load->model('total/' . $order_total['code']);

                if (method_exists($this->{'model_total_' . $order_total['code']}, 'confirm')) {
                    $this->{'model_total_' . $order_total['code']}->confirm($order_info, $order_total);
                }
            }

            // Send out any gift voucher mails
            if ($this->config->get('config_complete_status_id') == $order_status_id) {
                $this->load->model('checkout/voucher');

                $this->model_checkout_voucher->confirm($order_id);
            }

            // Send out order confirmation mail
            $language = new Language($order_info['language_directory']);
            $language->load($order_info['language_filename']);
            $language->load('mail/order');

            $order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");

            if ($order_status_query->num_rows) {
                $order_status = $order_status_query->row['name'];
            } else {
                $order_status = '';
            }

            $order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
            $order_total_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");
            $order_download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "'");

            $subject = sprintf($language->get('text_new_subject'), $order_info['store_name'], $order_id);

            // HTML Mail
            $template = new Template();

            $template->data['title'] = sprintf($language->get('text_new_subject'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);

            $template->data['text_greeting'] = sprintf($language->get('text_new_greeting'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
            $template->data['text_link'] = $language->get('text_new_link');
            $template->data['text_download'] = $language->get('text_new_download');
            $template->data['text_order_detail'] = $language->get('text_new_order_detail');
            $template->data['text_instruction'] = $language->get('text_new_instruction');
            $template->data['text_order_id'] = $language->get('text_new_order_id');
            $template->data['text_date_added'] = $language->get('text_new_date_added');
            $template->data['text_payment_method'] = $language->get('text_new_payment_method');
            $template->data['text_shipping_method'] = $language->get('text_new_shipping_method');
            $template->data['text_email'] = $language->get('text_new_email');
            $template->data['text_telephone'] = $language->get('text_new_telephone');
            $template->data['text_ip'] = $language->get('text_new_ip');
            $template->data['text_payment_address'] = $language->get('text_new_payment_address');
            $template->data['text_shipping_address'] = $language->get('text_new_shipping_address');
            $template->data['text_product'] = $language->get('text_new_product');
            $template->data['text_model'] = $language->get('text_new_model');
            $template->data['text_quantity'] = $language->get('text_new_quantity');
            $template->data['text_price'] = $language->get('text_new_price');
            $template->data['text_total'] = $language->get('text_new_total');
            $template->data['text_footer'] = $language->get('text_new_footer');
            $template->data['text_powered'] = $language->get('text_new_powered');

            $template->data['logo'] = 'cid:' . md5(basename($this->config->get('config_logo')));
            $template->data['store_name'] = $order_info['store_name'];
            $template->data['store_url'] = $order_info['store_url'];
            $template->data['customer_id'] = $order_info['customer_id'];
            $template->data['link'] = $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id;

            if ($order_download_query->num_rows) {
                $template->data['download'] = $order_info['store_url'] . 'index.php?route=account/download';
            } else {
                $template->data['download'] = '';
            }

            $template->data['order_id'] = $order_id;
            $template->data['date_added'] = date($language->get('date_format_short'), strtotime($order_info['date_added']));
            $template->data['payment_method'] = $order_info['payment_method'];
            $template->data['shipping_method'] = $order_info['shipping_method'];
            $template->data['email'] = $order_info['email'];
            $template->data['telephone'] = $order_info['telephone'];
            $template->data['ip'] = $order_info['ip'];

            if ($comment && $notify) {
                $template->data['comment'] = nl2br($comment);
            } else {
                $template->data['comment'] = '';
            }

            if ($order_info['shipping_address_format']) {
                $format = $order_info['shipping_address_format'];
            } else {
                $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
            }

            $find = array(
                '{firstname}',
                '{lastname}',
                '{company}',
                '{address_1}',
                '{address_2}',
                '{city}',
                '{postcode}',
                '{zone}',
                '{zone_code}',
                '{country}'
            );

            $replace = array(
                'firstname' => $order_info['shipping_firstname'],
                'lastname'  => $order_info['shipping_lastname'],
                'company'   => $order_info['shipping_company'],
                'address_1' => $order_info['shipping_address_1'],
                'address_2' => $order_info['shipping_address_2'],
                'city'      => $order_info['shipping_city'],
                'postcode'  => $order_info['shipping_postcode'],
                'zone'      => $order_info['shipping_zone'],
                'zone_code' => $order_info['shipping_zone_code'],
                'country'   => $order_info['shipping_country']
            );

            $template->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            if ($order_info['payment_address_format']) {
                $format = $order_info['payment_address_format'];
            } else {
                $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
            }

            $find = array(
                '{firstname}',
                '{lastname}',
                '{company}',
                '{address_1}',
                '{address_2}',
                '{city}',
                '{postcode}',
                '{zone}',
                '{zone_code}',
                '{country}'
            );

            $replace = array(
                'firstname' => $order_info['payment_firstname'],
                'lastname'  => $order_info['payment_lastname'],
                'company'   => $order_info['payment_company'],
                'address_1' => $order_info['payment_address_1'],
                'address_2' => $order_info['payment_address_2'],
                'city'      => $order_info['payment_city'],
                'postcode'  => $order_info['payment_postcode'],
                'zone'      => $order_info['payment_zone'],
                'zone_code' => $order_info['payment_zone_code'],
                'country'   => $order_info['payment_country']
            );

            $template->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            $template->data['products'] = array();

            foreach ($order_product_query->rows as $product) {
                $option_data = array();

                $order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");

                foreach ($order_option_query->rows as $option) {
                    if ($option['type'] != 'file') {
                        $option_data[] = array(
                            'name'  => $option['name'],
                            'value' => (strlen($option['value']) > 20 ? substr($option['value'], 0, 20) . '..' : $option['value'])
                        );
                    } else {
                        $filename = substr($option['value'], 0, strrpos($option['value'], '.'));

                        $option_data[] = array(
                            'name'  => $option['name'],
                            'value' => (strlen($filename) > 20 ? substr($filename, 0, 20) . '..' : $filename)
                        );
                    }
                }

                $template->data['products'][] = array(
                    'name'     => $product['name'],
                    'model'    => $product['model'],
                    'option'   => $option_data,
                    'quantity' => $product['quantity'],
                    'price'    => $this->currency->format($product['price'], $order_info['currency_code'], $order_info['currency_value']),
                    'total'    => $this->currency->format($product['total'], $order_info['currency_code'], $order_info['currency_value'])
                );
            }

            $template->data['totals'] = $order_total_query->rows;

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/order.tpl')) {
                $html = $template->fetch($this->config->get('config_template') . '/template/mail/order.tpl');
            } else {
                $html = $template->fetch('default/template/mail/order.tpl');
            }

            // Text Mail
            $text  = sprintf($language->get('text_new_greeting'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8')) . "\n\n";
            $text .= $language->get('text_new_order_id') . ' ' . $order_id . "\n";
            $text .= $language->get('text_new_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n";
            $text .= $language->get('text_new_order_status') . ' ' . $order_status . "\n\n";

            if ($comment && $notify) {
                $text .= $language->get('text_new_instruction') . "\n\n";
                $text .= $comment . "\n\n";
            }

            $text .= $language->get('text_new_products') . "\n";

            foreach ($order_product_query->rows as $result) {
                $text .= $result['quantity'] . 'x ' . $result['name'] . ' (' . $result['model'] . ') ' . html_entity_decode($this->currency->format($result['total'], $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";

                $order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . $result['order_product_id'] . "'");

                foreach ($order_option_query->rows as $option) {
                    $text .= chr(9) . '-' . $option['name'] . ' ' . (strlen($option['value']) > 20 ? substr($option['value'], 0, 20) . '..' : $option['value']) . "\n";
                }
            }

            $text .= "\n";

            $text .= $language->get('text_new_order_total') . "\n";

            foreach ($order_total_query->rows as $result) {
                $text .= $result['title'] . ' ' . html_entity_decode($result['text'], ENT_NOQUOTES, 'UTF-8') . "\n";
            }

            $text .= "\n";

            if ($order_info['customer_id']) {
                $text .= $language->get('text_new_link') . "\n";
                $text .= $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id . "\n\n";
            }

            if ($order_download_query->num_rows) {
                $text .= $language->get('text_new_download') . "\n";
                $text .= $order_info['store_url'] . 'index.php?route=account/download' . "\n\n";
            }

            if ($order_info['comment']) {
                $text .= $language->get('text_new_comment') . "\n\n";
                $text .= $order_info['comment'] . "\n\n";
            }

            $text .= $language->get('text_new_footer') . "\n\n";

            $mail = new Mail();
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->hostname = $this->config->get('config_smtp_host');
            $mail->username = $this->config->get('config_smtp_username');
            $mail->password = $this->config->get('config_smtp_password');
            $mail->port = $this->config->get('config_smtp_port');
            $mail->timeout = $this->config->get('config_smtp_timeout');
            $mail->setTo($order_info['email']);
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender($order_info['store_name']);
            $mail->setSubject($subject);
            $mail->setHtml($html);
            $mail->setText(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
            $mail->addAttachment(DIR_IMAGE . $this->config->get('config_logo'), md5(basename($this->config->get('config_logo'))));
            $mail->send();

            // Admin Alert Mail
            if ($this->config->get('config_alert_mail')) {
                $subject = sprintf($language->get('text_new_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'), $order_id);

                // Text
                $text  = $language->get('text_new_received') . "\n\n";
                $text .= $language->get('text_new_order_id') . ' ' . $order_id . "\n";
                $text .= $language->get('text_new_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n";
                $text .= $language->get('text_new_order_status') . ' ' . $order_status . "\n\n";
                $text .= $language->get('text_new_products') . "\n";

                foreach ($order_product_query->rows as $result) {
                    $text .= $result['quantity'] . 'x ' . $result['name'] . ' (' . $result['model'] . ') ' . html_entity_decode($this->currency->format($result['total'], $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";

                    $order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . $result['order_product_id'] . "'");

                    foreach ($order_option_query->rows as $option) {
                        $text .= chr(9) . '-' . $option['name'] . ' ' . (strlen($option['value']) > 20 ? substr($option['value'], 0, 20) . '..' : $option['value']) . "\n";
                    }
                }

                $text .= "\n";

                $text .= $language->get('text_new_order_total') . "\n";

                foreach ($order_total_query->rows as $result) {
                    $text .= $result['title'] . ' ' . html_entity_decode($result['text'], ENT_NOQUOTES, 'UTF-8') . "\n";
                }

                $text .= "\n";

                if ($order_info['comment'] != '') {
                    $comment = ($order_info['comment'] .  "\n\n" . $comment);
                }

                if ($comment) {
                    $text .= $language->get('text_new_comment') . "\n\n";
                    $text .= $comment . "\n\n";
                }

                $mail = new Mail();
                $mail->protocol = $this->config->get('config_mail_protocol');
                $mail->parameter = $this->config->get('config_mail_parameter');
                $mail->hostname = $this->config->get('config_smtp_host');
                $mail->username = $this->config->get('config_smtp_username');
                $mail->password = $this->config->get('config_smtp_password');
                $mail->port = $this->config->get('config_smtp_port');
                $mail->timeout = $this->config->get('config_smtp_timeout');
                $mail->setTo($this->config->get('config_email'));
                $mail->setFrom($this->config->get('config_email'));
                $mail->setSender($order_info['store_name']);
                $mail->setSubject($subject);
                $mail->setText($text);
                $mail->send();

                // Send to additional alert emails
                $emails = explode(',', $this->config->get('config_alert_emails'));

                foreach ($emails as $email) {
                    if ($email && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
                        $mail->setTo($email);
                        $mail->send();
                    }
                }
            }
        }
    }

    public function update($order_id, $order_status_id, $comment = '', $notify = false) {
        $order_info = $this->getOrder($order_id);

        if ($order_info && $order_info['order_status_id']) {
            $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

            $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");

            // Send out any gift voucher mails
            if ($this->config->get('config_complete_status_id') == $order_status_id) {
                $this->load->model('checkout/voucher');

                $this->model_checkout_voucher->confirm($order_id);
            }

            if ($notify) {
                $language = new Language($order_info['language_directory']);
                $language->load($order_info['language_filename']);
                $language->load('mail/order');

                $subject = sprintf($language->get('text_update_subject'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);

                $message  = $language->get('text_update_order') . ' ' . $order_id . "\n";
                $message .= $language->get('text_update_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n\n";

                $order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");

                if ($order_status_query->num_rows) {
                    $message .= $language->get('text_update_order_status') . "\n\n";
                    $message .= $order_status_query->row['name'] . "\n\n";
                }

                if ($order_info['customer_id']) {
                    $message .= $language->get('text_update_link') . "\n";
                    $message .= $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id . "\n\n";
                }

                if ($comment) {
                    $message .= $language->get('text_update_comment') . "\n\n";
                    $message .= $comment . "\n\n";
                }

                $message .= $language->get('text_update_footer');

                $mail = new Mail();
                $mail->protocol = $this->config->get('config_mail_protocol');
                $mail->parameter = $this->config->get('config_mail_parameter');
                $mail->hostname = $this->config->get('config_smtp_host');
                $mail->username = $this->config->get('config_smtp_username');
                $mail->password = $this->config->get('config_smtp_password');
                $mail->port = $this->config->get('config_smtp_port');
                $mail->timeout = $this->config->get('config_smtp_timeout');
                $mail->setTo($order_info['email']);
                $mail->setFrom($this->config->get('config_email'));
                $mail->setSender($order_info['store_name']);
                $mail->setSubject($subject);
                $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
                $mail->send();
            }
        }
    }

    public function getOrderDeliveryTime($order_id) {
        $delivery_time_query = $this->db->query("SELECT p.delivery_time "
            . "FROM `order` o, `product` p, `order_product` op "
            . "WHERE op.order_id = o.order_id "
            . "AND op.product_id = p.product_id "
            . "AND o.order_id = '" . (int)$order_id . "' "
            . "LIMIT 1");

        return $delivery_time_query->row["delivery_time"];
    }

    public function customeremail($order_id, $order_status_id, $email, $language_id) {
        //Send customer email

        $order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "'");

        if ($order_status_query->num_rows) {
            $order_status = $order_status_query->row['name'];
        } else {
            $order_status = '';
        }

        $order_data = $this->getOrder($order_id);

        $replaceArray = array(
            "{first_name}" => $order_data["firstname"],
            "{order_id}" => $order_id,
            "{total}" => $this->currency->format($order_data["total"], 'USD', false, false),
        );

        if($language_id == 1){
            //English
            $subject = sprintf('%s Confirmation - %s', $this->config->get('config_name'), $order_id);
            $language = "en";
        } else {
            //Spanish
            $subject = sprintf('Confirmación %s - %s', $this->config->get('config_name'), $order_id);
            $language = "es";
        }


        $products = $this->getOrderProducts($order_id);
        $this->load->model("catalog/product");
        foreach($products as &$product) {
            $product["price_formatted"] = $this->currency->format($product['price'], 'USD', false, false);
            $details = $this->model_catalog_product->getProduct($product["product_id"]);
            $image = $details["image"];

            $product["image"] = "https://www.unlockpanda.com/image/" . $image;
        }

        $partials = array(
            "order_confirmation_partial.html" => $products
        );


        $text = $this->getMailContent("order_confirmation.html", $language, $replaceArray, $partials);
        $this->sendMail($email, $subject, $text, true);
    }

    public function eCheckPendingEmail($order_id, $skip_billing=false) {
        $order = $this->getOrder($order_id);
        if($order) {

            $replace_array = array(
                "{order_id}" => $order_id
            );

            if($order["language_id"] == 1) {
                $subject = "Order Status - Payment Processing";
                $language = "en";
            } else {
                $subject = "Status de Orden - Pago Procesando";
                $language = "es";
            }

            $text = $this->getMailContent("echeck_pending", $language, $replace_array);

            $message = str_replace("\n", "<br>", $text);
            $content = $this->getMailContent('generic.html', $language, array('{content}' => $message));

            $this->sendMail($order["email"], $subject, $content, true);
            $this->sendMail($this->config->get("config_dev_email"), $subject, $content, true);
            if(!$skip_billing) {
                $this->sendMail($this->config->get("config_billing_email"), $subject, $content, true);
            }

            return $text;
        }

        return "";
    }

    public function eCheckCompletedEmail($order_id, $skip_billing=false) {
        $order = $this->getOrder($order_id);
        if($order) {

            $replace_array = array(
                "{order_id}" => $order_id
            );

            if($order["language_id"] == 1) {
                $subject = "Order Status - Processing";
                $language = "en";
            } else {
                $subject = "Status de Order - Procesando";
                $language = "es";
            }
            
            $text = $this->getMailContent("echeck_completed", $language, $replace_array);

            $message = str_replace("\n", "<br>", $text);
            $content = $this->getMailContent('generic.html', $language, array('{content}' => $message));

            $this->sendMail($order["email"], $subject, $content, true);
            $this->sendMail($this->config->get("config_dev_email"), $subject, $content, true);
            if(!$skip_billing) {
                $this->sendMail($this->config->get("config_billing_email"), $subject, $content, true);
            }

            return $text;
        }

        return "";
    }

    public function eCheckFailedEmail($order_id, $skip_billing=false) {
        $order = $this->getOrder($order_id);
        if($order) {

            $replace_array = array(
                "{order_id}" => $order_id
            );

            if($order["language_id"] == 1) {
                $subject = "Payment Failed - Order Cancelled";
                $language = "en";
            } else {
                $subject = "Pago rechazado - Orden Cancelada";
                $language = "es";
            }

            $text = $this->getMailContent("echeck_failed", $language, $replace_array);
            $this->sendMail($order["email"], $subject, $text, true);
            $this->sendMail($this->config->get("config_dev_email"), $subject, $text, true);
            if(!$skip_billing) {
                $this->sendMail("billing@unlockriver.com", $subject, $text, true);
            }

            return $text;
        }

        return "";
    }

    public function saveGiftInfo($order_id, $type, $gift_array) {
        $order = $this->getOrder($order_id);
        $ip = $order["ip"];

        $cache_key = sprintf("timezone:%s", $ip);
        $timezone = $this->cache->get($cache_key);
        if(!$timezone) {
            $timezone = json_decode(file_get_contents(sprintf("http://freegeoip.net/json/%s", $ip)), true)["time_zone"];
            if(!$timezone) {
                $timezone = "America/Guatemala";
            }
            $this->cache->set($cache_key, $timezone);
        }

        $this->db->query("
	        INSERT INTO " . DB_PREFIX . "gift
	        (order_id, type, client_name, gifted_client_name, gifted_client_notify, gifted_client_email, gift_delivery_time, client_timezone)
	        VALUES
	        (
	            '" . $this->db->escape($order_id) . "', '" . $this->db->escape($type) . "',
	            '" . $this->db->escape($gift_array["client_name"]) . "', '" . $this->db->escape($gift_array["gifted_client_name"]) . "',
	            '" . $this->db->escape($gift_array["gifted_client_notify"] == "true") . "', '" . $this->db->escape($gift_array["gifted_client_email"]) . "',
	            '" . $this->db->escape($gift_array["gift_delivery_time"]) . "',
	            '" . $this->db->escape($timezone) . "'
	        )
	    ");
    }

    public function validate($product_id, $carrier_id, $check_enabled = true) {
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');

        $carrier = $this->model_catalog_manufacturer->getManufacturer($carrier_id);
        $category = $this->model_catalog_product->getCategories($product_id);
        if($category) {
            $category_info = $this->model_catalog_category->getCategory($category['category_id']);
        } else {
            $category_info = false;
        }
        $product = $this->model_catalog_product->getProduct($product_id);
        $product_enabled = !$check_enabled || ($product && $product['status'] == '1');
        $carrier_products = $this->model_catalog_product->getCarriersByProduct($product_id);
        $carrier_product_exists = false;
        foreach($carrier_products as $carrier_product) {
            if ($carrier_product['manufacturer_id'] == $carrier_id) {
                $carrier_product_exists = true;
            }
        }

        if(!$carrier || !$category_info || !$product || !$carrier_product_exists || !$product_enabled) {
            $this->notifier->add(
                (new Notification())
                    ->setError("DetailedAddToCartCheck@", sprintf("Error a%s b%s c%s d%s e%s", $category['category_id'], !$category_info, !$product, !$carrier_product_exists, !$product_enabled))
            )->notify();
            return false;
        }
        return true;
    }


    public function getOrderIDByCardDetails($last4, $amount, $batch_date) {
        // For AuthorizeNet EMSData chargebacks
        // Attempts to retrieve a transaction by known last4, amount and batch_date

        $result = $this->db->query("
            SELECT o.order_id
            FROM `order` o
            JOIN `order_card` oc ON o.order_id = oc.order_id
            WHERE oc.last_four = '" . $this->db->escape($last4) . "'
            AND o.total = '" . $this->db->escape($amount) . "'
            AND (DATE(o.date_added) BETWEEN DATE_SUB(DATE('" . $this->db->escape($batch_date) . "'), INTERVAL 7 DAY) AND DATE('" . $this->db->escape($batch_date) . "')) 
        ");

        if($result->num_rows == 1) {
            return $result->row['order_id'];
        }
        return null;
    }
}
?>