<?php namespace DCarbone\CurlPlus;

use DCarbone\CurlPlus\Response\CurlPlusFileResponse;
use DCarbone\CurlPlus\Response\CurlPlusResponse;

/**
 * Class CurlClient
 * @package DCarbone\CurlPlus
 */
class CurlPlusClient
{
    /**
     * Default state is "0" or "NEW"
     * @var int
     */
    protected $state = 0;

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
     * @return \DCarbone\CurlPlus\CurlPlusClient
     */
    public function __construct($url = null, array $curlOpts = array(), array $requestHeaders = array())
    {
        // In case they use they deprecated way of setting the curl url.
        if (isset($curlOpts[CURLOPT_URL]))
        {
            $this->currentRequestUrl = $curlOpts[CURLOPT_URL];
            unset($curlOpts[CURLOPT_URL]);
            $this->state = StateEnumeration::STATE_INITIALIZED;
        }
        else if (is_string($url))
        {
            $this->currentRequestUrl = $url;
            $this->state = StateEnumeration::STATE_INITIALIZED;
        }

        $this->curlOpts = $curlOpts;
        $this->requestHeaders = $requestHeaders;
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
        if ($reset === true)
            $this->reset();

        $this->currentRequestUrl = $url;

        $this->state = StateEnumeration::STATE_INITIALIZED;

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
     * @param string $string
     * @return $this
     */
    public function addRequestHeaderString($string)
    {
        $this->requestHeaders[] = $string;
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
     * @return void
     */
    public function setRequestHeaders(array $headers)
    {
        $this->requestHeaders = $headers;
    }

    /**
     * Set CURL option
     *
     * @link http://www.php.net/manual/en/function.curl-setopt.php
     *
     * @param mixed  $opt curl option
     * @param mixed  $val curl option value
     * @return $this
     */
    public function setCurlOpt($opt, $val)
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
    public function setCurlOpts(array $array)
    {
        foreach($array as $k=>$v)
            $this->curlOpts[$k] = $v;

        return $this;
    }

    /**
     * @param bool $humanReadable
     * @return array
     */
    public function getCurlOpts($humanReadable = false)
    {
        if ($humanReadable)
            return CurlOptHelper::createHumanReadableCurlOptArray($this->curlOpts);

        return $this->curlOpts;
    }

    /**
     * @param $opt
     * @return void
     */
    public function removeCurlOpt($opt)
    {
        if (isset($this->curlOpts[$opt]))
            unset($this->curlOpts[$opt]);
    }

    /**
     * Close the curl resource, if exists
     */
    public function close()
    {
        if (gettype($this->ch) === 'resource')
            curl_close($this->ch);

        $this->state = StateEnumeration::STATE_CLOSED;
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

        $this->state = StateEnumeration::STATE_NEW;

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
        return isset($this->curlOpts[$opt]);
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
     * @param bool $resetAfterExecution
     * @return CurlPlusFileResponse|CurlPlusResponse
     */
    protected function createResponse($resetAfterExecution)
    {
        $this->state = StateEnumeration::STATE_EXECUTING;

        if (isset($this->curlOpts[CURLOPT_FILE]))
        {
            $response = new CurlPlusFileResponse(
                curl_exec($this->ch),
                curl_getinfo($this->ch),
                curl_error($this->ch),
                $this->curlOpts);
        }
        else
        {
            $response = new CurlPlusResponse(
                curl_exec($this->ch),
                curl_getinfo($this->ch),
                curl_error($this->ch),
                $this->curlOpts);
        }

        $this->state = StateEnumeration::STATE_EXECUTED;

        if ($resetAfterExecution)
            $this->reset();

        return $response;
    }

    /**
     * Execute CURL command
     *
     * @param bool $resetAfterExecution
     * @throws \RuntimeException
     * @return \DCarbone\CurlPlus\Response\CurlPlusResponse
     */
    public function execute($resetAfterExecution = false)
    {
        if ($this->state === StateEnumeration::STATE_NEW)
            throw new \RuntimeException(
                get_class($this).'::execute - Could not execute request, curl has not be initialized.'
            );

        if ($this->state === StateEnumeration::STATE_EXECUTED)
            $this->close();

        if ($this->state === StateEnumeration::STATE_CLOSED)
            $this->initialize($this->currentRequestUrl, false);

        // Create curl handle resource
        $this->ch = curl_init($this->currentRequestUrl);

        // Set the Header array (if any)
        if (count($this->requestHeaders) > 0)
            $this->setCurlOpt(CURLOPT_HTTPHEADER, $this->requestHeaders);

        // Return the Header info unless they specify otherwise
        if (!$this->curlOptSet(CURLINFO_HEADER_OUT))
            $this->setCurlOpt(CURLINFO_HEADER_OUT, true);

        // Output response header into body if body is being returned to memory, rather than output buffer
        if (!$this->curlOptSet(CURLOPT_HEADER) && $this->getCurlOptValue(CURLOPT_RETURNTRANSFER) == true)
            $this->setCurlOpt(CURLOPT_HEADER, true);

        // Set the CURLOPTS
        curl_setopt_array($this->ch, $this->curlOpts);

        return $this->createResponse($resetAfterExecution);
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