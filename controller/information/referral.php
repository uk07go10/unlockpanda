<?php

/**
 * Class ControllerInformationReferral
 *
 * @property ModelReferralReferral $model_referral_referral
 */
class ControllerInformationReferral extends Controller {

    private $referral;

    public function __construct($registry) {
        parent::__construct($registry);
        $this->referral = new Referral($this->registry);

        $this->load->model("referral/referral");
        $this->language->load('information/referral');

        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header'
        );

        $this->setFromLanguage($this->language->getData());
        $this->data["flash"] = $this->_getFlash();

        $this->document->addStyle("catalog/view/theme/unlock_mobiles/stylesheet/referral.css");
    }

    protected function _setFlash($content, $type = "success") {
        $this->session->data["_flash_msg"] = array(
            "content" => $content,
            "type" => $type
        );
    }

    protected function _getFlash() {
        if (isset($this->session->data["_flash_msg"])) {
            $flash = $this->session->data["_flash_msg"];
            unset($this->session->data["_flash_msg"]);
            return $flash;
        }

        return false;
    }

    public function index() {
        if ($this->referral->isLogged()) {
            $this->redirect($this->url->link('information/referral/account', '', 'SSL'));
        }

        $this->document->setTitle($this->language->get('text_title'));

        $this->data["already_registered"] = isset($this->request->cookie["referral_already_registered"]);

        $this->setTemplate('information/referral');
        $this->response->setOutput($this->render());
    }

    public function verify() {
        if(isset($this->request->get["c"])) {
            $code = $this->request->get["c"];
            $data = $this->model_referral_referral->getReferralDataByCode($code);
            if($data) {
                $email = $data["email"];
                $active = $data["active"];
                $language = $data["language"];

                if(!$active) {
                    $this->model_referral_referral->activate($email);
                    $password = $this->model_referral_referral->regeneratePassword($email);
                    $this->model_referral_referral->sendMail(
                        $email,
                        $this->language->get("email_sub_password"),
                        $this->model_referral_referral->getMailContent(
                            "referral_password",
                            $language,
                            array(
                                "{login_url}" => $this->url->link("information/referral", "", "SSL"),
                                "{password}" => $password
                            )
                        ),
                        true
                    );
                    $this->_setFlash($this->language->get("flash_verified"));
                }
            }

        }

        $this->redirect($this->url->link("information/referral", "", "SSL"));
    }

    public function login() {
        if ($this->referral->isLogged()) {
            $this->redirect($this->url->link('information/referral/account', '', 'SSL'));
        }

        $successful = false;
        $active = false;

        if (isset($this->request->get["logkey"])) {
            // log in using logkey
        } else if (isset($this->request->post["email"]) && isset($this->request->post["password"])) {
            if ($this->referral->authenticate($this->request->post["email"], $this->request->post["password"])) {
                $this->referral->login($this->request->post["email"]);
                $successful = true;
                if($this->referral->isActive()) {
                    $active = true;
                } else {
                    $this->referral->logout();
                }
            }
        }

        if($successful) {
            if($active) {
                $this->_setFlash($this->language->get("flash_login_successful"));
                $this->redirect($this->url->link('information/referral/account', '', 'SSL'));
            } else {
                $this->_setFlash($this->language->get("flash_not_active"), "attention");
                $this->redirect($this->url->link('information/referral', '', 'SSL'));
            }
        } else {
            $this->_setFlash($this->language->get("flash_credentials"), "attention");
            $this->redirect($this->url->link('information/referral', '', 'SSL'));
        }
    }

    public function logout() {
        if ($this->referral->isLogged()) {
            $this->referral->logout();
            $this->_setFlash($this->language->get("flash_logout_successful"));
        }

        $this->redirect($this->url->link('information/referral', '', 'SSL'));
    }

    public function payout() {
        if (!$this->referral->isLogged()) {
            $this->_setFlash($this->language->get("flash_please_login"), "attention");
            $this->redirect($this->url->link('information/referral', '', 'SSL'));
        }

        if($this->referral->getBalance() < $this->config->get("config_referral_min_payout")) {
            $this->_setFlash(
                sprintf(
                    $this->language->get("text_payout_disabled"),
                    $this->config->get("config_referral_min_payout")
                ), "attention");
            $this->redirect($this->url->link('information/referral/account', '', 'SSL'));
        }

        $amount = (double)$this->request->post['amount'];
        $payout_id = $this->model_referral_referral->lockBalanceForPayout($this->referral->getEmail(), $amount);
        if($payout_id) {
            $this->_setFlash($this->language->get("flash_payout_successful"));

            // notify admin
            $this->model_referral_referral->sendMail(
                $this->config->get("config_dev_email"),
                "New payout request",
                sprintf("User with username: %s requested a payout.", $this->referral->getEmail())
            );

            // notify staff
            $this->model_referral_referral->sendMail(
                $this->config->get("config_email"),
                sprintf("New payout request - id %s", $payout_id),
                $this->model_referral_referral->getMailContent(
                    "referral_payout_request_admin",
                    "en",
                    array(
                        "{email}" => $this->referral->getEmail(),
                        "{payout_id}" => $payout_id,
                        "{amount}" => $amount,
                        "{review_link}" => $this->url->link("", "", "SSL"), // todo: fix link
                    )
                ),
                true
            );

            $language = $this->referral->getLanguage();
            if ($language == "en") {
                $subject = sprintf("Payout request confirmation - request number %s", $payout_id);
            } else {
                $subject = sprintf("Payout request confirmation - request number %s", $payout_id); // todo: fix subj
            }

            // notify user
            $this->model_referral_referral->sendMail(
                $this->referral->getEmail(),
                $subject,
                $this->model_referral_referral->getMailContent(
                    "referral_payout_request_user",
                    $language, // todo: add template for another language
                    array(
                        "{request_id}" => $payout_id,
                        "{amount}" => $amount
                    )
                ),
                true
            );

        } else {
            $this->_setFlash($this->language->get("flash_payout_error"), "attention");
        }

        $this->redirect($this->url->link('information/referral/account', '', 'SSL'));
    }

    public function account() {
        if (!$this->referral->isLogged()) {
            $this->_setFlash($this->language->get("flash_please_login"), "attention");
            $this->redirect($this->url->link('information/referral', '', 'SSL'));
        }

        $this->document->addScript("catalog/view/javascript/sharebutton/share-button.js");
        $this->document->addStyle("catalog/view/javascript/sharebutton/share-button.css");

        $this->data["referral_text_title"] = $this->config->get("config_referral_share_title_" . $this->session->data["language"]);
        $this->data["referral_text_description"] = $this->config->get("config_referral_share_content_" . $this->session->data["language"]);
        $this->data["referral_text_image"] = $this->config->get("config_referral_share_image_url_" . $this->session->data["language"]);

        $this->data["referral_percent"] = $this->config->get("config_referral_percent");
        $this->data["referral_min_payout"] = $this->config->get("config_referral_min_payout");
        $this->data["referral_add_lock_time"] = $this->config->get("config_referral_add_lock_time");
        $this->data["referral_email"] = $this->referral->getEmail();
        $this->data["referral_balance"] = $this->referral->getBalance();
        $this->data["referral_balance_locked"] = $this->referral->getBalanceLocked();
        $this->data["referral_link"] = $this->url->link("", "", "SSL") . "?r=" . $this->referral->getRefCode();
        $this->data["referral_payout_enabled"] = $this->referral->getBalance() > $this->config->get("config_referral_min_payout");
        $this->data["referral_history"] = $this->model_referral_referral->getHistory($this->referral->getRefId());

        $this->setTemplate('information/referral_account');
        $this->response->setOutput($this->render());
    }

    public function login_register() {
        $response = array(
            "error" => false,
            "already_registered" => false,
            "message" => ""
        );

        if (isset($this->request->post["email"])) {
            $email = trim($this->request->post["email"]);

            if (!$this->model_referral_referral->getReferralDataByEmail($email)) {
                // .. register and send mail
                $this->model_referral_referral->addReferral($email, $this->session->data["language"]);
                $referral = $this->model_referral_referral->getReferralDataByEmail($email);
                $code = $referral["ref_code"];
                $this->model_referral_referral->sendMail(
                    $email,
                    $this->language->get("email_sub_confirm"),
                    $this->model_referral_referral
                        ->getMailContent(
                            "referral_confirm",
                            $this->session->data["language"],
                            array(
                                "{verify_url}" => $this->url->link("information/referral/verify", "c=" . $code, "SSL")
                            )
                        )
                );

                $response["message"] = $this->language->get("js_registered");
            } else {
                setcookie("referral_already_registered", true);
                $response["already_registered"] = true;
                $response["message"] = $this->language->get("js_already_registered");
            }
        } else {
            $response["error"] = true;
            $response["message"] = $this->language->get("js_login_error");
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($response));
    }
}

?>