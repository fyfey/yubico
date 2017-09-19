<?php

namespace Fyfey\Yubico;

class Parameters
{
    private $params = [];

    public static function make()
    {
        return new static;
    }

    public function set($key, $val)
    {
        $this->params[$key] = $val;

        return $this;
    }

    public function sort()
    {
        ksort($this->params);

        return $this;
    }

    public function build()
    {
        $ret = [];
        foreach ($this->params as $key => $val) {
            $ret[] = "{$key}={$val}";
        }

        return implode('&', $ret);
    }
}
