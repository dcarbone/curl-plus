<?php namespace DCarbone\OCURL;

use DCarbone\OCURL\Error\CurlErrorBase;
use DCarbone\OCURL\Response\CurlResponse;

/**
 * Class CurlClient
 * @package DCarbone\OCURL
 */
class CurlClient
{
    /**
     * @var null|resource
     */
    protected $ch = null;

    /**
     * @var string|null
     */
    protected $accept = null;

    /**
     * @var array
     */
    protected $httpHeaders = array();

    /**
     * @var array
     */
    protected $curlOpts = array();

    /**
     * @var string
     */
    protected $lastUrl = null;

    /**
     * @var string
     */
    protected $lastResponse = null;

    /**
     * @var string|mixed
     */
    protected $lastError = null;

    /**
     * @var array|mixed
     */
    protected $lastInfo = null;

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @Constructor
     *
     * @param string $url
     * @param array $curlOpts
     * @param array $httpHeaders
     * @param array $options
     */
    public function __construct($url = null, array $curlOpts = array(), array $httpHeaders = array(), array $options = array())
    {
        $this->curlOpts = $curlOpts;
        $this->httpHeaders = $httpHeaders;
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
    public function setURL($url, $reset = true)
    {
        if (gettype($this->ch) === 'resource')
            curl_close($this->ch);

        $this->ch = curl_init($url);

        if ($reset === true)
            $this->resetOpts();

        return $this;
    }

    /**
     * Add a header string to the request
     *
     * @param $string
     * @return $this
     */
    public function addHTTPHeaderString($string)
    {
        $this->httpHeaders[] = $string;
        return $this;
    }

    /**
     * Get the array of headers being sent.
     *
     * @return array
     */
    public function getHTTPHeaderArray()
    {
        return $this->httpHeaders;
    }

    /**
     * Set CURL option
     *
     * @link http://www.php.net/manual/en/function.curl-setopt.php
     *
     * @name setOpt
     * @param Mixed  $opt curl option
     * @param Mixed  $val curl option value
     * @return $this
     */
    public function setOpt($opt, $val)
    {
        $this->curlOpts[$opt] = $val;
        return $this;
    }

    /**
     * Set Array of CURL options
     *
     * @link http://www.php.net/manual/en/function.curl-setopt-array.php
     *
     * @name setOptArray
     * @param Array  $array CURL parameters
     * @return $this
     */
    public function setOptArray(Array $array)
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
    public function getOpts()
    {
        return $this->curlOpts;
    }

    /**
     * Reset the curlopt and httpheader arrays
     *
     * @return $this
     */
    public function resetOpts()
    {
        $this->curlOpts = array();
        $this->httpHeaders = array();

        return $this;
    }

    /**
     * Execute CURL command
     *
     * @throws \Exception
     * @return \DCarbone\OCURL\Response\CurlResponse
     */
    public function execute()
    {
        if (gettype($this->ch) !== 'resource')
            throw new \Exception('No valid cURL resource found! (Are you trying to execute the same handle twice?)');

        // Set the Header array (if any)
        if (count($this->httpHeaders) > 0)
            $this->setOpt(CURLOPT_HTTPHEADER, $this->httpHeaders);

        // Return the Header info unless they specify otherwise
        if (!array_key_exists(CURLINFO_HEADER_OUT, $this->curlOpts))
            $this->setOpt(CURLINFO_HEADER_OUT, true);

        // Set the CURLOPTS
        curl_setopt_array($this->ch, $this->curlOpts);

        // Execute and get meta data
        $this->lastResponse = curl_exec($this->ch);
        $this->lastError = curl_error($this->ch);
        $this->lastInfo = curl_getinfo($this->ch);

        // Close the handle
        $this->close();

        // Return the object.
        if ($this->lastError !== '' && $this->lastError !== false)
            return new CurlErrorBase($this->lastResponse, $this->lastInfo, $this->lastError, $this->curlOpts, $this->httpHeaders);

        return new CurlResponse($this->lastResponse, $this->lastInfo, $this->lastError, $this->curlOpts, $this->httpHeaders);
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
        else
            trigger_error('Tried to close non-resource.  Are you closing the curl session twice?');
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