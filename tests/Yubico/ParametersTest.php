<?php

namespace Fyfey\Yubico;

use PHPUnit\Framework\TestCase;

class ParametersTest extends TestCase
{
    public function SetUp()
    {
        $this->uut = new Parameters();
    }

    /** @test */
    function it_creates_parameters_as_a_string()
    {
        $params = Parameters::make()
            ->set('id', 12345)
            ->set('nonce', 'S7siUNKE=')
            ->set('h', 'abcd%2B123');

        $this->assertEquals('id=12345&nonce=S7siUNKE=&h=abcd%2B123', $params->build());
    }

    /** @test */
    function params_are_sortable()
    {
        $params = Parameters::make()
            ->set('a', 1)
            ->set('c', 2)
            ->set('b', 3)
            ->sort();

        $this->assertEquals('a=1&b=3&c=2', $params->build());
    }

    /** @test */
    function params_are_intersectable()
    {
        $params = Parameters::make()
            ->set('b', 1)
            ->set('c', 2)
            ->set('a', 3)
            ->intersect('a', 'b');

        $this->assertEquals('b=1&a=3', $params->build());
    }

    /** @test */
    function params_are_removable()
    {
        $params = Parameters::make()
            ->set('b', 1)
            ->set('c', 2)
            ->set('a', 3)
            ->except('c');

        $this->assertEquals('b=1&a=3', $params->build());
    }

    /** @test */
    function params_are_accessible()
    {
        $params = Parameters::make()
            ->set('b', 1)
            ->set('c', 2);

        $this->assertEquals(2, $params->c);
    }
}
