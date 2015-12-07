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
 * Class CurlPlusClientTest
 */
class CurlPlusClientTest extends PHPUnit_Framework_TestCase
{
    /** @var string */
    public static $jquery1_11 = 'http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js';
    /** @var string */
    public static $jquery2_1 = 'http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js';
    /** @var string */
    public static $smallResponse = 'http://api.gvmtool.net/candidates/grails/default';

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::__construct
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanConstructWithNoArguments()
    {
        $curlClient = new \DCarbone\CurlPlus\CurlPlusClient();
        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusClient', $curlClient);

        return $curlClient;
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::__construct
     * @covers \DCarbone\CurlPlus\CurlPlusClient::getRequestHeaders
     */
    public function testCanConstructWithHeaderArgument()
    {
        $curlClient = new \DCarbone\CurlPlus\CurlPlusClient(null, array(), array('Content-Type' => 'text/xml'));

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusClient', $curlClient);

        $requestHeaders = $curlClient->getRequestHeaders();

        $this->assertInternalType('array', $requestHeaders);
        $this->assertCount(1, $requestHeaders);
        $this->assertArrayHasKey('Content-Type', $requestHeaders);
        $this->assertContains('text/xml', $requestHeaders);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::__construct
     * @covers \DCarbone\CurlPlus\CurlPlusClient::getRequestHeaders
     */
    public function testCanConstructWithHeaderArgumentContainingSpaceInName()
    {
        $curlClient = new \DCarbone\CurlPlus\CurlPlusClient(null, array(), array('Content-Type ' => 'text/xml'));

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusClient', $curlClient);

        $requestHeaders = $curlClient->getRequestHeaders();

        $this->assertInternalType('array', $requestHeaders);
        $this->assertCount(1, $requestHeaders);
        $this->assertArrayHasKey('Content-Type', $requestHeaders);
        $this->assertContains('text/xml', $requestHeaders);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::__construct
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionThrownWhenInvalidHeaderArrayStructurePassedToConstructor()
    {
        new \DCarbone\CurlPlus\CurlPlusClient(null, array(), array('test value'));
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::__construct
     * @expectedException \RuntimeException
     */
    public function testExceptionThrownWhenEmptyHeaderNamePassedToConstructor()
    {
        new \DCarbone\CurlPlus\CurlPlusClient(null, array(), array('' => 'text/xml'));
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::getRequestUrl
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @depends testCanConstructWithNoArguments
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanGetNullRequestUrlWithEmptyConstructorArguments(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $requestUrl = $curlClient->getRequestUrl();

        $this->assertNull($requestUrl);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::getRequestHeaders
     * @depends testCanConstructWithNoArguments
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanGetEmptyCurlOptArrayWithEmptyConstructorArguments(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $requestHeaders = $curlClient->getRequestHeaders();

        $this->assertCount(0, $requestHeaders);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::version
     * @depends testCanConstructWithNoArguments
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanGetCurlVersion(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $curlVersion = $curlClient->version();

        $this->assertEquals(curl_version(), $curlVersion);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::execute
     * @depends testCanConstructWithNoArguments
     * @expectedException \RuntimeException
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testExceptionThrownWhenTryingToExecuteRequestWithEmptyConstructorArguments(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $curlClient->execute();
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::__construct
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanConstructWithOnlyUrlParam()
    {
        $curlClient = new \DCarbone\CurlPlus\CurlPlusClient(static::$jquery1_11);

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusClient', $curlClient);

        return $curlClient;
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::getRequestUrl
     * @depends testCanConstructWithOnlyUrlParam
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanGetRequestUrlWithOnlyUrlParamConstructorArgument(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $requestUrl = $curlClient->getRequestUrl();

        $this->assertEquals(static::$jquery1_11, $requestUrl);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::__construct
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanConstructCurlPlusObjectWithCurlOptUrlParam()
    {
        $curlClient = new \DCarbone\CurlPlus\CurlPlusClient(null, array(CURLOPT_URL => static::$jquery1_11));

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusClient', $curlClient);

        return $curlClient;
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::getRequestUrl
     * @depends testCanConstructCurlPlusObjectWithCurlOptUrlParam
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanGetRequestUrlWithCurlOptUrlParamConstructorArgument(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $requestUrl = $curlClient->getRequestUrl();

        $this->assertEquals(static::$jquery1_11, $requestUrl);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::__construct
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanConstructWithUrlAndCurlOptArrayParams()
    {
        $curlClient = new \DCarbone\CurlPlus\CurlPlusClient(
            static::$jquery1_11,
            array(CURLOPT_URL => static::$jquery1_11,
                CURLOPT_RETURNTRANSFER => true));

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusClient', $curlClient);

        return $curlClient;
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::getRequestUrl
     * @depends testCanConstructWithUrlAndCurlOptArrayParams
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanGetRequestUrlWithUrlAndCurlOptArrayConstructorArguments(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $requestUrl = $curlClient->getRequestUrl();

        $this->assertEquals(static::$jquery1_11, $requestUrl);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::getCurlOpts
     * @depends testCanConstructWithUrlAndCurlOptArrayParams
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanGetCurlOptArrayWithUrlAndCurlOptArrayConstructorArguments(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $curlOptArray = $curlClient->getCurlOpts();

        $this->assertCount(1, $curlOptArray);
        $this->assertArrayHasKey(CURLOPT_RETURNTRANSFER, $curlOptArray);
        $this->assertArrayNotHasKey(CURLOPT_URL, $curlOptArray);
        $this->assertContains(true, $curlOptArray);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::getCurlOpts
     * @covers \DCarbone\CurlPlus\CurlOptHelper::createHumanReadableCurlOptArray
     * @covers \DCarbone\CurlPlus\CurlOptHelper::getStringNameForCurlOpt
     * @depends testCanConstructWithUrlAndCurlOptArrayParams
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanGetHumanReadableCurlOptArray(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $curlOptArray = $curlClient->getCurlOpts(true);

        $this->assertCount(1, $curlOptArray);
        $this->assertArrayHasKey('CURLOPT_RETURNTRANSFER', $curlOptArray);
        $this->assertArrayNotHasKey('CURLOPT_URL', $curlOptArray);
        $this->assertContains(true, $curlOptArray);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::curlOptSet
     * @depends testCanConstructWithUrlAndCurlOptArrayParams
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanDetermineIfCurlOptIsSet(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $isset = $curlClient->curlOptSet(CURLOPT_RETURNTRANSFER);
        $notset = $curlClient->curlOptSet(CURLOPT_URL);

        $this->assertTrue($isset);
        $this->assertFalse($notset);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::setRequestHeader
     * @covers \DCarbone\CurlPlus\CurlPlusClient::getRequestHeaders
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanAddRequestHeaderString()
    {
        $curlClient = new \DCarbone\CurlPlus\CurlPlusClient(self::$jquery1_11);
        $client = $curlClient->setRequestHeader('Accept', 'application/json');

        $this->assertEquals($curlClient, $client);

        $requestHeaders = $curlClient->getRequestHeaders();
        $this->assertInternalType('array', $requestHeaders);
        $this->assertCount(1, $requestHeaders);
        $this->assertArrayHasKey('Accept', $requestHeaders);
        $this->assertContains('application/json', $requestHeaders);

        return $curlClient;
    }

    /**
     * @covers  \DCarbone\CurlPlus\CurlPlusClient::addRequestHeaders
     * @depends testCanAddRequestHeaderString
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanAddArrayOfHeadersToExistingRequestHeaderArray(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $curlClient->addRequestHeaders(array('Content-Type' => 'text/html'));

        $requestHeaders = $curlClient->getRequestHeaders();
        $this->assertInternalType('array', $requestHeaders);
        $this->assertCount(2, $requestHeaders);
        $this->assertArrayHasKey('Content-Type', $requestHeaders);
        $this->assertContains('text/html', $requestHeaders);

        return $curlClient;
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::setRequestHeaders
     * @covers \DCarbone\CurlPlus\CurlPlusClient::getRequestHeaders
     * @depends testCanAddRequestHeaderString
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanOverwriteRequestHeaderArray(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $curlClient->setRequestHeaders(array('Content-Type' => 'text/xml'));

        $requestHeaders = $curlClient->getRequestHeaders();

        $this->assertInternalType('array', $requestHeaders);
        $this->assertCount(1, $requestHeaders);
        $this->assertArrayHasKey('Content-Type', $requestHeaders);
        $this->assertContains('text/xml', $requestHeaders);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::getCurlOpts
     * @covers \DCarbone\CurlPlus\CurlPlusClient::setCurlOpts
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanSetCurlOptsWithArray()
    {
        $curlClient = new \DCarbone\CurlPlus\CurlPlusClient(self::$jquery1_11);

        $curlOpts = $curlClient->getCurlOpts();
        $this->assertInternalType('array', $curlOpts);
        $this->assertCount(0, $curlOpts);

        $client = $curlClient->setCurlOpts(array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
        ));

        $this->assertEquals($curlClient, $client);

        $curlOpts = $curlClient->getCurlOpts();
        $this->assertInternalType('array', $curlOpts);
        $this->assertCount(2, $curlOpts);
        $this->assertArrayHasKey(CURLOPT_RETURNTRANSFER, $curlOpts);
        $this->assertArrayHasKey(CURLOPT_SSL_VERIFYPEER, $curlOpts);

        return $curlClient;
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::removeCurlOpt
     * @covers \DCarbone\CurlPlus\CurlPlusClient::getCurlOpts
     * @depends testCanSetCurlOptsWithArray
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanRemoveCurlOpt(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $curlClient->removeCurlOpt(CURLOPT_SSL_VERIFYPEER);

        $curlOpts = $curlClient->getCurlOpts();
        $this->assertInternalType('array', $curlOpts);
        $this->assertCount(1, $curlOpts);
        $this->assertArrayHasKey(CURLOPT_RETURNTRANSFER, $curlOpts);
        $this->assertArrayNotHasKey(CURLOPT_SSL_VERIFYPEER, $curlOpts);

        return $curlClient;
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::getCurlOptValue
     * @depends testCanRemoveCurlOpt
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanGetCurlOptValue(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $exists = $curlClient->getCurlOptValue(CURLOPT_RETURNTRANSFER);
        $notExists = $curlClient->getCurlOptValue(CURLOPT_SSL_VERIFYPEER);

        $this->assertTrue($exists);
        $this->assertNull($notExists);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::initialize
     * @covers \DCarbone\CurlPlus\CurlPlusClient::close
     * @covers \DCarbone\CurlPlus\CurlPlusClient::reset
     * @depends testCanConstructWithUrlAndCurlOptArrayParams
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanSetRequestUrlAndResetOpts(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $curlClient->initialize(static::$jquery2_1, true);

        $this->assertEquals(static::$jquery2_1, $curlClient->getRequestUrl());
        $this->assertCount(0, $curlClient->getCurlOpts());
        $this->assertCount(0, $curlClient->getRequestHeaders());

        return $curlClient;
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::execute
     * @covers \DCarbone\CurlPlus\CurlPlusClient::createResponse
     * @covers \DCarbone\CurlPlus\Response\CurlPlusResponse::__construct
     */
    public function testCanExecuteQueryDirectlyToOutputBufferWithoutResetting()
    {
        $curlClient = new \DCarbone\CurlPlus\CurlPlusClient(static::$jquery2_1);

        ob_start();
        $curlClient->execute();
        $data = ob_get_clean();

        $this->assertStringStartsWith('/*! jQuery v2.1.1', $data);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::execute
     */
    public function testCanReExecuteRequestSuccessfully()
    {
        $curlClient = new \DCarbone\CurlPlus\CurlPlusClient(static::$jquery2_1);

        ob_start();
        $curlClient->execute();
        $data = ob_get_clean();

        $this->assertStringStartsWith('/*! jQuery v2.1.1', $data);

        ob_start();
        $curlClient->execute();
        $data = ob_get_clean();

        $this->assertStringStartsWith('/*! jQuery v2.1.1', $data);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::setCurlOpt
     * @depends testCanConstructWithUrlAndCurlOptArrayParams
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanSetCurlOptValuesAfterConstruction(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $curlClient->setCurlOpt(CURLOPT_HEADER, true);
        $curlClient->setCurlOpt(CURLOPT_RETURNTRANSFER, true);

        $curlOpts = $curlClient->getCurlOpts();

        $this->assertCount(2, $curlOpts);
        $this->assertArrayHasKey(CURLOPT_HEADER, $curlOpts);
        $this->assertArrayHasKey(CURLOPT_RETURNTRANSFER, $curlOpts);

        return $curlClient;
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::execute
     * @covers \DCarbone\CurlPlus\Response\CurlPlusResponse::getResponseHeaders
     * @covers \DCarbone\CurlPlus\Response\CurlPlusResponse::__construct
     */
    public function testCanParseResponseHeadersOutOfResponseBody()
    {
        $curlClient = new \DCarbone\CurlPlus\CurlPlusClient(static::$smallResponse, array(CURLOPT_RETURNTRANSFER => true));

        $response = $curlClient->execute();

        $responseHeaders = $response->getResponseHeaders();

        $this->assertInternalType('string', $responseHeaders);
        $this->assertStringStartsWith('HTTP/1.1 200 OK', $responseHeaders);
        $this->assertStringEndsWith("\r\n\r\n", $responseHeaders);

        $data = $response->getResponseBody();

        $this->assertInternalType('string', $data);
        $this->assertRegExp('/^[1-9]+\.[0-9]+\.[0-9]+/', $data);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::removeRequestHeader
     */
    public function testCanRemoveRequestHeader()
    {
        $client = new \DCarbone\CurlPlus\CurlPlusClient(null, array(), array('Accept' => 'application/json'));
        $client->removeRequestHeader('Accept');
        $this->assertCount(0, $client->getRequestHeaders());
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::setCurlOpt
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionThrownWhenPassingInvalidCurlOptIdentifier()
    {
         new \DCarbone\CurlPlus\CurlPlusClient(null, array('sandwiches' => true));
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::execute
     */
    public function testExecutionWithCustomRequestHeaders()
    {
        $client = new \DCarbone\CurlPlus\CurlPlusClient(
            self::$jquery1_11,
            array(CURLOPT_RETURNTRANSFER => true),
            array('Accept' => 'application/json'));

        $client->execute(true);
    }
}
