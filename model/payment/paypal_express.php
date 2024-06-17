<?php

/*
 * Copyright (c) 2012 Web Project Solutions LLC (info@webprojectsol.com)
 *
 * SOFTWARE LICENSE AGREEMENT
 *
 * This Software is not free.
 *
 * Developer hereby grants to Licensee a perpetual, non-exclusive,
 * limited license to use the Software as set forth in this Agreement.
 *
 * Licensee shall not modify, copy, duplicate, reproduce, license or sublicense the Software,
 * or transfer or convey the Software or any right in the Software to anyone else
 * without the prior written consent of Developer; provided that Licensee may
 * make one copy of the Software for backup or archival purposes.
 *
 *
 *  @author Antonello Venturino <info@webprojectsol.com>
 *  @copyright  2012 Web Project Solutions LLC
 *  @license    http://www.webprojectsol.com/license.php
 *  @url  http://www.webprojectsol.com/en/modules-of-payment/paypal-express-checkout.html
 */

class ModelPaymentPayPalExpress extends Model {

    public function getMethod($address, $total) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int) $this->config->get('paypal_express_geo_zone_id') . "' AND country_id = '" . (int) $address['country_id'] . "' AND (zone_id = '" . (int) $address['zone_id'] . "' OR zone_id = '0')");

        if ($this->config->get('paypal_express_total') > $total) {
            $status = false;
        } elseif ($this->config->get('paypal_express_total_over') > 0 && $this->config->get('paypal_express_total_over') < $total) {
            $status = false;
        } elseif (!$this->config->get('paypal_express_geo_zone_id')) {
            $status = true;
        } elseif ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }
        if ($this->config->get('paypal_express_enableincheckout') == '0') {
            $status = false;
        }

        $method_data = array();

        if ($status) {
            $method_data = array(
                'code' => 'paypal_express',
                'title' => html_entity_decode($this->config->get('paypal_express_title_' . $this->config->get('config_language_id'))),
                'sort_order' => $this->config->get('paypal_express_sort_order')
            );
        }

        return $method_data;
    }

}

?>