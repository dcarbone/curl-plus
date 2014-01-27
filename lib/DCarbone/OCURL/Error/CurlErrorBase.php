<?php namespace DCarbone\OCURL\Error;

/**
 * Class CurlErrorBase
 * @package DCarbone\OCURL\Error
 */
class CurlErrorBase
{
    /**
     * @var string
     */
    protected $response;

    /**
     * @var mixed
     */
    protected $info;

    /**
     * @var string
     */
    protected $error;
    /**
     * @var array
     */
    protected $curlOpts;
    /**
     * @var array
     */
    protected $httpHeaders;

    /**
     * @param $response
     * @param $info
     * @param $error
     * @param array $curlOpts
     * @param array $httpHeaders
     */
    public function __construct($response, $info, $error, array $curlOpts, array $httpHeaders)
    {
        $this->response = $response;
        $this->info = $info;
        $this->error = $error;
        $this->curlOpts = $curlOpts;
        $this->httpHeaders = $httpHeaders;
    }

    /**
     * Get the response (if any)
     *
     * @return string
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
    public function getHttpHeaders()
    {
        return $this->httpHeaders;
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