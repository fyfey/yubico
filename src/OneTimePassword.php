<?php

namespace Fyfey\Yubico;

class OneTimePassword
{
    private $otp;

    public static function make($otp)
    {
        return new static($otp);
    }

    public function __construct($otp)
    {
        $this->otp = $otp;
        $this->prefix = substr($otp, 0, 12);
        $this->cipherText = substr($otp, 12);
    }

    public function otp()
    {
        return $this->otp;
    }

    public function prefix()
    {
        return $this->prefix;
    }

    public function cipherText()
    {
        return $this->cipherText;
    }
}
