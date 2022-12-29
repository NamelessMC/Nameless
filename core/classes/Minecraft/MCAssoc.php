<?php
/**
 * MCAssoc utility class
 *
 * @package NamelessMC\Minecraft
 * @author lukegb
 * @site https://github.com/lukegb/mcassoc.php
 */
class MCAssoc {

    private string $_sharedSecret;
    private string $_instanceSecret;
    private int $_timestampLeeway;
    private bool $_insecureMode = false;

    /** @phpstan-ignore-next-line */
    public function __construct($siteId, $sharedSecret, $instanceSecret, $timestampLeeway = 300) {
        $this->_sharedSecret = hex2bin($sharedSecret);
        $this->_instanceSecret = $instanceSecret;
        $this->_timestampLeeway = $timestampLeeway;
    }

    /**
     * @return void
     */
    public function enableInsecureMode(): void {
        $this->_insecureMode = true;
    }

    /**
     * @param string $data
     *
     * @return string
     */
    public function generateKey(string $data): string {
        return $this->sign($data, $this->_instanceSecret);
    }

    /**
     * @param string $data
     * @param string $key
     *
     * @return string
     */
    private function sign(string $data, string $key): string {
        return base64_encode($data . $this->baseSign($data, $key));
    }

    /**
     * @param string $data
     * @param string $key
     *
     * @return string
     */
    private function baseSign(string $data, string $key): string {
        if (!$key && !$this->_insecureMode) {
            throw new RuntimeException('key must be provided');
        }

        if ($this->_insecureMode) {
            $key = 'insecure';
        }

        return hash_hmac('sha1', $data, $key, true);
    }

    /**
     * @param string $input
     *
     * @return string
     */
    public function unwrapKey(string $input): string {
        return $this->verify($input, $this->_instanceSecret);
    }

    /**
     * @param string $input
     * @param string $key
     *
     * @return string
     */
    private function verify(string $input, string $key): string {
        $signed_data = base64_decode($input, true);
        if ($signed_data === false) {
            throw new RuntimeException('bad base64 data');
        }

        if (strlen($signed_data) <= 20) {
            throw new RuntimeException('signed data too short to have signature');
        }

        $data = substr($signed_data, 0, -20);

        if ($this->_insecureMode) {
            return $data;
        }

        $signature = substr($signed_data, -20);
        $my_signature = $this->baseSign($data, $key);

        if (!self::constantCompare($my_signature, $signature)) {
            throw new RuntimeException('signature invalid');
        }
        return $data;
    }

    /**
     * @param string $str1
     * @param string $str2
     *
     * @return bool
     */
    private static function constantCompare(string $str1, string $str2): bool {
        if (strlen($str1) !== strlen($str2)) {
            return false;
        }

        $res = 0;
        for ($i = 0, $iMax = strlen($str1); $i < $iMax; $i++) {
            $res |= ord($str1[$i]) ^ ord($str2[$i]);
        }
        return ($res === 0);
    }

    /**
     * @param string $input
     * @param ?int $time
     *
     * @return mixed
     */
    public function unwrapData(string $input, int $time = null) {
        if ($time === null) {
            $time = time();
        }

        $data = $this->verify($input, $this->_sharedSecret);
        $rdata = json_decode($data, true);
        if ($rdata === null) {
            throw new RuntimeException('json data invalid');
        }

        $min_time = $time - $this->_timestampLeeway;
        $max_time = $time + $this->_timestampLeeway;
        if (!(($min_time < $rdata->now) && ($rdata->now < $max_time))) {
            throw new RuntimeException('timestamp stale');
        }

        return $rdata;
    }
}
