<?php namespace DCarbone\CurlPlus\Response;

/**
 * Class CurlPlusResponse
 * @package DCarbone\CurlPlus\Response
 */
class CurlPlusResponse implements ICurlPlusResponse
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
     * @return \DCarbone\CurlPlus\Response\CurlPlusResponse
     */
    public function __construct($response, $info, $error, array $curlOpts)
    {
        if (is_string($response) && isset($curlOpts[CURLOPT_HEADER]) && $curlOpts[CURLOPT_HEADER] == true)
        {
            $responseHeaderString = '';
            $phrase = '';
            $strlen = strlen($response);
            for ($i = 0; $i < $strlen; $i++)
            {
                $lastFour = substr($phrase, -4);
                $firstSeven = substr($phrase, 0, 7);
                if ($lastFour === "\r\n\r\n" && ($firstSeven === 'HTTP/1.' || $firstSeven === 'http/1.'))
                {
                    $responseHeaderString .= $phrase;
                    $phrase = '';
                }
                else if (strlen($phrase) > 7 && !($firstSeven === 'HTTP/1.' || $firstSeven === 'http/1.'))
                {
                    $this->responseHeaders = $responseHeaderString;
                    $this->response = substr($response, strlen($responseHeaderString));
                    break;
                }

                $phrase .= $response[$i];
            }
        }
        else
        {
            $this->response = $response;
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
        if (isset($this->info['request_header']))
            return $this->info['request_header'];
        else
            return null;
    }

    /**
     * @return null|string
     */
    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }
}