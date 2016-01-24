<?php namespace DCarbone\CurlPlus\Response;

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
 * Class AbstractCurlPlusResponse
 * @package DCarbone\CurlPlus\Response
 */
abstract class AbstractCurlPlusResponse implements CurlPlusResponseInterface
{
    /** @var array */
    protected $requestHeadersArray;

    /** @var array */
    protected $info = null;
    /** @var string */
    protected $error = null;

    /** @var integer */
    protected $httpCode = null;

    /** @var array */
    protected $curlOpts = array();

    /**
     * Constructor
     *
     * @param mixed $response
     * @param array $info
     * @param string $error
     * @param array $curlOpts
     */
    public function __construct($response, $info, $error, array $curlOpts)
    {
        $this->info = $info;
        $this->error = $error;

        if (isset($this->info['http_code']))
            $this->httpCode = (int)$this->info['http_code'];

        $this->curlOpts = $curlOpts;

        $this->parseResponse($response);
    }

    /**
     * Get CURL error
     *
     * @return string|bool
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Get CURL info
     *
     * @return array
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Return response HTTP code
     *
     * @return int
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * @return array
     */
    public function getRequestCurlOpts()
    {
        return $this->curlOpts;
    }

    /**
     * @param bool $asArray
     * @return null|string|array
     */
    public function getRequestHeaders($asArray = false)
    {
        if (isset($this->info['request_header']))
        {
            if ($asArray)
            {
                if (!isset($this->requestHeadersArray))
                    $this->requestHeadersArray = $this->headerStringToArray($this->info['request_header']);

                return $this->requestHeadersArray;
            }
            return $this->info['request_header'];
        }

        return null;
    }

    /**
     * Create associative array of response header string
     *
     * @param string $headerString
     * @return array
     */
    protected function headerStringToArray($headerString)
    {
        $tmp = array();
        foreach(explode("\r\n\r\n", $headerString) as $header)
        {
            $headerData = array();
            foreach(explode("\r\n", $header) as $headerEntry)
            {
                if (strpos($headerEntry, ':') === false)
                {
                    $headerData[] = $headerEntry;
                }
                else
                {
                    list($name, $value) = explode(':', $headerEntry, 2);
                    $headerData[trim($name)] = trim($value);
                }
            }
            $tmp[] = array_filter($headerData);
        }

        return array_filter($tmp);
    }

    /**
     * @param mixed $response
     * @return void
     */
    abstract protected function parseResponse($response);
}