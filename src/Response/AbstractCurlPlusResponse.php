<?php namespace DCarbone\CurlPlus\Response;

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