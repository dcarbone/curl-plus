<?php

/*
    Copyright 2012-2022  Daniel Paul Carbone (daniel.p.carbone@gmail.com)

    Licensed under the Apache License, Version 2.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at

        http://www.apache.org/licenses/LICENSE-2.0

    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.
 */

/**
 * Class CurlOptHelperTest
 */
class CurlOptHelperTest extends \PHPUnit\Framework\TestCase
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
        $this->assertIsString($string);
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
 