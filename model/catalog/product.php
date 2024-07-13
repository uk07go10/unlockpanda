<?php

/**
 * Class ModelCatalogProduct
 * @property ModelFraudFraud $model_fraud_fraud
 */
class ModelCatalogProduct extends Model {

    public $delivery_time_regex = "";
    public $delivery_time_entities = array(
        "business days", "days", "hours", "hrs", "business hours", "business hrs"
    );

    public function __construct($registry) {
        parent::__construct($registry);
        $this->delivery_time_regex = "/([0-9]+)\s*-\s*([0-9]+)\s(" . implode("|", $this->delivery_time_entities) . ")/";
    }

    public function deliveryTimeToHours($delivery_time_text) {
        $delivery_time = array(
            "from" => array(),
            "to" => array(),
            "original" => array()
        );

        preg_match($this->delivery_time_regex, $delivery_time_text, $matches);

        $delivery_time['original']['from'] = $matches[1];
        $delivery_time['original']['to'] = $matches[2];
        $delivery_time['original']['span'] = $matches[3];

        if(in_array($matches[3], array("business days", "days"))) {
            $delivery_time["from"]["hours"] = 24 * (int)$matches[1];
            $delivery_time["from"]["days"] = (int)$matches[1];
            $delivery_time["to"]["hours"] = 24 * (int)$matches[2];
            $delivery_time["to"]["days"] = (int)$matches[2];
        } else {
            $delivery_time["from"]["hours"] = (int)$matches[1];
            $delivery_time["from"]["days"] = ceil((int)$matches[1] / 24);
            $delivery_time["to"]["hours"] = (int)$matches[2];
            $delivery_time["to"]["days"] = ceil((int)$matches[2] / 24);
        }

        return $delivery_time;
    }

    public function updateViewed($product_id) {
        $this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
    }

