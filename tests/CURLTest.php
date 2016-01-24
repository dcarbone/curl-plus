<?php

require_once __DIR__ . '/inc/CPParameters.php';

/*
    Copyright 2012-2015  Daniel Paul Carbone (daniel.p.carbone@gmail.com)

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
 * Class CURLTest
 */
class CURLTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \DCarbone\CurlPlus\CURL::_execute
     * @covers \DCarbone\CurlPlus\CURL::get
     */
    public function testGetRequestWithNoParams()
    {
        $resp = \DCarbone\CurlPlus\CURL::get(CPParameters::$smallResponse);

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\Response\\CurlPlusResponse', $resp);
        $this->assertRegExp('/^[0-9\.]+$/', (string)$resp);
    }

    /**
     * @covers \DCarbone\CurlPlus\CURL::_execute
     * @covers \DCarbone\CurlPlus\CURL::get
     */
    public function testGetRequestWithParams()
    {
        $resp = \DCarbone\CurlPlus\CURL::get(
            sprintf('%s/response-headers', CPParameters::$httpbinURL),
            array(
                'Content-Type' => 'text/plain; charset=UTF-8',
                'Server' => 'sandwiches',
            ),
            array(
                CURLOPT_SSL_VERIFYPEER => false
            )
        );

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\Response\\CurlPlusResponse', $resp);
        $this->assertJson((string)$resp);
    }

    /**
     * @covers \DCarbone\CurlPlus\CURL::_execute
     * @covers \DCarbone\CurlPlus\CURL::post
     */
    public function testPostRequest()
    {
        $resp = \DCarbone\CurlPlus\CURL::post(
            sprintf('%s/post', CPParameters::$httpbinURL),
            array(),
            array(),
            array(
                CURLOPT_SSL_VERIFYPEER => false
            )
        );

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\Response\\CurlPlusResponse', $resp);
        $this->assertJson((string)$resp);
        $json = json_decode((string)$resp);
        $this->assertObjectHasAttribute('headers', $json);
    }

    /**
     * @covers \DCarbone\CurlPlus\CURL::_execute
     * @covers \DCarbone\CurlPlus\CURL::post
     */
    public function testPostRequestWithQueryParams()
    {
        $resp = \DCarbone\CurlPlus\CURL::post(
            sprintf('%s/post', CPParameters::$httpbinURL),
            array(
                'sandwiches' => 'post tasty'
            ),
            array(),
            array(
                CURLOPT_SSL_VERIFYPEER => false
            )
        );

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\Response\\CurlPlusResponse', $resp);
        $this->assertJson((string)$resp);
        $json = json_decode((string)$resp);
        $this->assertObjectHasAttribute('args', $json);
        $this->assertObjectHasAttribute('sandwiches', $json->args);
        $this->assertEquals('post tasty', $json->args->sandwiches);
    }

    /**
     * @covers \DCarbone\CurlPlus\CURL::_execute
     * @covers \DCarbone\CurlPlus\CURL::post
     */
    public function testPostRequestWithFormParams()
    {
        $resp = \DCarbone\CurlPlus\CURL::post(
            sprintf('%s/post', CPParameters::$httpbinURL),
            array(),
            array(
                'post form key' => 'form value',
            ),
            array(
                CURLOPT_SSL_VERIFYPEER => false
            )
        );

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\Response\\CurlPlusResponse', $resp);
        $this->assertJson((string)$resp);
        $json = json_decode((string)$resp);
        $this->assertObjectHasAttribute('form', $json);
        $this->assertObjectHasAttribute('post form key', $json->form);
        $this->assertEquals('form value', $json->form->{'post form key'});
    }

    /**
     * @covers \DCarbone\CurlPlus\CURL::_execute
     * @covers \DCarbone\CurlPlus\CURL::options
     */
    public function testOptionsRequest()
    {
        $resp = \DCarbone\CurlPlus\CURL::options(CPParameters::$httpbinURL, array(CURLOPT_SSL_VERIFYPEER => false));

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\Response\\CurlPlusResponse', $resp);
        $headers = $resp->getResponseHeaders(true);
        $this->assertInternalType('array', $headers);
        $this->assertCount(1, $headers);
        $this->assertInternalType('array', $headers[0]);
        $this->assertArrayHasKey('Access-Control-Allow-Methods', $headers[0]);
    }

    /**
     * @covers \DCarbone\CurlPlus\CURL::_execute
     * @covers \DCarbone\CurlPlus\CURL::head
     */
    public function testHeadRequest()
    {
        $resp = \DCarbone\CurlPlus\CURL::head(CPParameters::$httpbinURL, array(CURLOPT_SSL_VERIFYPEER => false));

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\Response\\CurlPlusResponse', $resp);
        $headers = $resp->getResponseHeaders(true);
        $this->assertInternalType('array', $headers);
        $this->assertCount(1, $headers);
        $this->assertInternalType('array', $headers[0]);
        $this->assertArrayHasKey('Server', $headers[0]);
    }

    /**
     * @covers \DCarbone\CurlPlus\CURL::_execute
     * @covers \DCarbone\CurlPlus\CURL::put
     */
    public function testPutRequest()
    {
        $resp = \DCarbone\CurlPlus\CURL::put(
            sprintf('%s/put', CPParameters::$httpbinURL),
            array(),
            array(),
            array(CURLOPT_SSL_VERIFYPEER => false));

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\Response\\CurlPlusResponse', $resp);
        $this->assertJson((string)$resp);
        $json = json_decode((string)$resp);
        $this->assertObjectHasAttribute('headers', $json);
    }

    /**
     * @covers \DCarbone\CurlPlus\CURL::_execute
     * @covers \DCarbone\CurlPlus\CURL::put
     */
    public function testPutRequestWithQueryParams()
    {
        $resp = \DCarbone\CurlPlus\CURL::put(
            sprintf('%s/put', CPParameters::$httpbinURL),
            array(
                'sandwiches' => 'put tasty'
            ),
            array(),
            array(
                CURLOPT_SSL_VERIFYPEER => false
            )
        );

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\Response\\CurlPlusResponse', $resp);
        $this->assertJson((string)$resp);
        $json = json_decode((string)$resp);
        $this->assertObjectHasAttribute('args', $json);
        $this->assertObjectHasAttribute('sandwiches', $json->args);
        $this->assertEquals('put tasty', $json->args->sandwiches);
    }

    /**
     * @covers \DCarbone\CurlPlus\CURL::_execute
     * @covers \DCarbone\CurlPlus\CURL::put
     */
    public function testPutRequestWithFormParams()
    {
        $resp = \DCarbone\CurlPlus\CURL::put(
            sprintf('%s/put', CPParameters::$httpbinURL),
            array(),
            array(
                'put form key' => 'form value',
            ),
            array(
                CURLOPT_SSL_VERIFYPEER => false
            )
        );

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\Response\\CurlPlusResponse', $resp);
        $this->assertJson((string)$resp);
        $json = json_decode((string)$resp);
        $this->assertObjectHasAttribute('form', $json);
        $this->assertObjectHasAttribute('put form key', $json->form);
        $this->assertEquals('form value', $json->form->{'put form key'});
    }

    /**
     * @covers \DCarbone\CurlPlus\CURL::_execute
     * @covers \DCarbone\CurlPlus\CURL::delete
     */
    public function testDeleteRequest()
    {
        $resp = \DCarbone\CurlPlus\CURL::delete(
            sprintf('%s/delete', CPParameters::$httpbinURL),
            array(),
            array(),
            array(CURLOPT_SSL_VERIFYPEER => false));

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\Response\\CurlPlusResponse', $resp);
        $this->assertJson((string)$resp);
        $json = json_decode((string)$resp);
        $this->assertObjectHasAttribute('headers', $json);
    }

    /**
     * @covers \DCarbone\CurlPlus\CURL::_execute
     * @covers \DCarbone\CurlPlus\CURL::delete
     */
    public function testDeleteRequestWithQueryParams()
    {
        $resp = \DCarbone\CurlPlus\CURL::delete(
            sprintf('%s/delete', CPParameters::$httpbinURL),
            array(
                'sandwiches' => 'delete tasty'
            ),
            array(),
            array(
                CURLOPT_SSL_VERIFYPEER => false
            )
        );

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\Response\\CurlPlusResponse', $resp);
        $this->assertJson((string)$resp);
        $json = json_decode((string)$resp);
        $this->assertObjectHasAttribute('args', $json);
        $this->assertObjectHasAttribute('sandwiches', $json->args);
        $this->assertEquals('delete tasty', $json->args->sandwiches);
    }

    /**
     * @covers \DCarbone\CurlPlus\CURL::_execute
     * @covers \DCarbone\CurlPlus\CURL::delete
     */
    public function testDeleteRequestWithFormParams()
    {
        $resp = \DCarbone\CurlPlus\CURL::delete(
            sprintf('%s/delete', CPParameters::$httpbinURL),
            array(),
            array(
                'delete form key' => 'form value',
            ),
            array(
                CURLOPT_SSL_VERIFYPEER => false
            )
        );

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\Response\\CurlPlusResponse', $resp);
        $this->assertJson((string)$resp);
        $json = json_decode((string)$resp);
        $this->assertObjectHasAttribute('form', $json);
        $this->assertObjectHasAttribute('delete form key', $json->form);
        $this->assertEquals('form value', $json->form->{'delete form key'});
    }
}
