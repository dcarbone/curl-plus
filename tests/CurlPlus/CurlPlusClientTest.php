<?php

/**
 * Class CurlPlusClientTest
 */
class CurlPlusClientTest extends PHPUnit_Framework_TestCase
{
    /** @var string */
    public static $requestUrl = 'http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js';
    public static $requestUrl2 = 'http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js';

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::__construct
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanConstructCurlPlusObjectWithNoArguments()
    {
        $curlClient = new \DCarbone\CurlPlus\CurlPlusClient();
        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusClient', $curlClient);

        return $curlClient;
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::getRequestUrl
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @depends testCanConstructCurlPlusObjectWithNoArguments
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanGetNullRequestUrlWithEmptyConstructorArguments(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $requestUrl = $curlClient->getRequestUrl();

        $this->assertNull($requestUrl);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::getRequestHeaders
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @depends testCanConstructCurlPlusObjectWithNoArguments
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanGetEmptyCurlOptArrayWithEmptyConstructorArguments(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $requestHeaders = $curlClient->getRequestHeaders();

        $this->assertCount(0, $requestHeaders);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::version
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @depends testCanConstructCurlPlusObjectWithNoArguments
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanGetCurlVersion(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $curlVersion = $curlClient->version();

        $this->assertEquals(curl_version(), $curlVersion);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::execute
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @depends testCanConstructCurlPlusObjectWithNoArguments
     * @expectedException \RuntimeException
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testExceptionThrownWhenTryingToExecuteRequestWithEmptyConstructorArguments(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $curlResponse = $curlClient->execute();
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::__construct
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanConstructCurlPlusObjectWithOnlyUrlParam()
    {
        $curlClient = new \DCarbone\CurlPlus\CurlPlusClient(static::$requestUrl);

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusClient', $curlClient);

        return $curlClient;
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::getRequestUrl
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @depends testCanConstructCurlPlusObjectWithOnlyUrlParam
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanGetRequestUrlWithOnlyUrlParamConstructorArgument(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $requestUrl = $curlClient->getRequestUrl();

        $this->assertEquals(static::$requestUrl, $requestUrl);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::__construct
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanConstructCurlPlusObjectWithCurlOptUrlParam()
    {
        $curlClient = new \DCarbone\CurlPlus\CurlPlusClient(null, array(CURLOPT_URL => static::$requestUrl));

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusClient', $curlClient);

        return $curlClient;
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::getRequestUrl
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @depends testCanConstructCurlPlusObjectWithCurlOptUrlParam
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanGetRequestUrlWithCurlOptUrlParamConstructorArgument(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $requestUrl = $curlClient->getRequestUrl();

        $this->assertEquals(static::$requestUrl, $requestUrl);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::__construct
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanConstructCurlPlusObjectWithUrlAndCurlOptArrayParams()
    {
        $curlClient = new \DCarbone\CurlPlus\CurlPlusClient(
            static::$requestUrl,
            array(CURLOPT_URL => static::$requestUrl,
                CURLOPT_RETURNTRANSFER => true));

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusClient', $curlClient);

        return $curlClient;
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::getRequestUrl
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @depends testCanConstructCurlPlusObjectWithUrlAndCurlOptArrayParams
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanGetRequestUrlWithUrlAndCurlOptArrayConstructorArguments(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $requestUrl = $curlClient->getRequestUrl();

        $this->assertEquals(static::$requestUrl, $requestUrl);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::getCurlOpts
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @depends testCanConstructCurlPlusObjectWithUrlAndCurlOptArrayParams
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
     * @covers \DCarbone\CurlPlus\CurlPlusClient::initialize
     * @covers \DCarbone\CurlPlus\CurlPlusClient::close
     * @covers \DCarbone\CurlPlus\CurlPlusClient::reset
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @depends testCanConstructCurlPlusObjectWithUrlAndCurlOptArrayParams
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanSetRequestUrlAndResetOpts(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $curlClient->initialize(static::$requestUrl2, true);

        $this->assertEquals(static::$requestUrl2, $curlClient->getRequestUrl());
        $this->assertCount(0, $curlClient->getCurlOpts());
        $this->assertCount(0, $curlClient->getRequestHeaders());

        return $curlClient;
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::execute
     * @covers \DCarbone\CurlPlus\CurlPlusClient::createResponse
     * @covers \DCarbone\CurlPlus\Response\CurlPlusResponse::__construct
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @uses \DCarbone\CurlPlus\Response\CurlPlusResponse
     */
    public function testCanExecuteQueryDirectlyToOutputBufferWithoutResetting()
    {
        $curlClient = new \DCarbone\CurlPlus\CurlPlusClient(static::$requestUrl2);

        ob_start();
        $curlClient->execute();
        $data = ob_get_clean();

        $this->assertStringStartsWith('/*! jQuery v2.1.1', $data);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::execute
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @uses \DCarbone\CurlPlus\Response\CurlPlusResponse
     */
    public function testCanReExecuteRequestSuccessfully()
    {
        $curlClient = new \DCarbone\CurlPlus\CurlPlusClient(static::$requestUrl2);

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
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @depends testCanConstructCurlPlusObjectWithUrlAndCurlOptArrayParams
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
     * @depends testCanSetCurlOptValuesAfterConstruction
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanParseResponseHeadersOutOfResponseBody(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $response = $curlClient->execute();

        $responseHeaders = $response->getResponseHeaders();

        $this->assertInternalType('string', $responseHeaders);
        $this->assertStringStartsWith('HTTP/1.1 200 OK', $responseHeaders);
        $this->assertStringEndsWith("\r\n\r\n", $responseHeaders);
    }
}
