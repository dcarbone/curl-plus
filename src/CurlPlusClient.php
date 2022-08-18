<?php declare(strict_types=1);

namespace DCarbone\CurlPlus;

/*
    Copyright 2012-2022  Daniel Paul Carbone (daniel.p.carbone@gmail.com)
s
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
 * Class CurlClient
 * @package DCarbone\CurlPlus
 */
class CurlPlusClient
{
    private const STATE_NEW         = 10;
    private const STATE_INITIALIZED = 20;
    private const STATE_EXECUTING   = 30;
    private const STATE_EXECUTED    = 40;
    private const STATE_CLOSED      = 50;
    
    /** @var int */
    protected int $state;

    /** @var resource */
    protected $ch = null;
    /** @var array */
    protected array $requestHeaders = [];
    /** @var array */
    protected array $curlOpts = [];
    /** @var string|null */
    protected ?string $currentRequestUrl = null;

    /**
     * Constructor
     *
     * @param string|null $url
     * @param array $curlOpts
     * @param array $requestHeaders
     * @throws \InvalidArgumentException
     */
    public function __construct(?string $url = null, array $curlOpts = [], array $requestHeaders = [])
    {
        $this->state = self::STATE_NEW;

        if (null !== $url && !is_string($url)) {
            throw new \InvalidArgumentException('Argument 1 expected to be string or null, ' . gettype($url) . ' seen.');
        }

        // In case they use the deprecated way of setting the curl url.
        if (isset($curlOpts[CURLOPT_URL])) {
            $this->currentRequestUrl = $curlOpts[CURLOPT_URL];
            unset($curlOpts[CURLOPT_URL]);
            $this->state = self::STATE_INITIALIZED;
        } else if (is_string($url)) {
            $this->currentRequestUrl = $url;
            $this->state = self::STATE_INITIALIZED;
        }

        $this->setCurlOpts($curlOpts);
        $this->setRequestHeaders($requestHeaders);
    }

