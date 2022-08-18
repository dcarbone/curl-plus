<?php

namespace DCarbone\CurlPlus\Tests;

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

use DCarbone\CurlPlus\CurlPlusClient;
use DCarbone\CurlPlus\Tests\Utils\CPParameters;
use PHPUnit\Framework\TestCase;

/**
 * Class CurlPlusClientTest
 */
class CurlPlusClientTest extends TestCase
{
    /**
     * Create tmp dir
     */
    protected function setUp(): void
    {
        if (!is_dir(CPParameters::$tmpDir)) {
            $tmpDir = mkdir(CPParameters::$tmpDir);
            if (!$tmpDir) {
                throw new \RuntimeException('Unable to create tmp dir needed for tests.');
            }
        }
    }

    /**
     * Removing any local files and temp dir
     */
    protected function tearDown(): void
    {
        if (is_dir(CPParameters::$tmpDir)) {
            $f = sprintf('%s/%s', CPParameters::$tmpDir, CPParameters::LOCAL_TAMALES_FILENAME);
            if (file_exists($f)) {
                @unlink($f);
            }
        }
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::__construct
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanConstructWithNoArguments()
    {
        $curlClient = new CurlPlusClient();
        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusClient', $curlClient);

        return $curlClient;
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::__construct
     * @covers \DCarbone\CurlPlus\CurlPlusClient::getRequestHeaders
     */
    public function testCanConstructWithHeaderArgument()
    {
        $curlClient = new CurlPlusClient(null, [], ['Content-Type' => 'text/xml']);

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusClient', $curlClient);

        $requestHeaders = $curlClient->getRequestHeaders();

        $this->assertIsArray($requestHeaders);
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
        $curlClient = new CurlPlusClient(null, [], ['Content-Type ' => 'text/xml']);

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusClient', $curlClient);

        $requestHeaders = $curlClient->getRequestHeaders();

        $this->assertIsArray($requestHeaders);
        $this->assertCount(1, $requestHeaders);
        $this->assertArrayHasKey('Content-Type', $requestHeaders);
        $this->assertContains('text/xml', $requestHeaders);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::__construct
     */
    public function testExceptionThrownWhenEmptyHeaderNamePassedToConstructor()
    {
        $this->expectException(\RuntimeException::class);
        new CurlPlusClient(null, [], ['' => 'text/xml']);
    }

    /**
     * @covers  \DCarbone\CurlPlus\CurlPlusClient::getRequestUrl
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     * @uses    \DCarbone\CurlPlus\CurlPlusClient
     * @depends testCanConstructWithNoArguments
     */
    public function testCanGetNullRequestUrlWithEmptyConstructorArguments(CurlPlusClient $curlClient)
    {
        $requestUrl = $curlClient->getRequestUrl();

        $this->assertNull($requestUrl);
    }

    /**
     * @covers  \DCarbone\CurlPlus\CurlPlusClient::getRequestHeaders
     * @depends testCanConstructWithNoArguments
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanGetEmptyCurlOptArrayWithEmptyConstructorArguments(CurlPlusClient $curlClient)
    {
        $requestHeaders = $curlClient->getRequestHeaders();

        $this->assertCount(0, $requestHeaders);
    }

    /**
     * @covers  \DCarbone\CurlPlus\CurlPlusClient::version
     * @depends testCanConstructWithNoArguments
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanGetCurlVersion(CurlPlusClient $curlClient)
    {
        $curlVersion = $curlClient->version();

        $this->assertEquals(curl_version(), $curlVersion);
    }

    /**
     * @covers  \DCarbone\CurlPlus\CurlPlusClient::execute
     * @depends testCanConstructWithNoArguments
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testExceptionThrownWhenTryingToExecuteRequestWithEmptyConstructorArguments(CurlPlusClient $curlClient)
    {
        $this->expectException(\RuntimeException::class);
        $curlClient->execute();
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::__construct
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanConstructWithOnlyUrlParam()
    {
        $curlClient = new CurlPlusClient(CPParameters::JQUERY1_11_1);

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusClient', $curlClient);

        return $curlClient;
    }

    /**
     * @covers  \DCarbone\CurlPlus\CurlPlusClient::getRequestUrl
     * @depends testCanConstructWithOnlyUrlParam
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanGetRequestUrlWithOnlyUrlParamConstructorArgument(CurlPlusClient $curlClient)
    {
        $requestUrl = $curlClient->getRequestUrl();

        $this->assertEquals(CPParameters::JQUERY1_11_1, $requestUrl);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::__construct
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanConstructCurlPlusObjectWithCurlOptUrlParam()
    {
        $curlClient = new CurlPlusClient(null, [CURLOPT_URL => CPParameters::JQUERY1_11_1]);

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusClient', $curlClient);

        return $curlClient;
    }

    /**
     * @covers  \DCarbone\CurlPlus\CurlPlusClient::getRequestUrl
     * @depends testCanConstructCurlPlusObjectWithCurlOptUrlParam
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanGetRequestUrlWithCurlOptUrlParamConstructorArgument(CurlPlusClient $curlClient)
    {
        $requestUrl = $curlClient->getRequestUrl();

        $this->assertEquals(CPParameters::JQUERY1_11_1, $requestUrl);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::__construct
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanConstructWithUrlAndCurlOptArrayParams()
    {
        $curlClient = new CurlPlusClient(
            CPParameters::JQUERY1_11_1,
            array(CURLOPT_URL => CPParameters::JQUERY1_11_1,
                CURLOPT_RETURNTRANSFER => true));

        $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusClient', $curlClient);

        return $curlClient;
    }

    /**
     * @covers  \DCarbone\CurlPlus\CurlPlusClient::getRequestUrl
     * @depends testCanConstructWithUrlAndCurlOptArrayParams
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanGetRequestUrlWithUrlAndCurlOptArrayConstructorArguments(CurlPlusClient $curlClient)
    {
        $requestUrl = $curlClient->getRequestUrl();

        $this->assertEquals(CPParameters::JQUERY1_11_1, $requestUrl);
    }

    /**
     * @covers  \DCarbone\CurlPlus\CurlPlusClient::getCurlOpts
     * @depends testCanConstructWithUrlAndCurlOptArrayParams
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanGetCurlOptArrayWithUrlAndCurlOptArrayConstructorArguments(CurlPlusClient $curlClient)
    {
        $curlOptArray = $curlClient->getCurlOpts();

        $this->assertCount(1, $curlOptArray);
        $this->assertArrayHasKey(CURLOPT_RETURNTRANSFER, $curlOptArray);
        $this->assertArrayNotHasKey(CURLOPT_URL, $curlOptArray);
        $this->assertContains(true, $curlOptArray);
    }

    /**
     * @covers  \DCarbone\CurlPlus\CurlPlusClient::getCurlOpts
     * @covers  \DCarbone\CurlPlus\CurlOptHelper::createHumanReadableCurlOptArray
     * @covers  \DCarbone\CurlPlus\CurlOptHelper::getStringNameForCurlOpt
     * @depends testCanConstructWithUrlAndCurlOptArrayParams
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanGetHumanReadableCurlOptArray(CurlPlusClient $curlClient)
    {
        $curlOptArray = $curlClient->getCurlOpts(true);

        $this->assertCount(1, $curlOptArray);
        $this->assertArrayHasKey('CURLOPT_RETURNTRANSFER', $curlOptArray);
        $this->assertArrayNotHasKey('CURLOPT_URL', $curlOptArray);
        $this->assertContains(true, $curlOptArray);
    }

    /**
     * @covers  \DCarbone\CurlPlus\CurlPlusClient::curlOptSet
     * @depends testCanConstructWithUrlAndCurlOptArrayParams
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanDetermineIfCurlOptIsSet(CurlPlusClient $curlClient)
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
        $curlClient = new CurlPlusClient(CPParameters::JQUERY1_11_1);
        $client = $curlClient->setRequestHeader('Accept', 'application/json');

        $this->assertEquals($curlClient, $client);

        $requestHeaders = $curlClient->getRequestHeaders();
        $this->assertIsArray($requestHeaders);
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
    public function testCanAddArrayOfHeadersToExistingRequestHeaderArray(CurlPlusClient $curlClient)
    {
        $curlClient->addRequestHeaders(['Content-Type' => 'text/html']);

        $requestHeaders = $curlClient->getRequestHeaders();
        $this->assertIsArray($requestHeaders);
        $this->assertCount(2, $requestHeaders);
        $this->assertArrayHasKey('Content-Type', $requestHeaders);
        $this->assertContains('text/html', $requestHeaders);

        return $curlClient;
    }

    /**
     * @covers  \DCarbone\CurlPlus\CurlPlusClient::setRequestHeaders
     * @covers  \DCarbone\CurlPlus\CurlPlusClient::getRequestHeaders
     * @depends testCanAddRequestHeaderString
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanOverwriteRequestHeaderArray(CurlPlusClient $curlClient)
    {
        $curlClient->setRequestHeaders(['Content-Type' => 'text/xml']);

        $requestHeaders = $curlClient->getRequestHeaders();

        $this->assertIsArray($requestHeaders);
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
        $curlClient = new CurlPlusClient(CPParameters::JQUERY1_11_1);

        $curlOpts = $curlClient->getCurlOpts();
        $this->assertIsArray($curlOpts);
        $this->assertCount(0, $curlOpts);

        $client = $curlClient->setCurlOpts([
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $this->assertEquals($curlClient, $client);

        $curlOpts = $curlClient->getCurlOpts();
        $this->assertIsArray($curlOpts);
        $this->assertCount(2, $curlOpts);
        $this->assertArrayHasKey(CURLOPT_RETURNTRANSFER, $curlOpts);
        $this->assertArrayHasKey(CURLOPT_SSL_VERIFYPEER, $curlOpts);

        return $curlClient;
    }

    /**
     * @covers  \DCarbone\CurlPlus\CurlPlusClient::removeCurlOpt
     * @covers  \DCarbone\CurlPlus\CurlPlusClient::getCurlOpts
     * @depends testCanSetCurlOptsWithArray
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanRemoveCurlOpt(CurlPlusClient $curlClient)
    {
        $curlClient->removeCurlOpt(CURLOPT_SSL_VERIFYPEER);

        $curlOpts = $curlClient->getCurlOpts();
        $this->assertIsArray($curlOpts);
        $this->assertCount(1, $curlOpts);
        $this->assertArrayHasKey(CURLOPT_RETURNTRANSFER, $curlOpts);
        $this->assertArrayNotHasKey(CURLOPT_SSL_VERIFYPEER, $curlOpts);

        return $curlClient;
    }

    /**
     * @covers  \DCarbone\CurlPlus\CurlPlusClient::getCurlOptValue
     * @depends testCanRemoveCurlOpt
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     */
    public function testCanGetCurlOptValue(CurlPlusClient $curlClient)
    {
        $exists = $curlClient->getCurlOptValue(CURLOPT_RETURNTRANSFER);
        $notExists = $curlClient->getCurlOptValue(CURLOPT_SSL_VERIFYPEER);

        $this->assertTrue($exists);
        $this->assertNull($notExists);
    }

    /**
     * @covers  \DCarbone\CurlPlus\CurlPlusClient::initialize
     * @covers  \DCarbone\CurlPlus\CurlPlusClient::close
     * @covers  \DCarbone\CurlPlus\CurlPlusClient::reset
     * @depends testCanConstructWithUrlAndCurlOptArrayParams
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanSetRequestUrlAndResetOpts(CurlPlusClient $curlClient)
    {
        $curlClient->initialize(CPParameters::JQUERY2_1_1, true);

        $this->assertEquals(CPParameters::JQUERY2_1_1, $curlClient->getRequestUrl());
        $this->assertCount(0, $curlClient->getCurlOpts());
        $this->assertCount(0, $curlClient->getRequestHeaders());

        return $curlClient;
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::execute
     * @covers \DCarbone\CurlPlus\CurlPlusResponse::__construct
     */
    public function testCanExecuteQueryDirectlyToOutputBufferWithoutResetting()
    {
        $curlClient = new CurlPlusClient(CPParameters::JQUERY2_1_1);

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
        $curlClient = new CurlPlusClient(CPParameters::JQUERY2_1_1);

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
     * @covers  \DCarbone\CurlPlus\CurlPlusClient::setCurlOpt
     * @depends testCanConstructWithUrlAndCurlOptArrayParams
     * @param \DCarbone\CurlPlus\CurlPlusClient $curlClient
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function testCanSetCurlOptValuesAfterConstruction(CurlPlusClient $curlClient)
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
     * @covers \DCarbone\CurlPlus\CurlPlusResponse::__construct
     */
    public function testCanParseResponseHeadersOutOfResponseBody()
    {
        $curlClient = new CurlPlusClient(
            CPParameters::SMALL_RESPONSE,
            array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false
            )
        );

        $response = $curlClient->execute();

        $responseHeaders = $response->responseHeaders;
        $this->assertIsArray($responseHeaders);
        $lastHeader = end($responseHeaders);
        $this->assertContains('HTTP/2 200', $lastHeader);

        $data = $response->responseBody;

        $this->assertIsString($data);
        $this->assertEquals('<html><head><title>Links</title></head><body>0 </body></html>', $data);
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::removeRequestHeader
     */
    public function testCanRemoveRequestHeader()
    {
        $client = new CurlPlusClient(null, array(), array('Accept' => 'application/json'));
        $client->removeRequestHeader('Accept');
        $this->assertCount(0, $client->getRequestHeaders());
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::execute
     */
    public function testExecutionWithCustomRequestHeaders()
    {
        $client = new CurlPlusClient(
            CPParameters::JQUERY1_11_1,
            array(CURLOPT_RETURNTRANSFER => true),
            array('Accept' => 'application/json'));

        $resp = $client->execute(true);

        $this->assertEquals(200, $resp->getHttpCode());
    }

    /**
     * @covers \DCarbone\CurlPlus\CurlPlusClient::execute
     */
    public function testGetFileResponseWithFileRequest()
    {
        $client = new CurlPlusClient(CPParameters::LIFE_SAVING_TAMALES);

        $f = sprintf('%s/%s', CPParameters::$tmpDir, CPParameters::LOCAL_TAMALES_FILENAME);

        $fh = fopen($f, 'w+');
        $this->assertIsResource($fh);
        if ($fh) {
            $client->setCurlOpt(CURLOPT_FILE, $fh);
            $response = $client->execute(true);

            $this->assertInstanceOf('\\DCarbone\\CurlPlus\\CurlPlusResponse', $response);
            $this->assertEquals($f, $response->filename);

            fclose($fh);
        }
    }
}
