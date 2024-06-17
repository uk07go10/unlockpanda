<?php

/**
 * @property DB db
 * @property Config config
 */
class ModelReferralReferral extends Model {

    private $_cookie_key = "r";
    private $_default_referral_multiplier_value = 0.05;

    private $_logger;
    private $_debug = true;

    /**
     * Used when adding new referral
     * @var array
     */
    private $available_languages = array(
        "en", "es"
    );

    /**
     * Valid values for order events. For details see static vars below
     * @var array
     */
    private $_valid_order_statuses = array(
        "ADD_LOCK", "ADD", "SUB", "LOCK", "PAID"
    );

    /**
     * Status of a transaction, used when a transaction is added to referral (adds the value
     * to `balance_locked`)
     * @var string
     */
    static $ORDER_STATUS_ADD_LOCK = "ADD_LOCK";

    /**
     * Status of a transaction, used when a transaction referral value can be paid out (adds the value
     * to `balance`)
     * @var string
     */
    static $ORDER_STATUS_ADD = "ADD";

    /**
     * Status of a transaction, used when a transaction value is subtracted from the account
     * @var string
     */
    static $ORDER_STATUS_SUB = "SUB";

    /**
     * Amount lock for payout
     * @var string
     */
    static $ORDER_STATUS_LOCK = "LOCK";

    /**
     * Amount payout
     * @var string
     */
    static $ORDER_STATUS_PAID = "PAID";

    public function __construct($registry) {
        parent::__construct($registry);
        $this->_logger = new Log("referral.txt");
    }

