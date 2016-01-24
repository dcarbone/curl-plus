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