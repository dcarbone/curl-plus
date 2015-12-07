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
 * Class CurlPlusResponse
 * @package DCarbone\CurlPlus\Response
 */
class CurlPlusResponse implements CurlPlusResponseInterface
{
    /** @var string */
    protected $responseBody = null;
    /** @var string */
    protected $responseHeaders = null;

    /** @var array */
    protected $requestHeadersArray;
    /** @var array */
    protected $responseHeadersArray;

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
     * @param string $response
     * @param array $info
     * @param mixed $error
     * @param array $curlOpts
     * @return \DCarbone\CurlPlus\Response\CurlPlusResponse
     */
    public function __construct($response, $info, $error, array $curlOpts)
    {
        if (is_string($response) &&
            isset($curlOpts[CURLOPT_HEADER]) &&
            $curlOpts[CURLOPT_HEADER] == true &&
            stripos($response, 'http/') === 0 &&
            ($pos = strpos($response, "\r\n\r\n")) !== false)
        {
            $responseHeaderString = '';
            while (stripos($response, 'http/') === 0 && ($pos = strpos($response, "\r\n\r\n")) !== false)
            {
                $pos += 4;
                $responseHeaderString = substr($response, 0, $pos);
                if (strlen($response) === $pos)
                    $response = '';
                else
                    $response = substr($response, $pos);
            }

            $this->responseHeaders = $responseHeaderString;
            $this->responseBody = $response;
        }
        else
        {
            $this->responseBody = $response;
        }

        $this->info = $info;
        $this->error = $error;

        if (isset($this->info['http_code']))
            $this->httpCode = (int)$this->info['http_code'];

        $this->curlOpts = $curlOpts;
    }

    /**
     * Get CURL error
     *
     * @name getError
     * @access public
     * @return Mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Get CURL info
     *
     * @name getInfo
     * @access public
     * @return array
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @deprecated Since 2.0: Use getResponseBody instead
     *
     * @return mixed
     */
    public function getResponse()
    {
        return $this->getResponseBody();
    }

    /**
     * @return string
     */
    public function getResponseBody()
    {
        return $this->responseBody;
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
     * @param bool $asArray
     * @return array|null
     */
    public function getRequestHeaders($asArray = false)
    {
        if (isset($this->info['request_header']))
        {
            if ($asArray)
            {
                if (!isset($this->requestHeadersArray))
                    $this->requestHeadersArray = $this->_parseHeaders($this->info['request_header']);

                return $this->requestHeadersArray;
            }
            return $this->info['request_header'];
        }

        return null;
    }

    /**
     * @param bool $asArray
     * @return null|string
     */
    public function getResponseHeaders($asArray = false)
    {
        if ($asArray)
        {
            if (!isset($this->responseHeadersArray))
                $this->responseHeadersArray = $this->_parseHeaders($this->responseHeaders);

            return $this->responseHeadersArray;
        }

        return $this->responseHeaders;
    }

    /**
     * Returns response as string
     *
     * @name __toString
     * @return string  curl response string
     */
    public function __toString()
    {
        return (string)$this->responseBody;
    }

    /**
     * Create associative array of response header string
     * @param $headerString
     * @return array
     */
    private function _parseHeaders($headerString)
    {
        $tmp = array();
        foreach(explode("\r\n", $headerString) as $header)
        {
            if (strpos($header, ':') === false)
            {
                $tmp[] = $header;
            }
            else
            {
                list($name, $value) = explode(':', $header, 2);
                $tmp[trim($name)] = trim($value);
            }
        }

        return array_filter($tmp);
    }
}