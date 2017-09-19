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
            ->set('nonce', $this->nonce());
        $params->set('h', $this->sign($params));

        $mh = curl_multi_init();
        $handles = [];
        foreach ($this->verifyEndpoints as $endpoint) {
            $uri = "https://{$endpoint}?{$params->build()}";

            $ch = curl_init($uri);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FAILONERROR, true);
            curl_multi_add_handle($mh, $ch);
            $handles[(int)$ch] = $ch;
        }

        $active = null;
        $responses = [];
        do {
            while ($mrc = curl_multi_exec($mh, $active) === CURLM_CALL_MULTI_PERFORM);
            while ($info = curl_multi_info_read($mh)) {
                if ($info['result'] == CURLE_OK) {
                    $body = curl_multi_getcontent($info['handle']);
                    $responses[] = Response::make($body);
                }
                curl_multi_select($mh);
            }
        } while ($active);
        curl_multi_close($mh);

        $success = false;
        foreach ($responses as $response) {

            $hash = str_replace('+', '%2B', $response->h);
            $ourHash = $this->sign($response->params()->except('h'));

            if ((!$response->success() && !$response->replay())) {
                return false;
            } elseif ($response->otp !== $otp->otp()) {
                return false;
            } elseif (! hash_equals($ourHash, $hash)) {
                return false;
            } elseif ($response->success()) {
                $success = true;
            }
        }

        return $success;
    }

    private function sign($params)
    {
        return str_replace('+', '%2B', base64_encode(hash_hmac('sha1', $params->sort()->build(), $this->clientSecret, true)));
    }

    private function nonce()
    {
        return md5(uniqid(rand()));
    }
}
