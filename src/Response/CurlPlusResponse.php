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
class CurlPlusResponse extends AbstractCurlPlusResponse
{
    /** @var string */
    protected $responseBody = null;
    /** @var string */
    protected $responseHeaders = null;

    /** @var array */
    protected $responseHeadersArray;

    /**
     * @param bool $asArray
     * @return null|string|array
     */
    public function getResponseHeaders($asArray = false)
    {
        if ($asArray && null !== $this->responseHeaders)
        {
            if (!isset($this->responseHeadersArray))
                $this->responseHeadersArray = $this->headerStringToArray($this->responseHeaders);

            return $this->responseHeadersArray;
        }

        return $this->responseHeaders;
    }

    /**
     * @return string
     */
    public function getResponseBody()
    {
        return $this->responseBody;
    }

    /**
     * Returns response as string
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->responseBody;
    }

    /**
     * @param mixed $response
     */
    protected function parseResponse($response)
    {
        if (isset($this->curlOpts[CURLOPT_NOBODY]) && $this->curlOpts[CURLOPT_NOBODY] == true)
        {
            $this->responseBody = null;
            if (isset($this->curlOpts[CURLOPT_HEADER]) && $this->curlOpts[CURLOPT_HEADER])
                $this->responseHeaders = $response;
            else
                $this->responseHeaders = null;
        }
        else if (is_string($response) && isset($this->curlOpts[CURLOPT_HEADER]) && $this->curlOpts[CURLOPT_HEADER] == true)
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
            $this->responseHeaders = null;
            $this->responseBody = $response;
        }
    }
}