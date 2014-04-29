<?php namespace DCarbone\CurlPlus\Response;

/**
 * Class CurlResultBase
 * @package DCarbone\CurlPlus\Response
 */
class CurlResponse implements ICurlResponse
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
     * Constructor
     *
     * @param string $response
     * @param array $info
     * @param mixed $error
     * @param array $curlOpts
     * @param array $httpHeaders
     * @return \DCarbone\CurlPlus\Response\CurlResponse
     */
    public function __construct($response, $info, $error, array $curlOpts, array $httpHeaders)
    {
        $this->response = $response;
        $this->info = $info;
        $this->error = $error;

        if (isset($this->info['http_code']))
            $this->httpCode = (int)$this->info['http_code'];

        $this->curlOpts = $curlOpts;
        $this->httpHeaders = $httpHeaders;
    }

    /**
     * Get CURL error
     *
     * @name getError
     * @access public
     * @return Mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Get CURL info
     *
     * @name getInfo
     * @access public
     * @return array
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Returns response as string
     *
     * @name __toString
     * @return string  curl response string
     */
    public function __toString()
    {
        return (string)$this->response;
    }

    /**
     * Get the response from the Curl Request
     *
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Return response HTTP code
     *
     * @return int
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    public function getQueryHeaders()
    {
        // TODO: Implement getQueryHeaders() method.
    }

    public function getResponseHeaders()
    {
        // TODO: Implement getResponseHeaders() method.
    }
}