    public function getProduct($product_id) {
        if ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getCustomerGroupId();
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        $query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.status, p.image,
			(SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id
				AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1'
				AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW())
				AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW()))
			ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount,
			(SELECT price FROM " . DB_PREFIX . "product_special ps
				WHERE ps.product_id = p.product_id
				AND ps.customer_group_id = '" . (int)$customer_group_id . "'
				AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW())
				AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))
				ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special,
			(SELECT ss.name FROM " . DB_PREFIX . "stock_status ss
				WHERE ss.stock_status_id = p.stock_status_id
				AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status,
			(SELECT AVG(rating) AS total
			FROM " . DB_PREFIX . "review r1
			WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating,
			(SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id
			AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p
			LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
			LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)
			WHERE p.product_id = '" . (int)$product_id . "'
			AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
			AND p.status IN ('1', '2') AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

        if ($query->num_rows) {
            $query->row['price'] = ($query->row['discount'] ? $query->row['discount'] : $query->row['price']);
            $query->row['rating'] = (int)$query->row['rating'];

            return $query->row;
        } else {
            return false;
        }
    }

    public function getCarriersByProduct($product_id) {
        $cache_key = "product_carrier." . $this->config->get('config_language_id') . "." . $product_id;
        $carriers = $this->cache->get($cache_key);

        $results = array();
        if(!$carriers) {
            $sql = "SELECT DISTINCT mtp.manufacturer_id, m.name " .
                "FROM manufacturer_to_product mtp " .
                "JOIN manufacturer m ON (mtp.manufacturer_id = m.manufacturer_id) " .
                "WHERE mtp.product_id = '" . (int)$this->db->escape($product_id) . "'" .
                "ORDER BY m.name ASC";

            $data = $this->db->query($sql);
            $results = $data->rows;
            $this->cache->set($cache_key, $results);

        } else {
            $results = $carriers;
        }

        return $results;
    }

    public function getBrandsByCarrier($carrier_id) {
        $cache_key = "brands." . $this->config->get('config_language_id') . " . " . $carrier_id;
        $brands = $this->cache->get($cache_key);

        $results = array();

        if(!$brands) {
          
            $sql = "SELECT c.category_id, cd.name FROM category c " .
                "JOIN category_description cd ON (c.category_id = cd.category_id) " .
                "WHERE c.status = '1' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' " .
                "AND EXISTS (" .
                "SELECT ptc.product_id FROM product_to_category ptc WHERE ptc.category_id = c.category_id AND EXISTS ( " .
                "SELECT mtp.manufacturer_id FROM manufacturer_to_product mtp WHERE ptc.product_id = mtp.product_id AND mtp.manufacturer_id = '" . (int)$this->db->escape($carrier_id) . "' LIMIT 1)" .
                "AND EXISTS (SELECT p.product_id FROM product p WHERE ptc.product_id = p.product_id AND p.status IN ('1', '2'))) " .
                "ORDER BY cd.name ASC";

            $data = $this->db->query($sql);
            echo "<pre>"; print_r($data);
            foreach($data->rows as $row) {
                $results[] = array(
                    "category_id" => $row["category_id"],
                    "name" => $row["name"]
                );
            }

            $this->cache->set($cache_key, $results);

        } else {
            $results = $brands;
        }

        return $results;

    }

    public function getProducts($data = array(), $short = false) {
        if ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getCustomerGroupId();
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        $cache = md5(http_build_query($data)) . ($short ? ".short" : "");

        $product_data = $this->cache->get('product.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache);

        if (!$product_data) {
            $sql = "SELECT p.product_id, pd.name, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)";

            if (!empty($data['filter_tag'])) {
                $sql .= " LEFT JOIN " . DB_PREFIX . "product_tag pt ON (p.product_id = pt.product_id)";
            }

            if (!empty($data['filter_category_id'])) {
                $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
            }

            $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status IN ('1', '2')  AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

            if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
                $sql .= " AND (";

                if (!empty($data['filter_name'])) {
                    $implode = array();

                    $words = explode(' ', $data['filter_name']);

                    foreach ($words as $word) {
                        if (!empty($data['filter_description'])) {
                            $implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%' OR LCASE(pd.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
                        } else {
                            $implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
                        }
                    }

                    if ($implode) {
                        $sql .= " " . implode(" OR ", $implode) . "";
                    }
                }

                if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
                    $sql .= " OR ";
                }

                if (!empty($data['filter_tag'])) {
                    $implode = array();

                    $words = explode(' ', $data['filter_tag']);

                    foreach ($words as $word) {
                        $implode[] = "LCASE(pt.tag) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%' AND pt.language_id = '" . (int)$this->config->get('config_language_id') . "'";
                    }

                    if ($implode) {
                        $sql .= " " . implode(" OR ", $implode) . "";
                    }
                }

                $sql .= ")";
            }

            if (!empty($data['filter_category_id'])) {
                if (!empty($data['filter_sub_category'])) {
                    $implode_data = array();

                    $implode_data[] = "p2c.category_id = '" . (int)$data['filter_category_id'] . "'";

                    $this->load->model('catalog/category');

                    $categories = $this->model_catalog_category->getCategoriesByParentId($data['filter_category_id']);

                    foreach ($categories as $category_id) {
                        $implode_data[] = "p2c.category_id = '" . (int)$category_id . "'";
                    }

                    $sql .= " AND (" . implode(' OR ', $implode_data) . ")";
                } else {
                    $sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
                }
            }

            if (!empty($data['filter_manufacturer_id'])) {
                $sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
            }

            if(!empty($data['filter_carrier_id'])) {
                $sql .= " AND EXISTS (SELECT mtp.product_id FROM manufacturer_to_product mtp WHERE mtp.product_id = p.product_id AND mtp.manufacturer_id = '" . (int)$data['filter_carrier_id'] . "')";
            }

            $sql .= " GROUP BY p.product_id";

            $sort_data = array(
                'pd.name',
                'p.model',
                'p.quantity',
                'p.price',
                'rating',
                'p.sort_order',
                'p.date_added'
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
                    $sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
                } else {
                    $sql .= " ORDER BY " . $data['sort'];
                }
            } else {
                $sql .= " ORDER BY p.sort_order";
            }

            if (isset($data['order']) && ($data['order'] == 'DESC')) {
                $sql .= " DESC";
            } else {
                $sql .= " ASC";
            }

            if (isset($data['start']) || isset($data['limit'])) {
                if ($data['start'] < 0) {
                    $data['start'] = 0;
                }

                if ($data['limit'] < 1) {
                    $data['limit'] = 20;
                }

                $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
            }

            $product_data = array();

            $query = $this->db->query($sql);

            foreach ($query->rows as $result) {
                $product_data[$result['product_id']] = ($short ? $result : $this->getProduct($result['product_id']));
            }

            $this->cache->set('product.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache, $product_data);
        }

        return $product_data;
    }

    public function getProductSpecials($data = array()) {
        if ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getCustomerGroupId();
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        $sql = "SELECT DISTINCT ps.product_id, (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = ps.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1'  AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) GROUP BY ps.product_id";

        $sort_data = array(
            'pd.name',
            'p.model',
            'ps.price',
            'rating',
            'p.sort_order'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
                $sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
            } else {
                $sql .= " ORDER BY " . $data['sort'];
            }
        } else {
            $sql .= " ORDER BY p.sort_order";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $product_data = array();

        $query = $this->db->query($sql);

        foreach ($query->rows as $result) {
            $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
        }

        return $product_data;
    }

    public function getLatestProducts($limit) {
        $product_data = $this->cache->get('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$limit);

        if (!$product_data) {
            $query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);

            foreach ($query->rows as $result) {
                $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
            }

            $this->cache->set('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$limit, $product_data);
        }

        return $product_data;
    }

    public function getPopularProducts($limit) {
        $product_data = array();

        $query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed, p.date_added DESC LIMIT " . (int)$limit);

        foreach ($query->rows as $result) {
            $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
        }

        return $product_data;
    }

    public function getBestSellerProducts($limit) {
        $product_data = $this->cache->get('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$limit);

        if (!$product_data) {
            $product_data = array();

            $query = $this->db->query("SELECT op.product_id, COUNT(*) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id IN (1, 5) AND DATE(o.date_added) > DATE(DATE_SUB(NOW(), INTERVAL 28 DAY)) AND p.status = '1'  AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit);

            foreach ($query->rows as $result) {
                $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
            }

            $this->cache->set('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$limit, $product_data);
        }

        return $product_data;
    }

    public function getProductAttributes($product_id) {
        $product_attribute_group_data = array();

        $product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");

        foreach ($product_attribute_group_query->rows as $product_attribute_group) {
            $product_attribute_data = array();

            $product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.sort_order, ad.name");

            foreach ($product_attribute_query->rows as $product_attribute) {
                $product_attribute_data[] = array(
                    'attribute_id' => $product_attribute['attribute_id'],
                    'name'         => $product_attribute['name'],
                    'text'         => $product_attribute['text']
                );
            }

            $product_attribute_group_data[] = array(
                'attribute_group_id' => $product_attribute_group['attribute_group_id'],
                'name'               => $product_attribute_group['name'],
                'attribute'          => $product_attribute_data
            );
        }

        return $product_attribute_group_data;
    }

    public function getProductOptions($product_id) {
        $product_option_data = array();

        $product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");

        foreach ($product_option_query->rows as $product_option) {
            if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
                $product_option_value_data = array();

                $product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");

                foreach ($product_option_value_query->rows as $product_option_value) {
                    $product_option_value_data[] = array(
                        'product_option_value_id' => $product_option_value['product_option_value_id'],
                        'option_value_id'         => $product_option_value['option_value_id'],
                        'name'                    => $product_option_value['name'],
                        'image'                   => $product_option_value['image'],
                        'quantity'                => $product_option_value['quantity'],
                        'subtract'                => $product_option_value['subtract'],
                        'price'                   => $product_option_value['price'],
                        'price_prefix'            => $product_option_value['price_prefix'],
                        'weight'                  => $product_option_value['weight'],
                        'weight_prefix'           => $product_option_value['weight_prefix']
                    );
                }

                $product_option_data[] = array(
                    'product_option_id' => $product_option['product_option_id'],
                    'option_id'         => $product_option['option_id'],
                    'name'              => $product_option['name'],
                    'type'              => $product_option['type'],
                    'option_value'      => $product_option_value_data,
                    'required'          => $product_option['required']
                );
            } else {
                $product_option_data[] = array(
                    'product_option_id' => $product_option['product_option_id'],
                    'option_id'         => $product_option['option_id'],
                    'name'              => $product_option['name'],
                    'type'              => $product_option['type'],
                    'option_value'      => $product_option['option_value'],
                    'required'          => $product_option['required']
                );
            }
        }

        return $product_option_data;
    }

    public function getProductDiscounts($product_id) {
        if ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getCustomerGroupId();
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");

        return $query->rows;
    }

//	public function getProductImages($product_id) {
//		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");
//
//		return $query->rows;
//	}

    public function getProductRelated($product_id) {
        $product_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1'  AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

        foreach ($query->rows as $result) {
            $product_data[$result['related_id']] = $this->getProduct($result['related_id']);
        }

        return $product_data;
    }

    public function getProductTags($product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_tag WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->rows;
    }

    public function getProductLayoutId($product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

        if ($query->num_rows) {
            return $query->row['layout_id'];
        } else {
            return  $this->config->get('config_layout_product');
        }
    }

    public function getCategories($product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

        return $query->row;
    }

    public function getTotalProducts($data = array()) {
        $sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)";

        if (!empty($data['filter_category_id'])) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
        }

        if (!empty($data['filter_tag'])) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "product_tag pt ON (p.product_id = pt.product_id)";
        }

        $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

        if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
            $sql .= " AND (";

            if (!empty($data['filter_name'])) {
                $implode = array();

                $words = explode(' ', $data['filter_name']);

                foreach ($words as $word) {
                    if (!empty($data['filter_description'])) {
                        $implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%' OR LCASE(pd.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
                    } else {
                        $implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
                    }
                }

                if ($implode) {
                    $sql .= " " . implode(" OR ", $implode) . "";
                }
            }

            if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
                $sql .= " OR ";
            }

            if (!empty($data['filter_tag'])) {
                $implode = array();

                $words = explode(' ', $data['filter_tag']);

                foreach ($words as $word) {
                    $implode[] = "LCASE(pt.tag) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%' AND pt.language_id = '" . (int)$this->config->get('config_language_id') . "'";
                }

                if ($implode) {
                    $sql .= " " . implode(" OR ", $implode) . "";
                }
            }

            $sql .= ")";
        }

        if (!empty($data['filter_category_id'])) {
            if (!empty($data['filter_sub_category'])) {
                $implode_data = array();

                $implode_data[] = "p2c.category_id = '" . (int)$data['filter_category_id'] . "'";

                $this->load->model('catalog/category');

                $categories = $this->model_catalog_category->getCategoriesByParentId($data['filter_category_id']);

                foreach ($categories as $category_id) {
                    $implode_data[] = "p2c.category_id = '" . (int)$category_id . "'";
                }

                $sql .= " AND (" . implode(' OR ', $implode_data) . ")";
            } else {
                $sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
            }
        }

        if (!empty($data['filter_manufacturer_id'])) {
            $sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalProductSpecials() {
        if ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getCustomerGroupId();
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        $query = $this->db->query("SELECT COUNT(DISTINCT ps.product_id) AS total FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))");

        if (isset($query->row['total'])) {
            return $query->row['total'];
        } else {
            return 0;
        }
    }

    public function isValidOrder($product_id, $carrier_id, $imei) {
        if(substr($imei, 0, 2) == "99") {
            // CDMA

            // check if either from Blackberry or Apple brands
            $is_cdma_supported_brand_query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p
				WHERE p.product_id = '" . $this->db->escape($product_id) . "'
				AND EXISTS (
					SELECT ptc.category_id FROM product_to_category ptc
					WHERE ptc.product_id = p.product_id
					AND ptc.category_id IN (61, 60)
				)");

            $is_cdma_supported_brand = $is_cdma_supported_brand_query->num_rows > 0;
            $is_cdma_supported_carrier = in_array($carrier_id, array(
                40, 469 // sprint, verizon
            ));

            return $is_cdma_supported_brand && $is_cdma_supported_carrier;

        }

        return true;
    }

    public function isBlocked($ip, $email, $imei) {
        $ip = trim($ip);
        $email = trim($email);

        $list_text = $this->config->get("config_blocked_clients");
        $list = explode("\n", $list_text);
        foreach($list as $entry) {
            $entry = trim($entry);
            if($ip == $entry || $email == $entry || $imei == $entry) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $category_id int|string manufacturer name
     * @param $product_id int|string model
     * @param $carrier_id int|string carrier
     * @return bool
     */
    public function isDelayed($category_id, $product_id, $carrier_id) {

        $delayed = false;

        return $delayed;
    }

    public function convertDeliveryTime($delivery_time_text, $target_lang) {
        if ($target_lang != 'es' || !is_string($delivery_time_text)) {
            return $delivery_time_text;
        }

        return str_replace(array(
            "business days",
            "days",
            "hours",
            "weeks"
        ), array(
            "días hábiles",
            "días",
            "horas",
            "semanas"
        ), $delivery_time_text);
    }
    
    public function getBestSellerProductsLinks($count=7) {
        $cache_key = 'product.bestseller.links.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$count;
        $data = $this->cache->get($cache_key);
        if (!$data) {
            $data = array();
            $query = $this->getBestSellerProducts($count);
            foreach($query as $product_id => $value) {
                $data[] = array(
                   "name" => $value["name"],
                   "url" => $this->url->link('product/product', 'product_id=' . $product_id) 
                ); 
            }
            $this->cache->set($cache_key, $data);
        }
        
        return $data;
    }
}
?>