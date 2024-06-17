<?php

/**
 * @property ModelReferralReferral model_referral_referral
 * @property ModelFraudFraud model_fraud_fraud
 */
class ControllerTestingTesting extends Controller {

    public function test_referral() {
        $email_1 = "shamdog@gmail.com";
        $this->load->model("referral/referral");

        var_dump("ref_code testing");
        // ref_code should be returned
        $ref_1 = $this->model_referral_referral->addReferral($email_1);

        // on the second attempt, the same ref_code should be returned
        $ref_2 = $this->model_referral_referral->addReferral($email_1);
        var_dump($ref_1, $ref_2, $ref_1 === $ref_2);

        var_dump("adding order to referral - paid");
        $result = $this->model_referral_referral->addOrderToReferral($email_1, "134420");
        var_dump($result);
        $this->model_referral_referral->markOrderRefunded("134420");

        var_dump("adding order to referral - unpaid");
        $result = $this->model_referral_referral->addOrderToReferral($email_1, "134421");
        $this->model_referral_referral->markOrderPaid("134421");
        var_dump($result);
        $this->model_referral_referral->markOrderRefunded("134421");

        var_dump("get balance");
        $result_1 = $this->model_referral_referral->getBalance($email_1);
        $result_2 = $this->model_referral_referral->getBalanceLocked($email_1);
        var_dump($result_1, $result_2);

    }

    public function test_referral_payout() {
        $email_1 = "shamdog@gmail.com";
        $this->load->model("referral/referral");

        $ref_1 = $this->model_referral_referral->addReferral($email_1);
        $this->model_referral_referral->addOrderToReferral($email_1, "134421");
        $this->model_referral_referral->markOrderPaid("134421");
        $this->model_referral_referral->markOrderPaid("134421", true);

        var_dump("get balance");
        $balance = $this->model_referral_referral->getBalance($email_1);
        var_dump($balance);

        var_dump("try to lock more than the balance");
        $result = $this->model_referral_referral->lockBalanceForPayout($email_1, 2000);
        var_dump($result);

        var_dump("try to lock a reasonable amount");
        $lock_id = $this->model_referral_referral->lockBalanceForPayout($email_1, 1);
        var_dump($lock_id);

        var_dump("make clear the lock");
        $result = $this->model_referral_referral->markPayoutDone($lock_id, "example_TXN");
        var_dump($result);
    }

    public function test_referral_sub() {
        $email_1 = "shamdog@gmail.com";
        $this->load->model("referral/referral");

        $this->model_referral_referral->markOrderRefunded("134428");
    }

    public function test_fraud_ip() {
        $this->load->model("fraud/fraud");
        var_dump($this->model_fraud_fraud->checkIP($this->request->get["ip"]));
    }

    public function test_fraud_orders() {
        $start = isset($this->request->get["start"]) ? (int)$this->request->get["start"] : 0;
        $limit = isset($this->request->get["limit"]) ? (int)$this->request->get["limit"] : 10;

        $this->load->model("fraud/fraud");
        $fraud_rows_query = $this->db->query("
          SELECT o.order_id FROM `order` o
          WHERE o.payment_method = 'stripe'
          AND o.order_status_id = 13
          LIMIT " . $this->db->escape($start) . ", " . $this->db->escape($limit) . "
        ");

        foreach($fraud_rows_query->rows as $row) {
            var_dump($this->model_fraud_fraud->checkOrder($row["order_id"], false));
            echo "<br><br>";
        }
    }

    public function test_stripe() {
        $this->load->model("fraud/fraud");

        var_dump($this->model_fraud_fraud->checkOrder(134418));
    }

    public function test_fraud() {
        $this->load->model("fraud/fraud");

        $check = $this->model_fraud_fraud->classifyOrder(335058);

        var_dump($check);
        var_dump($check->getReason());
    }

}