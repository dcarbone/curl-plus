<?php

require_once __DIR__ . '/inc/CPParameters.php';

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
 * Class CURLTest
 */
class CURLTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \DCarbone\CurlPlus\CURL::_execute
     * @covers \DCarbone\CurlPlus\CURL::get
     */
    public function testGetRequestWithNoParams()
    {
        $resp = \DCarbone\CurlPlus\CURL::get(CPParameters::SMALL_RESPONSE, [], [CURLOPT_SSL_VERIFYPEER => false]);

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusResponse', $resp);
        $this->assertEquals('<html><head><title>Links</title></head><body>0 </body></html>', (string)$resp);
    }

    /**
     * @covers \DCarbone\CurlPlus\CURL::_execute
     * @covers \DCarbone\CurlPlus\CURL::get
     */
    public function testGetRequestWithParams()
    {
        $resp = \DCarbone\CurlPlus\CURL::get(
            sprintf('%s/response-headers', CPParameters::HTTPBIN_URL),
            [
                'Content-Type' => 'text/plain; charset=UTF-8',
                'Server' => 'sandwiches',
            ],
            [
                CURLOPT_SSL_VERIFYPEER => false
            ]
        );

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusResponse', $resp);
        $this->assertJson((string)$resp);
    }

    /**
     * @covers \DCarbone\CurlPlus\CURL::_execute
     * @covers \DCarbone\CurlPlus\CURL::post
     */
    public function testPostRequest()
    {
        $resp = \DCarbone\CurlPlus\CURL::post(
            sprintf('%s/post', CPParameters::HTTPBIN_URL),
            [],
            [],
            [
                CURLOPT_SSL_VERIFYPEER => false
            ]
        );

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusResponse', $resp);
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
            sprintf('%s/post', CPParameters::HTTPBIN_URL),
            [
                'sandwiches' => 'post tasty'
            ],
            [],
            [
                CURLOPT_SSL_VERIFYPEER => false
            ]
        );

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusResponse', $resp);
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
            sprintf('%s/post', CPParameters::HTTPBIN_URL),
            [],
            [
                'post form key' => 'form value',
            ],
            [
                CURLOPT_SSL_VERIFYPEER => false
            ]
        );

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusResponse', $resp);
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
        $resp = \DCarbone\CurlPlus\CURL::options(CPParameters::HTTPBIN_URL, [CURLOPT_SSL_VERIFYPEER => false]);

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusResponse', $resp);
        $headers = $resp->responseHeaders;
        $this->assertIsArray($headers);
        $this->assertCount(1, $headers);
        $this->assertIsArray($headers[0]);
        $this->assertArrayHasKey('access-control-allow-methods', $headers[0]);
    }

    /**
     * @covers \DCarbone\CurlPlus\CURL::_execute
     * @covers \DCarbone\CurlPlus\CURL::head
     */
    public function testHeadRequest()
    {
        $resp = \DCarbone\CurlPlus\CURL::head(CPParameters::HTTPBIN_URL, [CURLOPT_SSL_VERIFYPEER => false]);

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusResponse', $resp);
        $headers = $resp->responseHeaders;
        $this->assertIsArray($headers);
        $this->assertCount(1, $headers);
        $this->assertIsArray($headers[0]);
        $this->assertArrayHasKey('server', $headers[0]);
    }

    /**
     * @covers \DCarbone\CurlPlus\CURL::_execute
     * @covers \DCarbone\CurlPlus\CURL::put
     */
    public function testPutRequest()
    {
        $resp = \DCarbone\CurlPlus\CURL::put(
            sprintf('%s/put', CPParameters::HTTPBIN_URL),
            [],
            [],
            [CURLOPT_SSL_VERIFYPEER => false]);

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusResponse', $resp);
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
            sprintf('%s/put', CPParameters::HTTPBIN_URL),
            [
                'sandwiches' => 'put tasty'
            ],
            [],
            [
                CURLOPT_SSL_VERIFYPEER => false
            ]
        );

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusResponse', $resp);
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
            sprintf('%s/put', CPParameters::HTTPBIN_URL),
            [],
            [
                'put form key' => 'form value',
            ],
            [
                CURLOPT_SSL_VERIFYPEER => false
            ]
        );

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusResponse', $resp);
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
            sprintf('%s/delete', CPParameters::HTTPBIN_URL),
            [],
            [],
            [CURLOPT_SSL_VERIFYPEER => false]);

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusResponse', $resp);
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
            sprintf('%s/delete', CPParameters::HTTPBIN_URL),
            [
                'sandwiches' => 'delete tasty'
            ],
            [],
            [
                CURLOPT_SSL_VERIFYPEER => false
            ]
        );

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusResponse', $resp);
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
            sprintf('%s/delete', CPParameters::HTTPBIN_URL),
            [],
            [
                'delete form key' => 'form value',
            ],
            [
                CURLOPT_SSL_VERIFYPEER => false
            ]
        );

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusResponse', $resp);
        $this->assertJson((string)$resp);
        $json = json_decode((string)$resp);
        $this->assertObjectHasAttribute('form', $json);
        $this->assertObjectHasAttribute('delete form key', $json->form);
        $this->assertEquals('form value', $json->form->{'delete form key'});
    }
}
