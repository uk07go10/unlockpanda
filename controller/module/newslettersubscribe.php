<?php

class ControllerModuleNewslettersubscribe extends Controller
{
    private $error = array();

    public function index($setting)
    {

        /*if ($setting['type']=='normal'){
               $this->loadmodule();}

        if ($setting['type']=='popup'){
        $this->loadmodulepopup();}

        if ($setting['type']=='thickbox'){
        $this->loadmodulethickbox();}

        if ($setting['type']=='footer'){
        $this->loadmodulefooter();}

        if ($setting['type']=='slideleft'){
        $this->loadmoduleslideleft();}

        if ($setting['type']=='slideright'){
        $this->loadmoduleslideright();}

        if ($setting['type']=='footer2'){
        $this->loadmodulefooter2();}*/

        $this->loadmodule();


        $this->load->model('account/newslettersubscribe');
        //check db
        $this->model_account_newslettersubscribe->check_db();
    }

    public function subscribe()
    {

        if ($this->config->get('newslettersubscribe_thickbox')) {
            $prefix_eval = "";
        } else {
            $prefix_eval = "";
        }

        $this->language->load('module/newslettersubscribe');
        $this->load->model('account/newslettersubscribe');

        if (isset($this->request->post['subscribe_email']) and filter_var($this->request->post['subscribe_email'], FILTER_VALIDATE_EMAIL)) {

            if ($this->config->get('newslettersubscribe_registered') and $this->model_account_newslettersubscribe->checkRegisteredUser($this->request->post)) {
                $this->model_account_newslettersubscribe->UpdateRegisterUsers($this->request->post, 1);
                echo('$("' . $prefix_eval . ' #subscribe_result").html("' . $this->language->get('subscribe') . '");$("' . $prefix_eval . ' #subscribe")[0].reset();');


                if ($this->config->get('newslettersubscribe_mailchimp_msync')) {
                    if ($this->config->get('newslettersubscribe_mailchimp_mwelcome')) {
                        $mwelcome = "true";
                    } else {
                        $mwelcome = "false";
                    }
                    if ($this->config->get('newslettersubscribe_mailchimp_optin')) {

                        $double_optin = "true";
                    } else {
                        $double_optin = "false";
                    }
                    $name = $this->request->post['subscribe_name'];
                    $email_address = $this->request->post['subscribe_email'];
                    $apikey = $this->config->get('newslettersubscribe_mailchimp_api');
                    $id = $this->config->get('newslettersubscribe_mailchimp_fid');


                    if (version_compare(PHP_VERSION, '6.0.0') >= 0) {

                        $fname = strstr($name, ' ', true);
                        $lname2 = strstr($name, ' ');
                        $lname = substr($lname2, 1);
                    } else {
                        $fname = substr($name, 0, strpos($name, ' '));
                        $lname = substr($name, strpos($name, ' '));
                    }


                    $dc2 = strstr($apikey, '-');
                    $dc = substr($dc2, 1);

                    if ($fname == null) {

                        $fname = $name;

                    }


                    $url = 'http://' . $dc . '.api.mailchimp.com/1.3/?method=listSubscribe&apikey=' . $apikey . '&id=' . $id . '&email_address=' . $email_address . '&merge_vars[FNAME]=' . $fname . '&merge_vars[LNAME]=' . $lname . '&double_optin=' . $double_optin . '&send_welcome=' . $mwelcome . '&merge_vars[MC_LANGUAGE]=' . (isset($this->session->data["language"]) ? $this->session->data["language"] : "en");


                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($ch, CURLOPT_ENCODING, "");
                    curl_setopt($ch, CURLOPT_USERAGENT, 'User-Agent: MCAPI/1.3');
                    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
                    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, null);
                    $data = curl_exec($ch);
                    curl_close($ch);
                }


            } else if (!$this->model_account_newslettersubscribe->checkmailid($this->request->post)) {
                $this->model_account_newslettersubscribe->subscribe($this->request->post);
                echo('$("' . $prefix_eval . ' #subscribe_result").html("' . $this->language->get('subscribe') . '");$("' . $prefix_eval . ' #subscribe")[0].reset();');


                if ($this->config->get('newslettersubscribe_mailchimp_msync')) {
                    if ($this->config->get('newslettersubscribe_mailchimp_mwelcome')) {
                        $mwelcome = "true";
                    } else {
                        $mwelcome = "false";
                    }
                    if ($this->config->get('newslettersubscribe_mailchimp_optin')) {

                        $double_optin = "true";
                    } else {
                        $double_optin = "false";
                    }
                    $name = $this->request->post['subscribe_name'];
                    $email_address = $this->request->post['subscribe_email'];
                    $apikey = $this->config->get('newslettersubscribe_mailchimp_api');

                    $id = $this->config->get('newslettersubscribe_mailchimp_fid');

                    if (version_compare(PHP_VERSION, '6.0.0') >= 0) {

                        $fname = strstr($name, ' ', true);
                        $lname2 = strstr($name, ' ');
                        $lname = substr($lname2, 1);
                    } else {
                        $fname = substr($name, 0, strpos($name, ' '));
                        $lname = substr($name, strpos($name, ' '));
                    }


                    $dc2 = strstr($apikey, '-');
                    $dc = substr($dc2, 1);

                    if ($fname == null) {

                        $fname = $name;

                    }

                    $url = 'http://' . $dc . '.api.mailchimp.com/1.3/?method=listSubscribe&apikey=' . $apikey . '&id=' . $id . '&email_address=' . $email_address . '&merge_vars[FNAME]=' . $fname . '&merge_vars[LNAME]=' . $lname . '&double_optin=' . $double_optin . '&send_welcome=' . $mwelcome . '&merge_vars[MC_LANGUAGE]=' . (isset($this->session->data["language"]) ? $this->session->data["language"] : "en");


                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($ch, CURLOPT_ENCODING, "");
                    curl_setopt($ch, CURLOPT_USERAGENT, 'User-Agent: MCAPI/1.3');
                    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
                    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, null);
                    $data = curl_exec($ch);
                    curl_close($ch);
                }

                if ($this->config->get('newslettersubscribe_mail_status')) {

                    $subject = $this->language->get('mail_subject');

                    $message = '<table width="60%" cellpadding="2"  cellspacing="1" border="0">
				  	         <tr>
							   <td> Email Id </td>
							   <td> ' . $this->request->post['subscribe_email'] . ' </td>
							 </tr>
				  	         <tr>
							   <td> Name  </td>
							   <td> ' . $this->request->post['subscribe_name'] . ' </td>
							 </tr>';
                    if (isset($this->request->post['option1'])) {
                        $message .= '<tr> <td>' . $this->config->get('newslettersubscribe_option_field1') . '</td> <td>' . $this->request->post['option1'] . '</td> </tr>';
                    }
                    if (isset($this->request->post['option2'])) {
                        $message .= '<tr> <td>' . $this->config->get('newslettersubscribe_option_field2') . '</td> <td>' . $this->request->post['option2'] . '</td> </tr>';
                    }
                    if (isset($this->request->post['option3'])) {
                        $message .= '<tr> <td>' . $this->config->get('newslettersubscribe_option_field3') . '</td> <td>' . $this->request->post['option3'] . '</td> </tr>';
                    }
                    if (isset($this->request->post['option4'])) {
                        $message .= '<tr> <td>' . $this->config->get('newslettersubscribe_option_field4') . '</td> <td>' . $this->request->post['option4'] . '</td> </tr>';
                    }
                    if (isset($this->request->post['option5'])) {
                        $message .= '<tr> <td>' . $this->config->get('newslettersubscribe_option_field5') . '</td> <td>' . $this->request->post['option5'] . '</td> </tr>';
                    }
                    if (isset($this->request->post['option6'])) {
                        $message .= '<tr> <td>' . $this->config->get('newslettersubscribe_option_field6') . '</td> <td>' . $this->request->post['option6'] . '</td> </tr>';
                    }
                    $message .= '</table>';


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
                    $mail->setSender($this->config->get('config_name'));
                    $mail->setSubject($subject);
                    $mail->setHtml($message);
                    $mail->send();
                }

            } else {
                echo('$("' . $prefix_eval . ' #subscribe_result").html("<span class=\"error\">' . $this->language->get('alreadyexist') . '</span>");$("' . $prefix_eval . ' #subscribe")[0].reset();');
            }

        } else {
            echo('$("' . $prefix_eval . ' #subscribe_result").html("<span class=\"error\">' . $this->language->get('error_invalid') . '</span>")');
        }
    }

    public function subscribefancybox()
    {

        if ($this->config->get('newslettersubscribe_thickbox')) {
            $prefix_eval = "";
        } else {
            $prefix_eval = "";
        }

        $this->language->load('module/newslettersubscribe');
        $this->load->model('account/newslettersubscribe');

        if (isset($this->request->post['subscribe_email']) and filter_var($this->request->post['subscribe_email'], FILTER_VALIDATE_EMAIL)) {

            if ($this->config->get('newslettersubscribe_registered') and $this->model_account_newslettersubscribe->checkRegisteredUser($this->request->post)) {
                $this->model_account_newslettersubscribe->UpdateRegisterUsers($this->request->post, 1);
                $out = array('message' => $this->language->get('subscribe'), 'type' => 'success');

                if ($this->config->get('newslettersubscribe_mailchimp_msync')) {
                    if ($this->config->get('newslettersubscribe_mailchimp_mwelcome')) {
                        $mwelcome = "true";
                    } else {
                        $mwelcome = "false";
                    }
                    if ($this->config->get('newslettersubscribe_mailchimp_optin')) {

                        $double_optin = "true";
                    } else {
                        $double_optin = "false";
                    }
                    $name = $this->request->post['subscribe_name'];
                    $email_address = $this->request->post['subscribe_email'];
                    $apikey = $this->config->get('newslettersubscribe_mailchimp_api');
                    $id = $this->config->get('newslettersubscribe_mailchimp_fid');


                    if (version_compare(PHP_VERSION, '6.0.0') >= 0) {

                        $fname = strstr($name, ' ', true);
                        $lname2 = strstr($name, ' ');
                        $lname = substr($lname2, 1);
                    } else {
                        $fname = substr($name, 0, strpos($name, ' '));
                        $lname = substr($name, strpos($name, ' '));
                    }


                    $dc2 = strstr($apikey, '-');
                    $dc = substr($dc2, 1);

                    if ($fname == null) {

                        $fname = $name;

                    }

                    $url = 'http://' . $dc . '.api.mailchimp.com/1.3/?method=listSubscribe&apikey=' . $apikey . '&id=' . $id . '&email_address=' . $email_address . '&merge_vars[FNAME]=' . $fname . '&merge_vars[LNAME]=' . $lname . '&double_optin=' . $double_optin . '&send_welcome=' . $mwelcome . '';


                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($ch, CURLOPT_ENCODING, "");
                    curl_setopt($ch, CURLOPT_USERAGENT, 'User-Agent: MCAPI/1.3');
                    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
                    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, null);
                    $data = curl_exec($ch);
                    curl_close($ch);
                }

            } else if (!$this->model_account_newslettersubscribe->checkmailid($this->request->post)) {
                $this->model_account_newslettersubscribe->subscribe($this->request->post);
                $out = array('message' => $this->language->get('subscribe'), 'type' => 'success');

                if ($this->config->get('newslettersubscribe_mailchimp_msync')) {
                    if ($this->config->get('newslettersubscribe_mailchimp_mwelcome')) {
                        $mwelcome = "true";
                    } else {
                        $mwelcome = "false";
                    }
                    if ($this->config->get('newslettersubscribe_mailchimp_optin')) {

                        $double_optin = "true";
                    } else {
                        $double_optin = "false";
                    }
                    $name = $this->request->post['subscribe_name'];
                    $email_address = $this->request->post['subscribe_email'];
                    $apikey = $this->config->get('newslettersubscribe_mailchimp_api');
                    $id = $this->config->get('newslettersubscribe_mailchimp_fid');
                    if (version_compare(PHP_VERSION, '6.0.0') >= 0) {

                        $fname = strstr($name, ' ', true);
                        $lname2 = strstr($name, ' ');
                        $lname = substr($lname2, 1);
                    } else {
                        $fname = substr($name, 0, strpos($name, ' '));
                        $lname = substr($name, strpos($name, ' '));
                    }

                    $dc2 = strstr($apikey, '-');
                    $dc = substr($dc2, 1);

                    if ($fname == null) {

                        $fname = $name;

                    }
                    $url = 'http://' . $dc . '.api.mailchimp.com/1.3/?method=listSubscribe&apikey=' . $apikey . '&id=' . $id . '&email_address=' . $email_address . '&merge_vars[FNAME]=' . $fname . '&merge_vars[LNAME]=' . $lname . '&double_optin=' . $double_optin . '&send_welcome=' . $mwelcome . '';


                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($ch, CURLOPT_ENCODING, "");
                    curl_setopt($ch, CURLOPT_USERAGENT, 'User-Agent: MCAPI/1.3');
                    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
                    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, null);
                    $data = curl_exec($ch);
                    curl_close($ch);
                }

                if ($this->config->get('newslettersubscribe_mail_status')) {


                    $subject = $this->language->get('mail_subject');

                    $message = '<table width="60%" cellpadding="2"  cellspacing="1" border="0">
				  	         <tr>
							   <td> Email Id </td>
							   <td> ' . $this->request->post['subscribe_email'] . ' </td>
							 </tr>
				  	         <tr>
							   <td> Name  </td>
							   <td> ' . $this->request->post['subscribe_name'] . ' </td>
							 </tr>';
                    if (isset($this->request->post['option1'])) {
                        $message .= '<tr> <td>' . $this->config->get('newslettersubscribe_option_field1') . '</td> <td>' . $this->request->post['option1'] . '</td> </tr>';
                    }
                    if (isset($this->request->post['option2'])) {
                        $message .= '<tr> <td>' . $this->config->get('newslettersubscribe_option_field2') . '</td> <td>' . $this->request->post['option2'] . '</td> </tr>';
                    }
                    if (isset($this->request->post['option3'])) {
                        $message .= '<tr> <td>' . $this->config->get('newslettersubscribe_option_field3') . '</td> <td>' . $this->request->post['option3'] . '</td> </tr>';
                    }
                    if (isset($this->request->post['option4'])) {
                        $message .= '<tr> <td>' . $this->config->get('newslettersubscribe_option_field4') . '</td> <td>' . $this->request->post['option4'] . '</td> </tr>';
                    }
                    if (isset($this->request->post['option5'])) {
                        $message .= '<tr> <td>' . $this->config->get('newslettersubscribe_option_field5') . '</td> <td>' . $this->request->post['option5'] . '</td> </tr>';
                    }
                    if (isset($this->request->post['option6'])) {
                        $message .= '<tr> <td>' . $this->config->get('newslettersubscribe_option_field6') . '</td> <td>' . $this->request->post['option6'] . '</td> </tr>';
                    }
                    $message .= '</table>';


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
                    $mail->setSender($this->config->get('config_name'));
                    $mail->setSubject($subject);
                    $mail->setHtml($message);
                    $mail->send();
                }

            } else {
                $out = array('message' => $this->language->get('alreadyexist'), 'type' => 'success');

            }

        } else {
            $out = array('message' => $this->language->get('error_invalid'), 'type' => 'warning');


        }
        $this->response->addHeader('Content-type: application/json');
        $this->response->setOutput($out ? json_encode($out) : '');
    }

    public function unsubscribe()
    {

        if ($this->config->get('newslettersubscribe_thickbox')) {
            $prefix_eval = "#TB_ajaxContent ";
        } else {
            $prefix_eval = "";
        }

        $this->language->load('module/newslettersubscribe');

        $this->load->model('account/newslettersubscribe');

        if (isset($this->request->post['subscribe_email']) and filter_var($this->request->post['subscribe_email'], FILTER_VALIDATE_EMAIL)) {

            if ($this->config->get('newslettersubscribe_registered') and $this->model_account_newslettersubscribe->checkRegisteredUser($this->request->post)) {

                $this->model_account_newslettersubscribe->UpdateRegisterUsers($this->request->post, 0);

                echo('$("' . $prefix_eval . ' #subscribe_result").html("' . $this->language->get('unsubscribe') . '");$("' . $prefix_eval . ' #subscribe")[0].reset();');


            } else if (!$this->model_account_newslettersubscribe->checkmailid($this->request->post)) {

                echo('$("' . $prefix_eval . ' #subscribe_result").html("' . $this->language->get('notexist') . '");$("' . $prefix_eval . ' #subscribe")[0].reset();');

            } else {

                if ($this->config->get('option_unsubscribe')) {
                    $this->model_account_newslettersubscribe->unsubscribe($this->request->post);
                    echo('$("' . $prefix_eval . ' #subscribe_result").html("' . $this->language->get('unsubscribe') . '");$("' . $prefix_eval . ' #subscribe")[0].reset();');
                }
            }

        } else {
            echo('$("' . $prefix_eval . ' #subscribe_result").html("<span class=\"error\">' . $this->language->get('error_invalid') . '</span>")');
        }
    }


    public function subscribefooter()
    {

        if ($this->config->get('newslettersubscribe_thickbox')) {
            $prefix_eval = "";
        } else {
            $prefix_eval = "";
        }

        $this->language->load('module/newslettersubscribe');
        $this->load->model('account/newslettersubscribe');

        if (isset($this->request->post['subscribe_email']) and filter_var($this->request->post['subscribe_email'], FILTER_VALIDATE_EMAIL)) {

            if ($this->config->get('newslettersubscribe_registered') and $this->model_account_newslettersubscribe->checkRegisteredUser($this->request->post)) {
                $this->model_account_newslettersubscribe->UpdateRegisterUsers($this->request->post, 1);
                echo('$("' . $prefix_eval . ' #subscribef_result").html("' . $this->language->get('subscribe') . '");$("' . $prefix_eval . ' #subscribe")[0].reset();');

                if ($this->config->get('newslettersubscribe_mailchimp_msync')) {
                    if ($this->config->get('newslettersubscribe_mailchimp_mwelcome')) {
                        $mwelcome = "true";
                    } else {
                        $mwelcome = "false";
                    }
                    if ($this->config->get('newslettersubscribe_mailchimp_optin')) {

                        $double_optin = "true";
                    } else {
                        $double_optin = "false";
                    }
                    $name = $this->request->post['subscribe_name'];
                    $email_address = $this->request->post['subscribe_email'];
                    $apikey = $this->config->get('newslettersubscribe_mailchimp_api');
                    $id = $this->config->get('newslettersubscribe_mailchimp_fid');


                    if (version_compare(PHP_VERSION, '6.0.0') >= 0) {

                        $fname = strstr($name, ' ', true);
                        $lname2 = strstr($name, ' ');
                        $lname = substr($lname2, 1);
                    } else {
                        $fname = substr($name, 0, strpos($name, ' '));
                        $lname = substr($name, strpos($name, ' '));
                    }

                    $dc2 = strstr($apikey, '-');
                    $dc = substr($dc2, 1);
                    if ($fname == null) {

                        $fname = $name;

                    }

                    $url = 'http://' . $dc . '.api.mailchimp.com/1.3/?method=listSubscribe&apikey=' . $apikey . '&id=' . $id . '&email_address=' . $email_address . '&merge_vars[FNAME]=' . $fname . '&merge_vars[LNAME]=' . $lname . '&double_optin=' . $double_optin . '&send_welcome=' . $mwelcome . '';


                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($ch, CURLOPT_ENCODING, "");
                    curl_setopt($ch, CURLOPT_USERAGENT, 'User-Agent: MCAPI/1.3');
                    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
                    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, null);
                    $data = curl_exec($ch);
                    curl_close($ch);
                }
            } else if (!$this->model_account_newslettersubscribe->checkmailid($this->request->post)) {
                $this->model_account_newslettersubscribe->subscribe($this->request->post);
                echo('$("' . $prefix_eval . ' #subscribef_result").html("' . $this->language->get('subscribe') . '");$("' . $prefix_eval . ' #subscribe")[0].reset();');

                if ($this->config->get('newslettersubscribe_mailchimp_msync')) {
                    if ($this->config->get('newslettersubscribe_mailchimp_mwelcome')) {
                        $mwelcome = "true";
                    } else {
                        $mwelcome = "false";
                    }
                    if ($this->config->get('newslettersubscribe_mailchimp_optin')) {

                        $double_optin = "true";
                    } else {
                        $double_optin = "false";
                    }
                    $name = $this->request->post['subscribe_name'];
                    $email_address = $this->request->post['subscribe_email'];
                    $apikey = $this->config->get('newslettersubscribe_mailchimp_api');
                    $id = $this->config->get('newslettersubscribe_mailchimp_fid');
                    if (version_compare(PHP_VERSION, '6.0.0') >= 0) {

                        $fname = strstr($name, ' ', true);
                        $lname2 = strstr($name, ' ');
                        $lname = substr($lname2, 1);
                    } else {
                        $fname = substr($name, 0, strpos($name, ' '));
                        $lname = substr($name, strpos($name, ' '));
                    }
                    $dc2 = strstr($apikey, '-');
                    $dc = substr($dc2, 1);
                    if ($fname == null) {

                        $fname = $name;

                    }

                    $url = 'http://' . $dc . '.api.mailchimp.com/1.3/?method=listSubscribe&apikey=' . $apikey . '&id=' . $id . '&email_address=' . $email_address . '&merge_vars[FNAME]=' . $fname . '&merge_vars[LNAME]=' . $lname . '&double_optin=' . $double_optin . '&send_welcome=' . $mwelcome . '';


                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($ch, CURLOPT_ENCODING, "");
                    curl_setopt($ch, CURLOPT_USERAGENT, 'User-Agent: MCAPI/1.3');
                    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
                    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, null);
                    $data = curl_exec($ch);
                    curl_close($ch);
                }


                if ($this->config->get('newslettersubscribe_mail_status')) {


                    $subject = $this->language->get('mail_subject');

                    $message = '<table width="60%" cellpadding="2"  cellspacing="1" border="0">
				  	         <tr>
							   <td> Email Id </td>
							   <td> ' . $this->request->post['subscribe_email'] . ' </td>
							 </tr>
				  	         <tr>
							   <td> Name  </td>
							   <td> ' . $this->request->post['subscribe_name'] . ' </td>
							 </tr>';
                    if (isset($this->request->post['option1'])) {
                        $message .= '<tr> <td>' . $this->config->get('newslettersubscribe_option_field1') . '</td> <td>' . $this->request->post['option1'] . '</td> </tr>';
                    }
                    if (isset($this->request->post['option2'])) {
                        $message .= '<tr> <td>' . $this->config->get('newslettersubscribe_option_field2') . '</td> <td>' . $this->request->post['option2'] . '</td> </tr>';
                    }
                    if (isset($this->request->post['option3'])) {
                        $message .= '<tr> <td>' . $this->config->get('newslettersubscribe_option_field3') . '</td> <td>' . $this->request->post['option3'] . '</td> </tr>';
                    }
                    if (isset($this->request->post['option4'])) {
                        $message .= '<tr> <td>' . $this->config->get('newslettersubscribe_option_field4') . '</td> <td>' . $this->request->post['option4'] . '</td> </tr>';
                    }
                    if (isset($this->request->post['option5'])) {
                        $message .= '<tr> <td>' . $this->config->get('newslettersubscribe_option_field5') . '</td> <td>' . $this->request->post['option5'] . '</td> </tr>';
                    }
                    if (isset($this->request->post['option6'])) {
                        $message .= '<tr> <td>' . $this->config->get('newslettersubscribe_option_field6') . '</td> <td>' . $this->request->post['option6'] . '</td> </tr>';
                    }
                    $message .= '</table>';


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
                    $mail->setSender($this->config->get('config_name'));
                    $mail->setSubject($subject);
                    $mail->setHtml($message);
                    $mail->send();
                }

            } else {
                echo('$("' . $prefix_eval . ' #subscribef_result").html("<span class=\"error\">' . $this->language->get('alreadyexist') . '</span>");$("' . $prefix_eval . ' #subscribe")[0].reset();');
            }

        } else {
            echo('$("' . $prefix_eval . ' #subscribef_result").html("<span class=\"error\">' . $this->language->get('error_invalid') . '</span>")');
        }
    }

    public function unsubscribefooter()
    {

        if ($this->config->get('newslettersubscribe_thickbox')) {
            $prefix_eval = "#TB_ajaxContent ";
        } else {
            $prefix_eval = "";
        }

        $this->language->load('module/newslettersubscribe');

        $this->load->model('account/newslettersubscribe');

        if (isset($this->request->post['subscribe_email']) and filter_var($this->request->post['subscribe_email'], FILTER_VALIDATE_EMAIL)) {

            if ($this->config->get('newslettersubscribe_registered') and $this->model_account_newslettersubscribe->checkRegisteredUser($this->request->post)) {

                $this->model_account_newslettersubscribe->UpdateRegisterUsers($this->request->post, 0);

                echo('$("' . $prefix_eval . ' #subscribef_result").html("' . $this->language->get('unsubscribe') . '");$("' . $prefix_eval . ' #subscribe")[0].reset();');


            } else if (!$this->model_account_newslettersubscribe->checkmailid($this->request->post)) {

                echo('$("' . $prefix_eval . ' #subscribef_result").html("' . $this->language->get('notexist') . '");$("' . $prefix_eval . ' #subscribe")[0].reset();');

            } else {

                if ($this->config->get('option_unsubscribe')) {
                    $this->model_account_newslettersubscribe->unsubscribe($this->request->post);
                    echo('$("' . $prefix_eval . ' #subscribef_result").html("' . $this->language->get('unsubscribe') . '");$("' . $prefix_eval . ' #subscribe")[0].reset();');
                }
            }

        } else {
            echo('$("' . $prefix_eval . ' #subscribef_result").html("<span class=\"error\">' . $this->language->get('error_invalid') . '</span>")');
        }
    }


    protected function loadmodule()
    {
        $this->language->load('module/newslettersubscribe');

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['entry_name'] = $this->language->get('entry_name');
        $this->data['entry_email'] = $this->language->get('entry_email');

        $this->data['entry_button'] = $this->language->get('entry_button');

        $this->data['entry_unbutton'] = $this->language->get('entry_unbutton');

        $this->data['option_unsubscribe'] = $this->config->get('option_unsubscribe');

        $this->data['option_fields'] = $this->config->get('newslettersubscribe_option_field');

        $this->data['option_fields1'] = $this->config->get('newslettersubscribe_option_field1');
        $this->data['option_fields2'] = $this->config->get('newslettersubscribe_option_field2');
        $this->data['option_fields3'] = $this->config->get('newslettersubscribe_option_field3');
        $this->data['option_fields4'] = $this->config->get('newslettersubscribe_option_field4');
        $this->data['option_fields5'] = $this->config->get('newslettersubscribe_option_field5');
        $this->data['option_fields6'] = $this->config->get('newslettersubscribe_option_field6');


        $this->data['text_subscribe'] = $this->language->get('text_subscribe');


        $this->id = 'newslettersubscribe';

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/newslettersubscribe.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/newslettersubscribe.tpl';
        } else {
            $this->template = 'default/template/module/newslettersubscribe.tpl';
        }

        $this->render();
    }

    protected function loadmodulepopup()
    {
        $this->language->load('module/newslettersubscribe');

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['entry_name'] = $this->language->get('entry_name');
        $this->data['entry_email'] = $this->language->get('entry_email');

        $this->data['entry_button'] = $this->language->get('entry_button');

        $this->data['entry_unbutton'] = $this->language->get('entry_unbutton');

        $this->data['option_unsubscribe'] = $this->config->get('option_unsubscribe');

        $this->data['option_fields'] = $this->config->get('newslettersubscribe_option_field');

        $this->data['option_fields1'] = $this->config->get('newslettersubscribe_option_field1');
        $this->data['option_fields2'] = $this->config->get('newslettersubscribe_option_field2');
        $this->data['option_fields3'] = $this->config->get('newslettersubscribe_option_field3');
        $this->data['option_fields4'] = $this->config->get('newslettersubscribe_option_field4');
        $this->data['option_fields5'] = $this->config->get('newslettersubscribe_option_field5');
        $this->data['option_fields6'] = $this->config->get('newslettersubscribe_option_field6');
        $this->data['popupdisplay'] = $this->config->get('newslettersubscribe_popupdisplay');
        $this->data['popupdelay'] = $this->config->get('newslettersubscribe_popupdelay');
        $this->data['popupheaderimage'] = $this->config->get('newslettersubscribe_popupheaderimage');
        $this->data['subscribebutton'] = $this->config->get('newslettersubscribe_subscribebutton');
        $this->data['popupline1'] = $this->config->get('newslettersubscribe_popupline1');
        $this->data['popupline2'] = $this->config->get('newslettersubscribe_popupline2');
        $this->data['force'] = $this->config->get('newslettersubscribe_force');

        $this->data['subscribemessage'] = $this->language->get('subscribe');
        $this->data['home'] = $this->url->link('module/newslettersubscribe/subscribefancybox');
        $route = 'module/newslettersubscribe/subscribefancybox';


        $this->data['thickbox'] = $this->config->get('newslettersubscribe_thickbox');

        $this->data['text_subscribe'] = $this->language->get('text_subscribe');


        $this->id = 'newslettersubscribe';


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/newslettersubscribepopup.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/newslettersubscribepopup.tpl';
        } else {
            $this->template = 'default/template/module/newslettersubscribepopup.tpl';
        }

        $this->render();
    }

    protected function loadmodulethickbox()
    {
        $this->language->load('module/newslettersubscribe');

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['entry_name'] = $this->language->get('entry_name');
        $this->data['entry_email'] = $this->language->get('entry_email');

        $this->data['entry_button'] = $this->language->get('entry_button');

        $this->data['entry_unbutton'] = $this->language->get('entry_unbutton');

        $this->data['option_unsubscribe'] = $this->config->get('option_unsubscribe');

        $this->data['option_fields'] = $this->config->get('newslettersubscribe_option_field');

        $this->data['option_fields1'] = $this->config->get('newslettersubscribe_option_field1');
        $this->data['option_fields2'] = $this->config->get('newslettersubscribe_option_field2');
        $this->data['option_fields3'] = $this->config->get('newslettersubscribe_option_field3');
        $this->data['option_fields4'] = $this->config->get('newslettersubscribe_option_field4');
        $this->data['option_fields5'] = $this->config->get('newslettersubscribe_option_field5');
        $this->data['option_fields6'] = $this->config->get('newslettersubscribe_option_field6');
        $this->data['popupdisplay'] = $this->config->get('newslettersubscribe_popupdisplay');
        $this->data['popupdelay'] = $this->config->get('newslettersubscribe_popupdelay');
        $this->data['popupheaderimage'] = $this->config->get('newslettersubscribe_popupheaderimage');
        $this->data['subscribebutton'] = $this->config->get('newslettersubscribe_subscribebutton');
        $this->data['popupline1'] = $this->config->get('newslettersubscribe_popupline1');
        $this->data['popupline2'] = $this->config->get('newslettersubscribe_popupline2');

        $this->data['subscribemessage'] = $this->language->get('subscribe');
        $this->data['home'] = $this->url->link('module/newslettersubscribe/subscribefancybox');
        $route = 'module/newslettersubscribe/subscribefancybox';

        $this->data['thickbox'] = $this->config->get('newslettersubscribe_thickbox');

        $this->data['text_subscribe'] = $this->language->get('text_subscribe');


        $this->id = 'newslettersubscribe';

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/newslettersubscribethickbox.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/newslettersubscribethickbox.tpl';
        } else {
            $this->template = 'default/template/module/newslettersubscribethickbox.tpl';
        }

        $this->render();
    }


    protected function loadmoduleslideright()
    {
        $this->language->load('module/newslettersubscribe');

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['entry_name'] = $this->language->get('entry_name');
        $this->data['entry_email'] = $this->language->get('entry_email');

        $this->data['entry_button'] = $this->language->get('entry_button');

        $this->data['entry_unbutton'] = $this->language->get('entry_unbutton');

        $this->data['option_unsubscribe'] = $this->config->get('option_unsubscribe');

        $this->data['option_fields'] = $this->config->get('newslettersubscribe_option_field');

        $this->data['option_fields1'] = $this->config->get('newslettersubscribe_option_field1');
        $this->data['option_fields2'] = $this->config->get('newslettersubscribe_option_field2');
        $this->data['option_fields3'] = $this->config->get('newslettersubscribe_option_field3');
        $this->data['option_fields4'] = $this->config->get('newslettersubscribe_option_field4');
        $this->data['option_fields5'] = $this->config->get('newslettersubscribe_option_field5');
        $this->data['option_fields6'] = $this->config->get('newslettersubscribe_option_field6');
        $this->data['popupdisplay'] = $this->config->get('newslettersubscribe_popupdisplay');
        $this->data['popupdelay'] = $this->config->get('newslettersubscribe_popupdelay');
        $this->data['popupheaderimage'] = $this->config->get('newslettersubscribe_popupheaderimage');
        $this->data['subscribebutton'] = $this->config->get('newslettersubscribe_subscribebutton');
        $this->data['popupline1'] = $this->config->get('newslettersubscribe_popupline1');
        $this->data['popupline2'] = $this->config->get('newslettersubscribe_popupline2');

        $this->data['subscribemessage'] = $this->language->get('subscribe');

        $this->data['thickbox'] = $this->config->get('newslettersubscribe_thickbox');

        $this->data['text_subscribe'] = $this->language->get('text_subscribe');


        $this->id = 'newslettersubscribe';

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/newslettersubscribeslideright.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/newslettersubscribeslideright.tpl';
        } else {
            $this->template = 'default/template/module/newslettersubscribeslideright.tpl';
        }

        $this->render();
    }

    protected function loadmoduleslideleft()
    {
        $this->language->load('module/newslettersubscribe');

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['entry_name'] = $this->language->get('entry_name');
        $this->data['entry_email'] = $this->language->get('entry_email');

        $this->data['entry_button'] = $this->language->get('entry_button');

        $this->data['entry_unbutton'] = $this->language->get('entry_unbutton');

        $this->data['option_unsubscribe'] = $this->config->get('option_unsubscribe');

        $this->data['option_fields'] = $this->config->get('newslettersubscribe_option_field');

        $this->data['option_fields1'] = $this->config->get('newslettersubscribe_option_field1');
        $this->data['option_fields2'] = $this->config->get('newslettersubscribe_option_field2');
        $this->data['option_fields3'] = $this->config->get('newslettersubscribe_option_field3');
        $this->data['option_fields4'] = $this->config->get('newslettersubscribe_option_field4');
        $this->data['option_fields5'] = $this->config->get('newslettersubscribe_option_field5');
        $this->data['option_fields6'] = $this->config->get('newslettersubscribe_option_field6');
        $this->data['popupdisplay'] = $this->config->get('newslettersubscribe_popupdisplay');
        $this->data['popupdelay'] = $this->config->get('newslettersubscribe_popupdelay');
        $this->data['popupheaderimage'] = $this->config->get('newslettersubscribe_popupheaderimage');
        $this->data['subscribebutton'] = $this->config->get('newslettersubscribe_subscribebutton');
        $this->data['popupline1'] = $this->config->get('newslettersubscribe_popupline1');
        $this->data['popupline2'] = $this->config->get('newslettersubscribe_popupline2');

        $this->data['subscribemessage'] = $this->language->get('subscribe');

        $this->data['thickbox'] = $this->config->get('newslettersubscribe_thickbox');

        $this->data['text_subscribe'] = $this->language->get('text_subscribe');


        $this->id = 'newslettersubscribe';

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/newslettersubscribeslideleft.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/newslettersubscribeslideleft.tpl';
        } else {
            $this->template = 'default/template/module/newslettersubscribeslideleft.tpl';
        }

        $this->render();
    }


    protected function loadmodulefooter()
    {
        $this->language->load('module/newslettersubscribe');

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['entry_name'] = $this->language->get('entry_name');
        $this->data['entry_email'] = $this->language->get('entry_email');

        $this->data['entry_button'] = $this->language->get('entry_button');

        $this->data['entry_unbutton'] = $this->language->get('entry_unbutton');

        $this->data['option_unsubscribe'] = $this->config->get('option_unsubscribe');

        $this->data['option_fields'] = $this->config->get('newslettersubscribe_option_field');

        $this->data['option_fields1'] = $this->config->get('newslettersubscribe_option_field1');
        $this->data['option_fields2'] = $this->config->get('newslettersubscribe_option_field2');
        $this->data['option_fields3'] = $this->config->get('newslettersubscribe_option_field3');
        $this->data['option_fields4'] = $this->config->get('newslettersubscribe_option_field4');
        $this->data['option_fields5'] = $this->config->get('newslettersubscribe_option_field5');
        $this->data['option_fields6'] = $this->config->get('newslettersubscribe_option_field6');


        $this->data['thickbox'] = $this->config->get('newslettersubscribe_thickbox');

        $this->data['text_subscribe'] = $this->language->get('text_subscribe');


        $this->id = 'newslettersubscribe';

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/newslettersubscribefooter.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/newslettersubscribefooter.tpl';
        } else {
            $this->template = 'default/template/module/newslettersubscribefooter.tpl';
        }

        $this->render();
    }


    protected function loadmodulefooter2()
    {
        $this->language->load('module/newslettersubscribe');

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['entry_name'] = $this->language->get('entry_name');
        $this->data['entry_email'] = $this->language->get('entry_email');

        $this->data['entry_button'] = $this->language->get('entry_button');

        $this->data['entry_unbutton'] = $this->language->get('entry_unbutton');
        $this->data['entry_followus'] = $this->language->get('entry_followus');
        $this->data['entry_fblink'] = $this->language->get('entry_fblink');
        $this->data['entry_twitterlink'] = $this->language->get('entry_twitterlink');
        $this->data['entry_googlelink'] = $this->language->get('entry_googlelink');

        $this->data['option_unsubscribe'] = $this->config->get('option_unsubscribe');

        $this->data['option_fields'] = $this->config->get('newslettersubscribe_option_field');

        $this->data['option_fields1'] = $this->config->get('newslettersubscribe_option_field1');
        $this->data['option_fields2'] = $this->config->get('newslettersubscribe_option_field2');
        $this->data['option_fields3'] = $this->config->get('newslettersubscribe_option_field3');
        $this->data['option_fields4'] = $this->config->get('newslettersubscribe_option_field4');
        $this->data['option_fields5'] = $this->config->get('newslettersubscribe_option_field5');
        $this->data['option_fields6'] = $this->config->get('newslettersubscribe_option_field6');


        $this->data['thickbox'] = $this->config->get('newslettersubscribe_thickbox');

        $this->data['text_subscribe'] = $this->language->get('text_subscribe');


        $this->id = 'newslettersubscribe';

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/newslettersubscribefooter2.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/newslettersubscribefooter2.tpl';
        } else {
            $this->template = 'default/template/module/newslettersubscribefooter2.tpl';
        }

        $this->render();
    }

    public function promosubscribe()
    {

        $prefix_eval = "";

        $this->language->load('module/newslettersubscribe');

        $this->load->model('account/newslettersubscribe');

        if (isset($this->request->post['subscribe_email']) and filter_var($this->request->post['subscribe_email'], FILTER_VALIDATE_EMAIL)) {

            echo('$("#subscribe_result").html("Email Confirmed Successfully.");$("#subscribe :input").attr("disabled", true);$("#subscribe")[0].reset();');

            $subject = 'Promotion 1 Confirmation';
            $message = 'This customer has confirmed his registration for Promotion 1.<br />';
            $message .= 'Email ID: ' . $this->request->post['subscribe_email'];

            $mail = new Mail();
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->hostname = $this->config->get('config_smtp_host');
            $mail->username = $this->config->get('config_smtp_username');
            $mail->password = $this->config->get('config_smtp_password');
            $mail->port = $this->config->get('config_smtp_port');
            $mail->timeout = $this->config->get('config_smtp_timeout');
            $mail->setTo($this->config->get("config_promotions_email"));
            //$mail->setTo('ntiersolutions6@gmail.com');
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender('Unlockriver Promotion');
            $mail->setSubject($subject);
            $mail->setHtml($message);
            $mail->send();

//Newsletter Subscribe Start				
            if ($this->config->get('newslettersubscribe_registered') and $this->model_account_newslettersubscribe->checkRegisteredUser($this->request->post)) {
                $this->model_account_newslettersubscribe->UpdateRegisterUsers($this->request->post, 1);


                if ($this->config->get('newslettersubscribe_mailchimp_msync')) {
                    if ($this->config->get('newslettersubscribe_mailchimp_mwelcome')) {
                        $mwelcome = "true";
                    } else {
                        $mwelcome = "false";
                    }
                    if ($this->config->get('newslettersubscribe_mailchimp_optin')) {

                        $double_optin = "true";
                    } else {
                        $double_optin = "false";
                    }
                    $name = $this->request->post['subscribe_name'];
                    $email_address = $this->request->post['subscribe_email'];
                    $apikey = $this->config->get('newslettersubscribe_mailchimp_api');
                    $id = $this->config->get('newslettersubscribe_mailchimp_fid');


                    if (version_compare(PHP_VERSION, '6.0.0') >= 0) {

                        $fname = strstr($name, ' ', true);
                        $lname2 = strstr($name, ' ');
                        $lname = substr($lname2, 1);
                    } else {
                        $fname = substr($name, 0, strpos($name, ' '));
                        $lname = substr($name, strpos($name, ' '));
                    }


                    $dc2 = strstr($apikey, '-');
                    $dc = substr($dc2, 1);

                    if ($fname == null) {

                        $fname = $name;

                    }


                    $url = 'http://' . $dc . '.api.mailchimp.com/1.3/?method=listSubscribe&apikey=' . $apikey . '&id=' . $id . '&email_address=' . $email_address . '&merge_vars[FNAME]=' . $fname . '&merge_vars[LNAME]=' . $lname . '&double_optin=' . $double_optin . '&send_welcome=' . $mwelcome . '';

                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($ch, CURLOPT_ENCODING, "");
                    curl_setopt($ch, CURLOPT_USERAGENT, 'User-Agent: MCAPI/1.3');
                    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
                    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, null);
                    $data = curl_exec($ch);
                    curl_close($ch);
                }


            } else if (!$this->model_account_newslettersubscribe->checkmailid($this->request->post)) {
                $this->model_account_newslettersubscribe->subscribe($this->request->post);


                if ($this->config->get('newslettersubscribe_mailchimp_msync')) {
                    if ($this->config->get('newslettersubscribe_mailchimp_mwelcome')) {
                        $mwelcome = "true";
                    } else {
                        $mwelcome = "false";
                    }
                    if ($this->config->get('newslettersubscribe_mailchimp_optin')) {

                        $double_optin = "true";
                    } else {
                        $double_optin = "false";
                    }
                    $name = isset($this->request->post['subscribe_name']) ? $this->request->post['subscribe_name'] : "" ;
                    $email_address = $this->request->post['subscribe_email'];
                    $apikey = $this->config->get('newslettersubscribe_mailchimp_api');

                    $id = $this->config->get('newslettersubscribe_mailchimp_fid');

                    if (version_compare(PHP_VERSION, '6.0.0') >= 0) {

                        $fname = strstr($name, ' ', true);
                        $lname2 = strstr($name, ' ');
                        $lname = substr($lname2, 1);
                    } else {
                        $fname = substr($name, 0, strpos($name, ' '));
                        $lname = substr($name, strpos($name, ' '));
                    }


                    $dc2 = strstr($apikey, '-');
                    $dc = substr($dc2, 1);

                    if ($fname == null) {

                        $fname = $name;

                    }

                    $url = 'http://' . $dc . '.api.mailchimp.com/1.3/?method=listSubscribe&apikey=' . $apikey . '&id=' . $id . '&email_address=' . $email_address . '&merge_vars[FNAME]=' . $fname . '&merge_vars[LNAME]=' . $lname . '&double_optin=' . $double_optin . '&send_welcome=' . $mwelcome . '';


                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($ch, CURLOPT_ENCODING, "");
                    curl_setopt($ch, CURLOPT_USERAGENT, 'User-Agent: MCAPI/1.3');
                    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
                    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, null);
                    $data = curl_exec($ch);
                    curl_close($ch);
                }
            }
//Newsletter Subscribe End
        } else {
            echo('$("' . $prefix_eval . ' #subscribe_result").html("Invalid Email")');
        }
    }

}

?>