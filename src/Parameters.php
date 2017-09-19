<?php

namespace Fyfey\Yubico;

class Parameters
{
    private $params = [];

    public static function make($params = [])
    {
        return new static($params);
    }

    public function __construct($params = [])
    {
        foreach ($params as $key => $val) {
            $this->set($key, $val);
        }
    }

    public function __get($key)
    {
        if (isset($this->params[$key])) {
            return $this->params[$key];
        }
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

    public function intersect(...$keys)
    {
        return new static(array_intersect_key($this->params, array_flip((array) $keys)));
    }

    public function except(...$keys)
    {
        foreach ($keys as $key) {
            unset($this->params[$key]);
        }

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
