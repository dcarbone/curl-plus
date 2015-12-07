<?php

/*
    CurlPlus: A simple OO implementation of CURL in PHP
    Copyright (C) 2012-2015  Daniel Paul Carbone (daniel.p.carbone@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along
    with this program; if not, write to the Free Software Foundation, Inc.,
    51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

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
 