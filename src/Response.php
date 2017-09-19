<?php

namespace Fyfey\Yubico;

class Response
{
    private $data = [];
    private $params = ['h', 'nonce', 'otp', 'sessioncounter', 'sessionuse', 'sl', 'status', 't', 'timeout', 'timestamp'];

    public static function make($response)
    {
        return new static($response);
    }

    public function __construct($response)
    {
        foreach (array_filter(explode("\r\n", $response)) as $line) {
            $parts = explode('=', $line, 2);
            $params[$parts[0]] = $parts[1];
        }

        $this->data = Parameters::make($params)->intersect(...$this->params);
    }

    public function success()
    {
        return $this->status() === 'OK';
    }

    public function replay()
    {
        return $this->status() === 'REPLAYED_REQUEST';
    }

    public function status()
    {
        return $this->data->status;
    }

    public function params()
    {
        return $this->data;
    }

    public function __get($key)
    {
        return $this->data->$key;
    }
}
