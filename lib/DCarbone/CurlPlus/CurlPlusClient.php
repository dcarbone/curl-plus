<?php namespace DCarbone\CurlPlus;

use DCarbone\CurlPlus\Error\CurlErrorBase;
use DCarbone\CurlPlus\Response\CurlResponse;

/**
 * Class CurlClient
 * @package DCarbone\CurlPlus
 */
class CurlPlusClient
{
    /** @var resource */
    protected $ch = null;
    /** @var string */
    protected $accept = null;
    /** @var array */
    protected $requestHeaders = array();
    /** @var array */
    protected $curlOpts = array();
    /** @var string */
    protected $lastUrl = null;
    /** @var string */
    protected $lastResponse = null;
    /** @var string|mixed */
    protected $lastError = null;
    /** @var array|mixed */
    protected $lastInfo = null;
    /** @var array */
    protected $options = array();

    /**
     * @Constructor
     *
     * @param string $url
     * @param array $curlOpts
     * @param array $requestHeaders
     * @param array $options
     */
    public function __construct($url = null, array $curlOpts = array(), array $requestHeaders = array(), array $options = array())
    {
        $this->curlOpts = $curlOpts;
        $this->requestHeaders = $requestHeaders;
        $this->options = $options;

        // In case they use they deprecated way of setting the curl url.
        if (array_key_exists(CURLOPT_URL, $this->curlOpts))
        {
            $this->lastUrl = $this->curlOpts[CURLOPT_URL];
            $this->ch = curl_init($this->curlOpts[CURLOPT_URL]);
            unset($this->curlOpts[CURLOPT_URL]);
        }
        else if (is_string($url))
        {
            $this->ch = curl_init($url);
            $this->lastUrl = $url;
        }
    }

    /**
     * Get the last error value
     *
     * @return mixed|string
     */
    public function getLastError()
    {
        return $this->lastError;
    }

    /**
     * Get the last response value
     *
     * @return string
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * Return the last curl_getinfo array
     *
     * @return array|mixed
     */
    public function getLastInfo()
    {
        return $this->lastInfo;
    }

    /**
     * Create a new CURL resource
     *
     * @param string $url
     * @param bool $reset
     * @return $this
     */
    public function setRequestUrl($url, $reset = true)
    {
        if (gettype($this->ch) === 'resource')
            curl_close($this->ch);

        $this->ch = curl_init($url);

        if ($reset === true)
            $this->resetCurlOpts();

        return $this;
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
     * @param Mixed  $opt curl option
     * @param Mixed  $val curl option value
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
     * Get the opts set
     *
     * This function returns a pointer to the
     *
     * @return array
     */
    public function getCurlOpts()
    {
        return $this->curlOpts;
    }

    /**
     * Reset the curlopt and requestheader arrays
     *
     * @return $this
     */
    public function resetCurlOpts()
    {
        $this->curlOpts = array();
        $this->requestHeaders = array();

        return $this;
    }

    /**
     * Execute CURL command
     *
     * @param bool $close
     * @throws \Exception
     * @return \DCarbone\CurlPlus\Response\CurlResponse
     */
    public function execute($close = false)
    {
        if (gettype($this->ch) !== 'resource')
            throw new \Exception('No valid cURL resource found! (Are you trying to execute the same handle twice?)');

        // Set the Header array (if any)
        if (count($this->requestHeaders) > 0)
            $this->setCurlOpt(CURLOPT_HTTPHEADER, $this->requestHeaders);

        // Return the Header info unless they specify otherwise
        if (!array_key_exists(CURLINFO_HEADER_OUT, $this->curlOpts))
            $this->setCurlOpt(CURLINFO_HEADER_OUT, true);

        // Set the CURLOPTS
        curl_setopt_array($this->ch, $this->curlOpts);

        // Execute and get meta data
        $this->lastResponse = curl_exec($this->ch);
        $this->lastError = curl_error($this->ch);
        $this->lastInfo = curl_getinfo($this->ch);

        // Close the handle
        if ($close === true)
            $this->close();

        // Return the object.
        if ($this->lastError !== '' && $this->lastError !== false)
            return new CurlErrorBase($this->lastResponse, $this->lastInfo, $this->lastError, $this->curlOpts, $this->requestHeaders);

        return new CurlResponse($this->lastResponse, $this->lastInfo, $this->lastError, $this->curlOpts, $this->requestHeaders);
    }

    /**
     * Close the curl session or trigger an error
     *
     * @return void
     */
    public function close()
    {
        if (gettype($this->ch) === 'resource')
            curl_close($this->ch);
//        else
//            trigger_error('Tried to close non-resource.  Are you closing the curl session twice?');
    }

    /**
     * Return the version of CURL currently implemented
     *
     * @name version
     * @access public
     * @return Mixed
     */
    public function version()
    {
        return curl_version();
    }
}