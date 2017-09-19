<?php

use PHPUnit\Framework\TestCase;

use Fyfey\Yubico\Response;

class ResponseTest extends TestCase
{
    public function SetUp()
    {
        $testData = "h=9TLbYNMpVA40WE+QrmEm6EnDxTw=\r\nt=2017-09-19T16:14:13Z0194\r\notp=dteffujehknhfjbrjnlnldnhcujvddbikngjrtgh\r\nnonce=314e0f23e382a4152de8e077532fe8e0\r\nsl=25\r\nstatus=OK";
        $this->uut = new Response($testData);
    }

    /** @test */
    function status_is_parsed()
    {
        $this->assertEquals('OK', $this->uut->status());
    }
}
