<?php namespace DCarbone\CurlPlus\Response;

/**
 * Class AbstractCurlPlusResponse
 * @package DCarbone\CurlPlus\Response
 */
abstract class AbstractCurlPlusResponse implements ICurlPlusResponse
{
    /** @var string */
    protected $response = null;
    /** @var string */
    protected $responseHeaders = null;
    /** @var array */
    protected $info = null;
    /** @var string */
    protected $error = null;
    /** @var integer */
    protected $httpCode = 404;
    /** @var array */
    protected $curlOpts = array();

    /**
     * Constructor
     *
     * @param string $response
     * @param array $info
     * @param mixed $error
     * @param array $curlOpts
     * @return \DCarbone\CurlPlus\Response\AbstractCurlPlusResponse
     */
    public function __construct($response, $info, $error, array $curlOpts)
    {
        $exp = explode("\r\n\r\n", $response, 2);

        switch(count($exp))
        {
            case 2 :
                $this->response = end($exp);
                $this->responseHeaders = reset($exp);
                break;

            default :
                $this->response = $response;
                break;
        }

        $this->info = $info;
        $this->error = $error;

        if (isset($this->info['http_code']))
            $this->httpCode = (int)$this->info['http_code'];

        $this->curlOpts = $curlOpts;
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

    /**
     * @return array|null
     */
    public function getRequestHeaders()
    {
        $info = $this->getInfo();
        return (isset($info['request_header']) ? $info['request_header'] : null);
    }

    /**
     * @return null|string
     */
    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }
}