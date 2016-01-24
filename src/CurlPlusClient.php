<?php namespace DCarbone\CurlPlus;

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

use DCarbone\CurlPlus\Response\CurlPlusFileResponse;
use DCarbone\CurlPlus\Response\CurlPlusResponse;

/**
 * Class CurlClient
 * @package DCarbone\CurlPlus
 */
class CurlPlusClient
{
    /** @var int */
    protected $state;

    /** @var resource */
    protected $ch = null;
    /** @var array */
    protected $requestHeaders = array();
    /** @var array */
    protected $curlOpts = array();
    /** @var string */
    protected $currentRequestUrl = null;

    /**
     * Constructor
     *
     * @param string $url
     * @param array $curlOpts
     * @param array $requestHeaders
     * @throws \InvalidArgumentException
     */
    public function __construct($url = null, array $curlOpts = array(), array $requestHeaders = array())
    {
        $this->state = CurlPlusClientState::STATE_NEW;

        if (null !== $url && !is_string($url))
            throw new \InvalidArgumentException('Argument 1 expected to be string or null, '.gettype($url).' seen.');

        // In case they use the deprecated way of setting the curl url.
        if (isset($curlOpts[CURLOPT_URL]))
        {
            $this->currentRequestUrl = $curlOpts[CURLOPT_URL];
            unset($curlOpts[CURLOPT_URL]);
            $this->state = CurlPlusClientState::STATE_INITIALIZED;
        }
        else if (is_string($url))
        {
            $this->currentRequestUrl = $url;
            $this->state = CurlPlusClientState::STATE_INITIALIZED;
        }

        $this->setCurlOpts($curlOpts);
        $this->setRequestHeaders($requestHeaders);
    }

