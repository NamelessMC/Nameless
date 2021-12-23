<?php

class MCAssoc {

    private string $_siteId;
    private string $_sharedSecret;
    private string $_instanceSecret;
    private int $_timestampLeeway;
    private bool $_insecureMode = false;

    public function __construct($siteId, $sharedSecret, $instanceSecret, $timestampLeeway = 300) {
        $this->_siteId = $siteId;
        $this->_sharedSecret = hex2bin($sharedSecret);
        $this->_instanceSecret = $instanceSecret;
        $this->_timestampLeeway = $timestampLeeway;
    }

    public function enableInsecureMode(): void {
        $this->_insecureMode = true;
    }

    public function generateKey(string $data): string {
        return $this->sign($data, $this->_instanceSecret);
    }

    private function sign(string $data, string $key): string {
        return base64_encode($data . $this->baseSign($data, $key));
    }

    private function baseSign(string $data, string $key): string {
        if (!$key && !$this->_insecureMode) {
            throw new Exception('key must be provided');
        } else {
            if ($this->_insecureMode) {
                $key = 'insecure';
            }
        }

        return hash_hmac('sha1', $data, $key, true);
    }

    public function unwrapKey(string $input): string {
        return $this->verify($input, $this->_instanceSecret);
    }

    private function verify(string $input, string $key): string {
        $signed_data = base64_decode($input, true);
        if ($signed_data === false) {
            throw new Exception('bad base64 data');
        }

        if (strlen($signed_data) <= 20) {
            throw new Exception('signed data too short to have signature');
        }

        $data = substr($signed_data, 0, -20);

        if ($this->_insecureMode) {
            return $data;
        }

        $signature = substr($signed_data, -20);
        $my_signature = $this->baseSign($data, $key);

        if (!$this->constantCompare($my_signature, $signature)) {
            throw new Exception('signature invalid');
        }
        return $data;
    }

    private static function constantCompare(string $str1, string $str2): bool {
        if (strlen($str1) != strlen($str2)) {
            return false;
        }

        $res = 0;
        for ($i = 0; $i < strlen($str1); $i++) {
            $res |= ord($str1[$i]) ^ ord($str2[$i]);
        }
        return ($res == 0);
    }

    public function unwrapData(string $input, int $time = null) {
        if ($time === null) {
            $time = time();
        }

        $data = $this->verify($input, $this->_sharedSecret);
        $rdata = json_decode($data);
        if ($rdata === null) {
            throw new Exception('json data invalid');
        }

        $mintime = $time - $this->_timestampLeeway;
        $maxtime = $time + $this->_timestampLeeway;
        if (!(($mintime < $rdata->now) && ($rdata->now < $maxtime))) {
            throw new Exception('timestamp stale');
        }

        return $rdata;
    }
}
