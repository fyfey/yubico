<?php

namespace Fyfey\Yubico;

class Response
{
    private $data = [];
    private $params = ['nonce', 'otp', 'sessioncounter', 'sessionuse', 'sl', 'status', 't', 'timeout', 'timestamp'];

    public function __construct($response)
    {
        parse_str(str_replace("\r\n", '&', $response), $this->data);
    }

    public function status()
    {
        return $this->data['status'];
    }

    public function hash()
    {
        return $this->data['h'];
    }
}
