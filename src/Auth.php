<?php

namespace Fyfey\Yubico;

class Auth
{
    private $verifyEndpoints = [
        'api.yubico.com/wsapi/2.0/verify',
        'api2.yubico.com/wsapi/2.0/verify',
        'api3.yubico.com/wsapi/2.0/verify',
        'api4.yubico.com/wsapi/2.0/verify',
        'api5.yubico.com/wsapi/2.0/verify'
    ];

    public function __construct($clientId, $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = base64_decode($clientSecret);
    }

    public function verify($otp)
    {
        $otp = OneTimePassword::make($otp);

        $params = Parameters::make()
            ->set('id', $this->clientId)
            ->set('otp', $otp->otp())
            ->set('nonce', $this->nonce())
            ->sort();
        $params->set('h', $this->sign($params));

        var_dump($params->build());

        $mh = curl_multi_init();
        $handles = [];
        foreach ($this->verifyEndpoints as $endpoint) {
            $uri = "https://{$endpoint}?{$params->build()}";

            var_dump($uri);
            $ch = curl_init($uri);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FAILONERROR, true);
            curl_multi_add_handle($mh, $ch);
            $handles[(int)$ch] = $ch;
        }

        $active = null;
        do {
            while ($mrc = curl_multi_exec($mh, $active) === CURLM_CALL_MULTI_PERFORM) {
            }

            while ($info = curl_multi_info_read($mh)) {
                if ($info['result'] == CURLE_OK) {
                    $body = curl_multi_getcontent($info['handle']);
                }
                curl_multi_select($mh);
            }
        } while ($active);

        return true;
    }

    private function sign($params)
    {
        return str_replace('+', '%sB', base64_encode(hash_hmac('sha1', $params->build(), $this->clientSecret, true)));
    }

    private function nonce()
    {
        return md5(uniqid(rand()));
    }

}
