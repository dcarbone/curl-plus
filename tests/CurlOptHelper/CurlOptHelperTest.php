<?php

/**
 * Class CurlOptHelperTest
 */
class CurlOptHelperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \DCarbone\CurlPlus\CurlOptHelper::init
     * @uses \DCarbone\CurlPlus\CurlOptHelper
     */
    public function testCanInitCurlOptHelperOnFileInclude()
    {
        $shouldBeTrue = class_exists('\\DCarbone\\CurlPlus\\CurlOptHelper', true);

        $this->assertTrue($shouldBeTrue);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlOptHelper::getStringNameForCurlOpt
     * @uses \DCarbone\CurlPlus\CurlOptHelper
     */
    public function testCanGetStringNameForCurlOpt()
    {
        $string = \DCarbone\CurlPlus\CurlOptHelper::getStringNameForCurlOpt(CURLOPT_HTTPAUTH);
        $this->assertInternalType('string', $string);
        $this->assertEquals('CURLOPT_HTTPAUTH', $string);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlOptHelper::getStringNameForCurlOpt
     * @uses \DCarbone\CurlPlus\CurlOptHelper
     */
    public function testCanGetNullForUndefinedCurlOpt()
    {
        $null = \DCarbone\CurlPlus\CurlOptHelper::getStringNameForCurlOpt(-43);
        $this->assertNull($null);
    }
}
 