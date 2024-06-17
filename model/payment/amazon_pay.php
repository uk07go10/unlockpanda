<?php

class ModelPaymentAmazonPay extends Model
{

    public function sign($secret_key, $params)
    {
        uksort($params, "strcmp");
        return $this->_urlencode($this->_signParameters($params, $secret_key));
    }

    private function _urlencode($value)
    {
        return str_replace('%7E', '~', rawurlencode($value));
    }

    private function _signParameters($parameters, $key)
    {
        $stringToSign = null;
        $algorithm = "HmacSHA256";
        $stringToSign = $this->_calculateStringToSignV2($parameters);
        return $this->_sign($stringToSign, $key, $algorithm);
    }

    private function _calculateStringToSignV2($parameters)
    {
        $data = 'POST';
        $data .= "\n";
        $data .= "payments.amazon.com";
        $data .= "\n";
        $data .= "/";
        $data .= "\n";
        $data .= $this->_getParametersAsString($parameters);
        return $data;
    }

    private function _getParametersAsString($parameters) {
        $queryParameters = array();
        foreach ($parameters as $key => $value) {
            $queryParameters[] = $key . '=' . $this->_urlencode($value);
        }
        return implode('&', $queryParameters);
    }
    
    private function _sign($data, $key, $algorithm)
    {
        if ($algorithm === 'HmacSHA1') {
            $hash = 'sha1';
        } else if ($algorithm === 'HmacSHA256') {
            $hash = 'sha256';
        } else {
            throw new Exception("Non-supported signing method specified");
        }
        return base64_encode(hash_hmac($hash, $data, $key, true));
    }
}