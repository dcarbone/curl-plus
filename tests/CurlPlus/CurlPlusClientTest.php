<?php

/**
 * Class CurlPlusClientTest
 */
class CurlPlusClientTest extends PHPUnit_Framework_TestCase
{
    /** @var string */
    public static $requestUrl = 'http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js';
    /** @var string */
    public static $requestUrl2 = 'http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js';
    /** @var string */
    public static $smallResponse = 'http://api.gvmtool.net/candidates/grails/default';

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::__construct
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanConstructCurlPlusClientWithNoArguments()
    {
        $curlClient = new \DCarbone\CurlPlus\CurlPlusClient();
        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusClient', $curlClient);

        return $curlClient;
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::__construct
     * @covers \DCarbone\CurlPlus\CurlPlusClient::getRequestHeaders
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanConstructCurlPlusClientWithHeaderArgument()
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
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanConstructCurlPlusClientWithHeaderArgumentContainingSpaceInName()
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
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionThrownWhenInvalidHeaderArrayStructurePassedToConstructor()
    {
        $curlClient = new \DCarbone\CurlPlus\CurlPlusClient(null, array(), array('test value'));
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::__construct
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionThrownWhenEmptyHeaderNamePassedToConstructor()
    {
        $curlClient = new \DCarbone\CurlPlus\CurlPlusClient(null, array(), array('' => 'text/xml'));
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::getRequestUrl
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @depends testCanConstructCurlPlusClientWithNoArguments
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
     * @depends testCanConstructCurlPlusClientWithNoArguments
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
     * @depends testCanConstructCurlPlusClientWithNoArguments
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
     * @depends testCanConstructCurlPlusClientWithNoArguments
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
     * @covers \DCarbone\CurlPlus\CurlPlusClient::getCurlOpts
     * @covers \DCarbone\CurlPlus\CurlOptHelper::createHumanReadableCurlOptArray
     * @covers \DCarbone\CurlPlus\CurlOptHelper::getStringNameForCurlOpt
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @uses \DCarbone\CurlPlus\CurlOptHelper
     * @depends testCanConstructCurlPlusObjectWithUrlAndCurlOptArrayParams
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
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @depends testCanConstructCurlPlusObjectWithUrlAndCurlOptArrayParams
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
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanAddRequestHeaderString()
    {
        $curlClient = new \DCarbone\CurlPlus\CurlPlusClient(self::$requestUrl);
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
     * @uses \DCarbone\CurlPlus\CurlPlusClient
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
     * @uses \DCarbone\CurlPlus\CurlPlusClient
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
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanSetCurlOptsWithArray()
    {
        $curlClient = new \DCarbone\CurlPlus\CurlPlusClient(self::$requestUrl);

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
     * @uses \DCarbone\CurlPlus\CurlPlusClient
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
     * @uses \DCarbone\CurlPlus\CurlPlusClient
     * @depends testCanRemoveCurlOpt
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanGetCurlOptValue(\DCarbone\CurlPlus\CurlPlusClient $curlClient)
    {
        $exists = $curlClient->getCurlOptValue(CURLOPT_RETURNTRANSFER);
        $notexists = $curlClient->getCurlOptValue(CURLOPT_SSL_VERIFYPEER);

        $this->assertTrue($exists);
        $this->assertNull($notexists);
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

        $data = $response->getResponse();

        $this->assertInternalType('string', $data);
        $this->assertRegExp('/^[1-9]+\.[0-9]+\.[0-9]+/', $data);
    }
}
