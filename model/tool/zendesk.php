<?php
class ModelToolZendesk extends Model {

    private $_domain;
    private $_email;
    private $_password;

    public function setCredentials($domain, $email, $password) {
        $this->_domain = $domain;
        $this->_email = $email;
        $this->_password = $password;
    }

    public function getActiveMacros() {

        $next_page = 1;
        $next_page_exists = true;
        $macros = array();

        while($next_page_exists) {
            $results =  $this->_makeRequest(sprintf("macros/active.json?page=%s", $next_page));
            foreach($results["macros"] as $macro) {
                $title = $macro["title"];
                $content_raw = array_filter($macro["actions"], function($k) {
                    return $k["field"] == "comment_value" || $k["field"] == "comment_value_html";
                });

                $element = current($content_raw);
                $content = is_array($element["value"]) ? $element["value"][1] : strip_tags(str_replace("<br>", "\n\n", $element["value"]));

                $macros[] = array(
                    "title" => $title,
                    "content" => $content
                );
            }

            if($results["next_page"]) {
                $next_page++;
            } else {
                $next_page_exists = false;
            }
        }

        return $macros;
    }

    private function _makeRequest($url) {
        $c = curl_init($this->_buildURL($url));
        curl_setopt($c, CURLOPT_TIMEOUT, 5);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_USERPWD, $this->_email . ":" . $this->_password);
        $result = curl_exec($c);

        return json_decode($result, true);
    }

    private function _makePOSTRequest($url, $data) {
        $c = curl_init($this->_buildURL($url));
        curl_setopt($c, CURLOPT_TIMEOUT, 5);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_USERPWD, $this->_email . ":" . $this->_password);
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($c, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json"
        ));
        $result = curl_exec($c);

        return json_decode($result, true);
    }

    public function _buildURL($url) {
        return sprintf("https://%s.zendesk.com/api/v2/%s", $this->_domain, $url);
    }

    public function createTicket($name, $email, $subject, $body) {
        $data = array(
            "request" => array(
                "requester" => array("name" => $name, "email" => $email),
                "subject" => $subject,
                "comment" => array(
                    "body" => $body
                )
            )
        );

        return $this->_makePOSTRequest("requests.json", $data);
    }
}