    /**
     * Regenerate the password for given account
     * @param $email
     * @return string new password
     */
    public function regeneratePassword($email) {
        //todo: replace with something more sophisticated
        $password = substr(str_shuffle(str_repeat("0123456789", 8)) , 0 , 8);
        $hash = password_hash($password, PASSWORD_BCRYPT);

        $this->db->query("
                UPDATE " . DB_PREFIX . "referral
                SET `password` = '" . $hash . "'
                WHERE email = '" . $this->db->escape($email) . "'
            ");

        return $password;
    }

    /**
     * Logging utility
     * @param $content string
     * @param bool $force bool
     */
    private function _log($content, $force=false) {
        if($force || $this->_debug) {
            $this->_logger->write($content);
        }
    }

    /**
     * Get the referral code
     * @param array $array $request->cookie object
     * @return bool
     */
    public function getReferral($array = array()) {
        if(isset($array) && is_array($array) && array_key_exists($this->_cookie_key, $array)) {
            return $array[$this->_cookie_key];
        }

        return false;
    }


    /**
     * Set the ref_code for the current visitor
     * @param $code string ref_code
     */
    public function setReferral($code) {
        setcookie("r", $code, time() + 60 * 60 * 24 * 365);
    }


    /**
     * Create new referral using email, checking if he exists in the first place
     * If the referral exists - skip the creation and return existing ref_code
     * @param string $email email of the user being referral
     * @param string $language
     * @return string referral code
     */
    public function addReferral($email, $language="en") {
        if(!in_array($language, $this->available_languages)) {
            $language = "en";
        }

        $exists = $this->getReferralDataByEmail($email);
        if(!$exists) {
            return $this->_addReferral($email, $language);
        } else {
            return $exists["ref_code"];
        }
    }

    /**
     * Create new referral using email. Password is set to some temp value
     * @param $email string email of the user being referral
     * @param string $language
     * @return string referral code
     */
    private function _addReferral($email, $language="en") {
        $ref_code = $this->_generateReferralCode(array("email" => $email));
        $this->db->query("
            INSERT INTO " . DB_PREFIX . "referral
            SET email = '" . $this->db->escape($email) . "',
            ref_code = '" . $this->db->escape($ref_code) . "',
            `password` = '" . password_hash(time() . rand(100, 2000), PASSWORD_BCRYPT) . "',
            `language` = '" . $this->db->escape($language) . "',
            balance=0.0
        ");

        return $ref_code;
    }

    /**
     * Create a unique ref_code identifier. It will be used in URLs and cookies
     * @param $params array of parameters used
     * @return string unique ref_code
     */
    private function _generateReferralCode($params) {
        $code = sha1($params["email"] . time() . rand(0, 999));
        if($this->getReferralDataByCode($code)) {
            return $this->_generateReferralCode($params);
        } else {
            return $code;
        }
    }

    /**
     * Activate user account
     * @param $email string
     */
    public function activate($email) {
        $this->db->query("
                UPDATE " . DB_PREFIX . "referral
                SET active = 1
                WHERE email = '" . $this->db->escape($email) . "'
            ");
    }

    /**
     * Return the referral by given email, if not exists: return false
     * @param $email string
     */
    public function getReferralDataByEmail($email) {
        $query_result = $this->db->query("
            SELECT * FROM " . DB_PREFIX . "referral
            WHERE email = '" . $this->db->escape($email) . "'
        ");

        return $query_result->row;
    }

    /**
     * Get information about referral using ref_id
     * @param $ref_id string|int referral ref_id
     * @return mixed
     */
    public function getReferralDataByCode($ref_id) {
        $query_result = $this->db->query("
            SELECT * FROM " . DB_PREFIX . "referral
            WHERE ref_code = '" . $this->db->escape($ref_id) . "'
        ");

        return $query_result->row;
    }

    /**
     * Returns referral ID basing on email or false if not exists
     * @param $email string referral email
     * @return mixed referral ID or false
     */
    public function emailToReferralID($email) {
        if(!$referral = $this->getReferralDataByEmail($email)) {
            return false;
        }

        return $referral["ref_id"];
    }

    /**
     * Return email of a client associated with ref_id
     * @param $ref_id string|int referral ref_id
     * @return bool|string
     */
    public function referralCodeToEmail($ref_id) {
        if(!$referral = $this->getReferralDataByCode($ref_id)) {
            return false;
        }

        return $referral["email"];
    }

    /**
     * Connects the referral with an order
     * Things that are checked:
     * - if the order exists
     * - if it's already linked to someone
     * @param $email string email of the referral
     * @param $order_id int|string ID of the related order
     * @return bool whether there was not error
     */
    public function addOrderToReferral($email, $order_id) {

        // check if referral exists
        if(!$ref_id = $this->emailToReferralID($email)) {
            return false;
        }

        $this->load->model("checkout/order");

        $order_model = new ModelCheckoutOrder($this->registry);
        $order = $order_model->getOrder($order_id);

        // check if order exists
        if(!$order) {
            return false;
        }

        // check if the order isn't already in the referral_to_order table
        $ref_to_order_query = $this->db->query("
            SELECT ref_id FROM " . DB_PREFIX . "referral_to_order
            WHERE order_id = '" . $this->db->escape($order_id) . "'
        ");

        if($ref_to_order_query->num_rows > 0) {
            return false;
        }

        $this->db->query("
            INSERT INTO " . DB_PREFIX . "referral_to_order
            SET ref_id = '" . $this->db->escape($ref_id) . "',
            order_id = '" . $this->db->escape($order_id) . "',
            order_paid = '0'
        ");

        return true;
    }

    /**
     * Marks transaction as paid, adds the history record.
     * This method should be invoked with $unlock set to true after the `cooldown period`,
     * this way the "locked" amount will be available for payout.
     * Things that are checked:
     * - if referral exists
     * - ($unlock) corresponding locked transaction exist
     * - ($unlock) corresponding sub transaction doesn't exist
     * - the same transaction doesn't exist in the transaction log
     * @param int|string $order_id
     * @param bool $unlock whether the transaction should be unlocked
     * @return bool
     */
    public function markOrderPaid($order_id, $unlock=false) {
        // todo: transaction should be used here, but not with this driver..

        $ref_id_query = $this->db->query("
                SELECT rto.ref_id FROM " . DB_PREFIX . "referral_to_order rto
                WHERE rto.order_id = '" . $this->db->escape($order_id) . "'
            ");

        if($ref_id_query->num_rows == 0) {
            return false;
        }

        $ref_id = $ref_id_query->row["ref_id"];

        if($unlock) {
            // make sure corresponding locked transaction exist
            $history_add_locked_exists = $this->db->query("
                    SELECT ref_id
                    FROM " . DB_PREFIX . "referral_balance_history
                    WHERE ref_id = '" . $this->db->escape($ref_id) . "'
                    AND `action` = 'ADD_LOCK'
                    AND `reference_type` = 'ORDER'
                    AND reference_id = '" . $this->db->escape($order_id) . "'
                ");

            // make sure corresponding sub transaction doesn't exist
            $history_sub_exists = $this->db->query("
                    SELECT ref_id
                    FROM " . DB_PREFIX . "referral_balance_history
                    WHERE ref_id = '" . $this->db->escape($ref_id) . "'
                    AND `action` = 'SUB'
                    AND `reference_type` = 'ORDER'
                    AND reference_id = '" . $this->db->escape($order_id) . "'
                ");

            if(!$history_add_locked_exists->num_rows || $history_sub_exists->num_rows) {
                return false;
            }
        }

        // check if history with the current action already exists
        // if yes, we'll skip marking it as paid

        $action_mark = "ADD_LOCK";
        if($unlock) {
            $action_mark = "ADD";
        }

        $history_add_exists = $this->db->query("
                SELECT ref_id
                FROM " . DB_PREFIX . "referral_balance_history
                WHERE ref_id = '" . $this->db->escape($ref_id) . "'
                AND `action` = '" . $action_mark . "'
                AND `reference_type` = 'ORDER'
                AND reference_id = '" . $this->db->escape($order_id) . "'
            ");

        if($history_add_exists->num_rows > 0) {
            return false;
        }

        $total = $this->_getReferralValue($order_id);

        if($total === false) {
            return false;
        }

        // add the row to referral_balance_history
        $this->db->query("
                INSERT INTO " . DB_PREFIX . "referral_balance_history
                SET ref_id = '" . $this->db->escape($ref_id) . "',
                `action` = '" . $action_mark . "',
                `value` = " . $this->db->escape($total) . ",
                reference_type = 'ORDER',
                reference_id = '" . $this->db->escape($order_id) . "'
            ");

        $order_paid_status = "1";
        if($unlock) {
            $order_paid_status = "2";
        }

        // update referral_to_order table
        $this->db->query("
                UPDATE " . DB_PREFIX . "referral_to_order
                SET order_paid = '" . $order_paid_status . "'
                WHERE ref_id = '" . $this->db->escape($ref_id) . "'
                AND order_id = '" . $this->db->escape($order_id) . "'
            ");

        // update the balance
        // todo: transaction this urgently with all the above

        $balance_field = "balance_locked";
        if($unlock) {
            $this->db->query("
                UPDATE " . DB_PREFIX . "referral
                SET " . $balance_field . " = " . $balance_field . " - " . $this->db->escape($total) . "
                WHERE ref_id = '" . $this->db->escape($ref_id) . "'
            ");

            $balance_field = "balance";
        }

        $this->db->query("
                UPDATE " . DB_PREFIX . "referral
                SET " . $balance_field . " = " . $balance_field . " + " . $this->db->escape($total) . "
                WHERE ref_id = '" . $this->db->escape($ref_id) . "'
            ");

        return true;
    }

    /**
     * Marks transaction as refunded or charged back, adds the history record.
     * @param $order_id int|string
     * @return bool
     */
    public function markOrderRefunded($order_id) {
        // todo: transaction should be used here, but not with this driver..

        $ref_id_query = $this->db->query("
                SELECT rto.ref_id FROM " . DB_PREFIX . "referral_to_order rto
                WHERE rto.order_id = '" . $this->db->escape($order_id) . "'
            ");

        if($ref_id_query->num_rows == 0) {
            return false;
        }

        $ref_id = $ref_id_query->row["ref_id"];

        // related ADD operation has to exist
        $history_add_exists = $this->db->query("
                SELECT `action`
                FROM " . DB_PREFIX . "referral_balance_history
                WHERE ref_id = '" . $this->db->escape($ref_id) . "'
                AND `action` IN ('ADD_LOCK', 'ADD')
                AND `reference_type` = 'ORDER'
                AND reference_id = '" . $this->db->escape($order_id) . "'
            ");

        if(!$history_add_exists->num_rows) {
            return false;
        }

        // check if history with sub action already exists
        // if yes, we'll skip marking it as sub
        $history_sub_exists = $this->db->query("
                SELECT ref_id
                FROM " . DB_PREFIX . "referral_balance_history
                WHERE ref_id = '" . $this->db->escape($ref_id) . "'
                AND `action` = 'SUB'
                AND `reference_type` = 'ORDER'
                AND reference_id = '" . $this->db->escape($order_id) . "'
            ");

        if($history_sub_exists->num_rows > 0) {
            return false;
        }

        $total = $this->_getReferralValue($order_id);

        if($total === false) {
            return false;
        }

        // add the row to referral_balance_history
        $this->db->query("
                INSERT INTO " . DB_PREFIX . "referral_balance_history
                SET ref_id = '" . $this->db->escape($ref_id) . "',
                `action` = 'SUB',
                `value` = " . $this->db->escape(-$total) . ",
                reference_type = 'ORDER',
                reference_id = '" . $this->db->escape($order_id) . "'
            ");

        // update referral_to_order table
        $this->db->query("
                UPDATE " . DB_PREFIX . "referral_to_order
                SET order_paid = '3'
                WHERE ref_id = '" . $this->db->escape($ref_id) . "'
                AND order_id = '" . $this->db->escape($order_id) . "'
            ");

        // update the balance
        // todo: transaction this urgently with all the above

        $balance_field = "balance";
        if($history_add_exists->num_rows == 1) {
            // this means that there was only "ADD_LOCK" transaction,
            // so we have to deduct the amount from the `balance_locked` field
            $balance_field = "balance_locked";
        }

        $this->db->query("
                UPDATE " . DB_PREFIX . "referral
                SET " . $balance_field . " = " . $balance_field . " - " . $this->db->escape($total) . "
                WHERE ref_id = '" . $this->db->escape($ref_id) . "'
            ");

        return true;
    }

    /**
     * Get balance for given referral account
     * @param $email string
     * @return bool
     */
    public function getBalance($email) {

        // check if referral exists
        if(!$ref_id = $this->emailToReferralID($email)) {
            return false;
        }

        $balance_query = $this->db->query("
                SELECT balance FROM " . DB_PREFIX . "referral
                WHERE ref_id = '" . $this->db->escape($ref_id) . "'
            ");

        return $balance_query->row["balance"];
    }

    /**
     * Get locked balance for given referral account
     * @param $email string
     * @return bool
     */
    public function getBalanceLocked($email) {
        // check if referral exists
        if(!$ref_id = $this->emailToReferralID($email)) {
            return false;
        }

        $balance_query = $this->db->query("
                SELECT balance_locked FROM " . DB_PREFIX . "referral
                WHERE ref_id = '" . $this->db->escape($ref_id) . "'
            ");

        return $balance_query->row["balance_locked"];
    }

    /**
     * Lock sum for payout.
     * Logic is as follows:
     * 1. Check if the requested payout sum is higher than zero
     * 2. Check if balance is higher than the requested payout sum
     * 3. Subtract the amount and make it appear as "locked" in the transaction history
     *
     * @param $email
     * @param $sum
     * @return bool
     */
    public function lockBalanceForPayout($email, $sum) {
        if($sum <= 0) {
            return false;
        }

        if(($balance = $this->getBalance($email)) === false) {
            return false;
        }

        if($sum > $balance) {
            return false;
        }

        $ref_id = $this->emailToReferralID($email);
        $rest = $balance - $sum;

        // todo: transaction
        $this->db->query("
                UPDATE " . DB_PREFIX . "referral
                SET balance = " . $this->db->escape($rest) . "
                WHERE ref_id = '" . $this->db->escape($ref_id) . "'
            ");

        // add the row to referral_balance_history
        $this->db->query("
                INSERT INTO " . DB_PREFIX . "referral_balance_history
                SET ref_id = '" . $this->db->escape($ref_id) . "',
                `action` = 'LOCK',
                `value` = -" . $this->db->escape($sum) . "
            ");

        $history_id = $this->db->getLastId();
        return $history_id;
    }

    /**
     * Marks payment as done, adding PAID transaction in the history
     * @param $lock_id
     * @param $txn_id
     * @return bool
     */
    public function markPayoutDone($lock_id, $txn_id) {

        $history_query = $this->db->query("
                SELECT ref_id, value FROM " . DB_PREFIX . "referral_balance_history
                WHERE history_id = '" . $this->db->escape($lock_id) . "'
            ");

        if($history_query->num_rows == 0) {
            return false;
        }

        $ref_id = $history_query->row["ref_id"];
        $value = $history_query->row["value"];

        // add the row to referral_balance_history
        $this->db->query("
                INSERT INTO " . DB_PREFIX . "referral_balance_history
                SET ref_id = '" . $this->db->escape($ref_id) . "',
                `action` = 'PAID',
                `value` = " . $value . ",
                reference_type = 'TXN',
                reference_id = '" . $this->db->escape($txn_id) . "'
            ");

        $payout_id = $this->db->getLastId();

        $this->db->query("
                UPDATE " . DB_PREFIX . "referral_balance_history
                SET `value` = 0,
                reference_type = 'PAID_ID',
                reference_id = '" . $this->db->escape($payout_id) . "'
                WHERE history_id = '" . $this->db->escape($lock_id) . "'
            ");

        return true;
    }

    /**
     * Return referral value
     * @param $order_id int|string
     * @return mixed
     */
    protected function _getReferralValue($order_id) {
        // get the order value
        $order_query = $this->db->query("
                SELECT o.total
                FROM `" . DB_PREFIX . "order` o
                WHERE o.order_id  = '" . $this->db->escape($order_id) . "'
            ");

        if($order_query->num_rows == 0) {
            return false;
        }

        $total = $order_query->row["total"];

        $multiplier = $this->config->get("config_referral_multiplier_value");
        if(!$multiplier) {
            $multiplier = $this->_default_referral_multiplier_value;
        }

        return $total * $multiplier;
    }

    /**
     * Get referral's history of actions (incomes, locks, payouts, refunds)
     * @param $ref_id int|string referral id
     * @return array
     */
    public function getHistory($ref_id) {
        $history_query = $this->db->query("
                SELECT rbh.action, DATE(rbh.created_date) as created_date, rbh.value
                FROM referral_balance_history rbh
                WHERE rbh.ref_id = '" . $this->db->escape($ref_id) . "'
                ORDER BY rbh.history_id DESC
            ");

        if($history_query->num_rows) {
            return $history_query->rows;
        }

        return array();
    }


    /*
     * Admin-like section
     * I really hate OpenCart's model's.. model
     */


    /**
     * Get the locked referrals suitable for unlocking
     * - can't be already unlocked or refunded
     * - has to pass $min_days since the lock creation
     * @param string|int $min_days the days that have to pass
     */
    public function getLockedReferrals($min_days) {
        $min_days = (int)$min_days;
        if(!$min_days) {
            $min_days = 14;
        }

        $locked_query = $this->db->query("
            SELECT rbh.*
            FROM " . DB_PREFIX . "referral_balance_history rbh
            WHERE rbh.`action` = 'ADD_LOCK'
            AND NOT EXISTS
                (
                    SELECT rbh2.history_id
                    FROM " . DB_PREFIX . "referral_balance_history rbh2
                    WHERE rbh2.action IN ('ADD', 'SUB')
                    AND rbh2.reference_id = rbh.reference_id
                )
            AND rbh.created_date < DATE_SUB(CURDATE(), INTERVAL " . $this->db->escape($min_days) . " DAY)
        ");

        return $locked_query->rows;
    }

}