    /**
     * Create a new CURL resource
     *
     * @param string $url
     * @param bool $reset
     * @return $this
     */
    public function initialize($url, $reset = true)
    {
        if ((bool)$reset)
            $this->reset();

        $this->currentRequestUrl = $url;

        $this->state = CurlPlusClientState::STATE_INITIALIZED;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getRequestUrl()
    {
        return $this->currentRequestUrl;
    }

    /**
     * Add a header string to the request
     *
     * @param string $name
     * @param string $value
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setRequestHeader($name, $value)
    {
        if (!is_string($name))
            throw new \InvalidArgumentException('Argument 1 expected to be string, '.gettype($name).' seen.');

        if (!is_string($value))
            throw new \InvalidArgumentException('Argument 2 expected to be string, '.gettype($value).' seen.');

        if (($name = trim($name)) === '')
            throw new \RuntimeException('Argument 1 cannot be empty string.');

        $this->requestHeaders[$name] = $value;

        return $this;
    }

    /**
     * Get the array of headers being sent.
     *
     * @return array
     */
    public function getRequestHeaders()
    {
        return $this->requestHeaders;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function addRequestHeaders(array $headers)
    {
        $this->requestHeaders = array_merge($this->requestHeaders, $headers);

        return $this;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function setRequestHeaders(array $headers)
    {
        $this->requestHeaders = array();
        foreach($headers as $k=>$v)
        {
            $this->setRequestHeader($k, $v);
        }

        return $this;
    }

    /**
     * @param string $name
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function removeRequestHeader($name)
    {
        if (!is_string($name))
            throw new \InvalidArgumentException('Argument 1 expected to be string, '.gettype($name).' seen.');

        if (isset($this->requestHeaders[$name]) || array_key_exists($name, $this->requestHeaders))
            unset($this->requestHeaders[$name]);

        return $this;
    }

    /**
     * Set CURL option
     *
     * @link http://www.php.net/manual/en/function.curl-setopt.php
     *
     * @param int  $opt curl option
     * @param mixed  $val curl option value
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setCurlOpt($opt, $val)
    {
        if (!is_int($opt))
        {
            throw new \InvalidArgumentException(sprintf(
                '%s::setCurlOpt - Argument 1 expected to be integer, %s seen.',
                get_class($this),
                gettype($opt)
            ));
        }

        $this->curlOpts[$opt] = $val;
        return $this;
    }

    /**
     * Set array of CURL options
     *
     * @link http://www.php.net/manual/en/function.curl-setopt-array.php
     *
     * @param array  $array CURL parameters
     * @return $this
     */
    public function setCurlOpts(array $array)
    {
        foreach($array as $opt=>$val)
        {
            $this->setCurlOpt($opt, $val);
        }

        return $this;
    }

    /**
     * @param bool $humanReadable
     * @return array
     */
    public function getCurlOpts($humanReadable = false)
    {
        if ((bool)$humanReadable)
            return CurlOptHelper::createHumanReadableCurlOptArray($this->curlOpts);

        return $this->curlOpts;
    }

    /**
     * @param int $opt
     * @return $this
     */
    public function removeCurlOpt($opt)
    {
        if ($this->curlOptSet($opt))
            unset($this->curlOpts[$opt]);

        return $this;
    }

    /**
     * Close the curl resource, if exists
     */
    public function close()
    {
        if (gettype($this->ch) === 'resource')
            curl_close($this->ch);

        $this->state = CurlPlusClientState::STATE_CLOSED;

        return $this;
    }

    /**
     * Reset to new state
     *
     * @return $this
     */
    public function reset()
    {
        $this->close();
        $this->curlOpts = array();
        $this->requestHeaders = array();
        $this->currentRequestUrl = null;

        $this->state = CurlPlusClientState::STATE_NEW;

        return $this;
    }

    /**
     * Returns true if passed in curl option has a value
     *
     * @param int $opt
     * @return bool
     */
    public function curlOptSet($opt)
    {
        return isset($this->curlOpts[$opt]) || array_key_exists($opt, $this->curlOpts);
    }

    /**
     * Returns value, if set, of curl option
     *
     * @param int $opt
     * @return mixed
     */
    public function getCurlOptValue($opt)
    {
        if ($this->curlOptSet($opt))
            return $this->curlOpts[$opt];

        return null;
    }

    /**
     * Execute CURL command
     *
     * @param bool $resetAfterExecution
     * @throws \RuntimeException
     * @return \DCarbone\CurlPlus\Response\CurlPlusResponseInterface
     */
    public function execute($resetAfterExecution = false)
    {
        if ($this->state === CurlPlusClientState::STATE_NEW)
        {
            throw new \RuntimeException(sprintf(
                '%s::execute - Could not execute request, curl has not been initialized.',
                get_class($this)
            ));
        }

        if ($this->state === CurlPlusClientState::STATE_EXECUTED)
            $this->close();

        if ($this->state === CurlPlusClientState::STATE_CLOSED)
            $this->initialize($this->currentRequestUrl, false);

        // Create curl handle resource
        $this->ch = curl_init($this->currentRequestUrl);

        // Set the Header array (if any)
        if (count($this->requestHeaders) > 0)
        {
            $headers = array();
            foreach($this->requestHeaders as $k=>$v)
            {
                $headers[] = vsprintf('%s: %s', array($k, $v));
            }
            $this->setCurlOpt(CURLOPT_HTTPHEADER, $headers);
        }

        // Return the Request Header as part of the curl_info array unless they specify otherwise
        if (!$this->curlOptSet(CURLINFO_HEADER_OUT))
            $this->setCurlOpt(CURLINFO_HEADER_OUT, true);

        // If the user has opted to return the data, rather than save to file or output directly,
        // attempt to get headers back in the response for later use if they have not specified
        // otherwise.
        if ($this->getCurlOptValue(CURLOPT_RETURNTRANSFER) && !$this->curlOptSet(CURLOPT_HEADER))
            $this->setCurlOpt(CURLOPT_HEADER, true);

        // Set the CURLOPTS
        curl_setopt_array($this->ch, $this->curlOpts);

        return $this->createResponse($resetAfterExecution);
    }

    /**
     * TODO: Come up with a better way to create response classes
     *
     * @param bool $resetAfterExecution
     * @return \DCarbone\CurlPlus\Response\CurlPlusResponseInterface
     */
    protected function createResponse($resetAfterExecution)
    {
        $this->state = CurlPlusClientState::STATE_EXECUTING;

        if (is_resource($this->getCurlOptValue(CURLOPT_FILE)))
        {
            $response = new CurlPlusFileResponse(
                curl_exec($this->ch),
                curl_getinfo($this->ch),
                curl_error($this->ch),
                $this->curlOpts
            );
        }
        else
        {
            $response = new CurlPlusResponse(
                curl_exec($this->ch),
                curl_getinfo($this->ch),
                curl_error($this->ch),
                $this->curlOpts);
        }

        $this->state = CurlPlusClientState::STATE_EXECUTED;

        if ($resetAfterExecution)
            $this->reset();

        return $response;
    }

    /**
     * Return the version of CURL currently implemented
     *
     * @return mixed
     */
    public function version()
    {
        return curl_version();
    }
}