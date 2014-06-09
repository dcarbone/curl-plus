<?php namespace DCarbone\CurlPlus\Error;

/**
 * Class CurlErrorBase
 * @package DCarbone\CurlPlus\Error
 */
class CurlErrorBase
{
    /** @var string */
    protected $response = null;
    /** @var array */
    protected $info = null;
    /** @var string */
    protected $error = null;
    /** @var integer */
    protected $httpCode = 404;
    /** @var array */
    protected $curlOpts = array();
    /** @var array */
    protected $httpHeaders = array();

    /**
     * @param $response
     * @param $info
     * @param $error
     * @param array $curlOpts
     * @param array $requestHeaders
     */
    public function __construct($response, $info, $error, array $curlOpts, array $requestHeaders)
    {
        $this->response = $response;
        $this->info = $info;

        if (isset($this->info['http_code']))
            $this->httpCode = (int)$this->info['http_code'];

        $this->error = $error;
        $this->curlOpts = $curlOpts;
        $this->requestHeaders = $requestHeaders;
    }

    /**
     * Get the response (if any)
     *
     * @return null|string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Get the curl_info array
     *
     * @return mixed
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * Get the Error string
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Get the curlopts set in the client
     *
     * @return array
     */
    public function getCurlOpts()
    {
        return $this->curlOpts;
    }

    /**
     * Get the http headers set in the client
     *
     * @return array
     */
    public function getRequestHeaders()
    {
        return $this->requestHeaders;
    }

    /**
     * ToString magic method
     *
     * @return string
     */
    public function __toString()
    {
        return $this->error;
    }
}