<?php

namespace Fyfey\Yubico;

use PHPUnit\Framework\TestCase;

/**
 * @author Stuart Fyfe <sfyfe@enable.services>
 */
class OneTimePasswordTest extends TestCase
{
    const OTP = 'dteffujehknhfjbrjnlnldnhcujvddbikngjrtgh';

    /** @test */
    function one_time_password_can_be_retrieved()
    {
        $otp = new OneTimePassword(self::OTP);

        $this->assertEquals('dteffujehknhfjbrjnlnldnhcujvddbikngjrtgh', $otp->otp());
    }

    /** @test */
    function the_prefix_can_be_retrieved()
    {
        $otp = new OneTimePassword(self::OTP);

        $this->assertEquals('dteffujehknh', $otp->prefix());
    }

    /** @test */
    function the_cipher_text_can_be_retrieved()
    {
        $otp = new OneTimePassword(self::OTP);

        $this->assertEquals('fjbrjnlnldnhcujvddbikngjrtgh', $otp->cipherText());
    }
}
