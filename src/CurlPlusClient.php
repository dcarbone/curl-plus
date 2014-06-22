<?php namespace DCarbone\CurlPlus;

use DCarbone\CurlPlus\Error\CurlErrorBase;
use DCarbone\CurlPlus\Response\CurlPlusFileResponse;
use DCarbone\CurlPlus\Response\CurlPlusResponse;

/**
 * Class CurlClient
 * @package DCarbone\CurlPlus
 */
class CurlPlusClient implements ICurlPlusContainer
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
        $this->curlOpts = $curlOpts;
        $this->requestHeaders = $requestHeaders;

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
     * Create a new CURL resource
     *
     * @param string $url
     * @param bool $reset
     * @return $this
     */
    public function setRequestUrl($url, $reset = true)
    {
        if (gettype($this->ch) === 'resource')
            $this->close();

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
     * @param $opt
     * @return void
     */
    public function removeCurlOpt($opt)
    {
        if (isset($this->curlOpts[$opt]))
            unset($this->curlOpts[$opt]);
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
     * @param bool $close
     * @param bool $resetOpts
     * @return CurlPlusFileResponse|CurlPlusResponse
     */
    protected function createResponse($close, $resetOpts)
    {
        if (array_key_exists(CURLOPT_FILE, $this->curlOpts))
            $response = new CurlPlusFileResponse(
                curl_exec($this->ch),
                curl_getinfo($this->ch),
                curl_error($this->ch),
                $this->curlOpts,
                $this->requestHeaders
            );
        else
            $response = new CurlPlusResponse(
            curl_exec($this->ch),
            curl_getinfo($this->ch),
            curl_error($this->ch),
            $this->curlOpts,
            $this->requestHeaders
        );

        // Close the handle
        if ($close === true)
            $this->close($resetOpts);

        return $response;
    }

    /**
     * Execute CURL command
     *
     * @param bool $close
     * @param bool $resetOpts
     * @throws \Exception
     * @return \DCarbone\CurlPlus\Response\CurlPlusResponse
     */
    public function execute($close = false, $resetOpts = false)
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

        return $this->createResponse($close, $resetOpts);
    }

    /**
     * Close the curl session or trigger an error
     *
     * @param bool $resetOpts
     * @return void
     */
    public function close($resetOpts = false)
    {
        if (gettype($this->ch) === 'resource')
            curl_close($this->ch);

        if ($resetOpts === true)
            $this->resetCurlOpts();
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

    /**
     * @throws \BadMethodCallException
     */
    public function &getClient()
    {
        throw new \BadMethodCallException('CurlPlusClient::getClient - Do not call getClient on base CurlPlusClient object.');
    }
}