    /**
     * Create a new CURL resource
     *
     * @param string|null $url
     * @param bool $reset
     * @return $this
     */
    public function initialize(?string $url, bool $reset = true): static
    {
        if ($reset) {
            $this->reset();
        }

        $this->currentRequestUrl = $url;

        $this->state = self::STATE_INITIALIZED;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getRequestUrl(): ?string
    {
        return $this->currentRequestUrl;
    }

    /**
     * Add a header string to the request
     *
     * @param string $name
     * @param string $value
     * @return $this
     * @throws \RuntimeException
     */
    public function setRequestHeader(string $name, string $value): static
    {
        if (($name = trim($name)) === '') {
            throw new \RuntimeException('Argument 1 cannot be empty string.');
        }

        $this->requestHeaders[$name] = $value;

        return $this;
    }

    /**
     * Get the array of headers being sent.
     *
     * @return array
     */
    public function getRequestHeaders(): array
    {
        return $this->requestHeaders;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function addRequestHeaders(array $headers): static
    {
        $this->requestHeaders = array_merge($this->requestHeaders, $headers);

        return $this;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function setRequestHeaders(array $headers): static
    {
        $this->requestHeaders = array();
        foreach($headers as $k=>$v) {
            $this->setRequestHeader($k, $v);
        }

        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function removeRequestHeader(string $name): static
    {
        if (isset($this->requestHeaders[$name]) || array_key_exists($name, $this->requestHeaders)) {
            unset($this->requestHeaders[$name]);
        }

        return $this;
    }

    /**
     * Set CURL option
     *
     * @link http://www.php.net/manual/en/function.curl-setopt.php
     *
     * @param int $opt curl option
     * @param mixed  $val curl option value
     * @return $this
     */
    public function setCurlOpt(int $opt, mixed $val): static
    {
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
    public function setCurlOpts(array $array): static
    {
        foreach($array as $opt => $val) {
            $this->setCurlOpt($opt, $val);
        }

        return $this;
    }

    /**
     * @param bool $humanReadable
     * @return array
     */
    public function getCurlOpts(bool $humanReadable = false): array
    {
        if ($humanReadable) {
            return CurlOptHelper::createHumanReadableCurlOptArray($this->curlOpts);
        }

        return $this->curlOpts;
    }

    /**
     * @param int $opt
     * @return $this
     */
    public function removeCurlOpt(int $opt): static
    {
        if ($this->curlOptSet($opt)) {
            unset($this->curlOpts[$opt]);
        }

        return $this;
    }

    /**
     * Close the curl resource, if exists
     */
    public function close(): static
    {
        if ('resource' === gettype($this->ch)) {
            curl_close($this->ch);
        }

        $this->state = self::STATE_CLOSED;

        return $this;
    }

    /**
     * Reset to new state
     *
     * @return $this
     */
    public function reset(): static
    {
        $this->close();
        $this->curlOpts = array();
        $this->requestHeaders = array();
        $this->currentRequestUrl = null;

        $this->state = self::STATE_NEW;

        return $this;
    }

    /**
     * Returns true if passed in curl option has a value
     *
     * @param int $opt
     * @return bool
     */
    public function curlOptSet(int $opt): bool
    {
        return isset($this->curlOpts[$opt]) || array_key_exists($opt, $this->curlOpts);
    }

    /**
     * Returns value, if set, of curl option
     *
     * @param int $opt
     * @return mixed
     */
    public function getCurlOptValue(int $opt): mixed
    {
        if ($this->curlOptSet($opt)) {
            return $this->curlOpts[$opt];
        }

        return null;
    }

    /**
     * Execute CURL command
     *
     * @param bool $resetAfterExecution
     * @return \DCarbone\CurlPlus\CurlPlusResponse
     *@throws \RuntimeException
     */
    public function execute(bool $resetAfterExecution = false): CurlPlusResponse
    {
        $this->buildRequest();

        $response = new CurlPlusResponse(
            curl_exec($this->ch),
            curl_getinfo($this->ch),
            curl_error($this->ch),
            $this->getCurlOpts()
        );

        if ($resetAfterExecution) {
            $this->reset();
        }

        return $response;
    }

    /**
     * Attempts to set up the client for a request, throws an exception if it cannot
     */
    protected function buildRequest(): void
    {
        if (self::STATE_NEW === $this->state) {
            throw new \RuntimeException(sprintf(
                '%s::execute - Could not execute request, curl has not been initialized.',
                get_class($this)
            ));
        }

        if (self::STATE_EXECUTED === $this->state) {
            $this->close();
        }

        if (self::STATE_CLOSED === $this->state) {
            $this->initialize($this->currentRequestUrl, false);
        }

        // Create curl handle resource
        $this->ch = curl_init($this->currentRequestUrl);
        if (false === $this->ch) {
            throw new \RuntimeException(sprintf(
                'Unable to initialize curl with url: %s',
                $this->getRequestUrl()
            ));
        }

        // Set the Header array (if any)
        if (count($this->requestHeaders) > 0) {
            $headers = array();
            foreach($this->requestHeaders as $k=>$v) {
                $headers[] = vsprintf('%s: %s', array($k, $v));
            }
            $this->setCurlOpt(CURLOPT_HTTPHEADER, $headers);
        }

        // Return the Request Header as part of the curl_info array unless they specify otherwise
        if (!$this->curlOptSet(CURLINFO_HEADER_OUT)) {
            $this->setCurlOpt(CURLINFO_HEADER_OUT, true);
        }

        // If the user has opted to return the data, rather than save to file or output directly,
        // attempt to get headers back in the response for later use if they have not specified
        // otherwise.
        if ($this->getCurlOptValue(CURLOPT_RETURNTRANSFER) && !$this->curlOptSet(CURLOPT_HEADER)) {
            $this->setCurlOpt(CURLOPT_HEADER, true);
        }

        // Set the CURLOPTS
        if (false === curl_setopt_array($this->ch, $this->curlOpts)) {
            throw new \RuntimeException(sprintf(
                'Unable to specify curl options, please ensure you\'re passing in valid constants.  Specified opts: %s',
                json_encode($this->getCurlOpts())
            ));
        }
    }

    /**
     * Return the version of CURL currently implemented
     *
     * @return array|bool
     */
    public function version(): array|bool
    {
        return curl_version();
    }
}