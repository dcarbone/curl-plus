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
 * Class CURLTest
 */
class CURLTest extends PHPUnit_Framework_TestCase
{
    /** @var string */
    public static $jquery1_11 = 'http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js';
    /** @var string */
    public static $jquery2_1 = 'http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js';
    /** @var string */
    public static $smallResponse = 'http://api.gvmtool.net/candidates/grails/default';

    public static $httpbinURL = 'https://httpbin.org';

    /**
     * @covers \DCarbone\CurlPlus\CURL::_init
     * @covers \DCarbone\CurlPlus\CURL::get
     */
    public function testGetRequestWithNoParams()
    {
        $resp = \DCarbone\CurlPlus\CURL::get(self::$smallResponse);

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\Response\\CurlPlusResponseInterface', $resp);
        $this->assertRegExp('/^[0-9\.]+$/', (string)$resp);
    }

    /**
     * @covers \DCarbone\CurlPlus\CURL::_init
     * @covers \DCarbone\CurlPlus\CURL::get
     */
    public function testGetRequestWithParams()
    {
        $resp = \DCarbone\CurlPlus\CURL::get(
            sprintf('%s/response-headers', self::$httpbinURL),
            array(
                'Content-Type' => 'text/plain; charset=UTF-8',
                'Server' => 'sandwiches',
            ),
            array(
                CURLOPT_SSL_VERIFYPEER => false
            )
        );

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\Response\\CurlPlusResponseInterface', $resp);
        $this->assertJson((string)$resp);
    }

    /**
     * @covers \DCarbone\CurlPlus\CURL::_init
     * @covers \DCarbone\CurlPlus\CURL::post
     */
    public function testPostRequest()
    {
        $resp = \DCarbone\CurlPlus\CURL::post(
            sprintf('%s/post', self::$httpbinURL),
            array(),
            array(),
            array(
                CURLOPT_SSL_VERIFYPEER => false
            )
        );

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\Response\\CurlPlusResponseInterface', $resp);
        $this->assertJson((string)$resp);
        $json = json_decode((string)$resp);
        $this->assertObjectHasAttribute('headers', $json);
    }

    /**
     * @covers \DCarbone\CurlPlus\CURL::_init
     * @covers \DCarbone\CurlPlus\CURL::post
     */
    public function testPostRequestWithQueryParams()
    {
        $resp = \DCarbone\CurlPlus\CURL::post(
            sprintf('%s/post', self::$httpbinURL),
            array(
                'sandwiches' => 'tasty'
            ),
            array(),
            array(
                CURLOPT_SSL_VERIFYPEER => false
            )
        );

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\Response\\CurlPlusResponseInterface', $resp);
        $this->assertJson((string)$resp);
        $json = json_decode((string)$resp);
        $this->assertObjectHasAttribute('args', $json);
        $this->assertObjectHasAttribute('sandwiches', $json->args);
        $this->assertEquals('tasty', $json->args->sandwiches);
    }

    /**
     * @covers \DCarbone\CurlPlus\CURL::_init
     * @covers \DCarbone\CurlPlus\CURL::post
     */
    public function testPostRequestWithPostFields()
    {
        $resp = \DCarbone\CurlPlus\CURL::post(
            sprintf('%s/post', self::$httpbinURL),
            array(),
            array(
                'form key' => 'form value',
            ),
            array(
                CURLOPT_SSL_VERIFYPEER => false
            )
        );

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\Response\\CurlPlusResponseInterface', $resp);
        $this->assertJson((string)$resp);
        $json = json_decode((string)$resp);
        $this->assertObjectHasAttribute('form', $json);
        $this->assertObjectHasAttribute('form key', $json->form);
        $this->assertEquals('form value', $json->form->{'form key'});
    }

    /**
     * @covers \DCarbone\CurlPlus\CURL::_init
     * @covers \DCarbone\CurlPlus\CURL::options
     */
    public function testOptionsRequest()
    {
        $resp = \DCarbone\CurlPlus\CURL::options(self::$httpbinURL, array(CURLOPT_SSL_VERIFYPEER => false));

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\Response\\CurlPlusResponseInterface', $resp);
        $headers = $resp->getResponseHeaders(true);
        $this->assertInternalType('array', $headers);
        $this->assertArrayHasKey('Access-Control-Allow-Methods', $headers);
    }

    /**
     * @covers \DCarbone\CurlPlus\CURL::_init
     * @covers \DCarbone\CurlPlus\CURL::head
     */
    public function testHeadRequest()
    {
        $resp = \DCarbone\CurlPlus\CURL::head(self::$httpbinURL, array(CURLOPT_SSL_VERIFYPEER => false));

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\Response\\CurlPlusResponseInterface', $resp);
        $headers = $resp->getResponseHeaders(true);
        $this->assertInternalType('array', $headers);
        $this->assertArrayHasKey('Server', $headers);
    }
}
