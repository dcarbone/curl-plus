<?php namespace DCarbone\CurlPlus;

/*
    Copyright 2012-2015  Daniel Paul Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\CURLHeaderExtractor;

/**
 * Class CurlPlusResponse
 * @package DCarbone\CurlPlus\Response
 */
class CurlPlusResponse
{
    /** @var string */
    public $url = '';
    /** @var int */
    public $httpCode = 0;
    /** @var string */
    public $contentType = '';
    /** @var int */
    public $headerSize = 0;
    /** @var int */
    public $requestSize = 0;
    /** @var bool */
    public $sslVerifyResult = false;
    /** @var int */
    public $redirectCount = 0;
    /** @var float */
    public $totalTime = 0.0;
    /** @var float */
    public $nameLookupTime = 0.0;
    /** @var float */
    public $connectTime = 0.0;
    /** @var float */
    public $preTransferTime = 0.0;
    /** @var float */
    public $sizeUpload = 0.0;
    /** @var float */
    public $sizeDownload = 0.0;
    /** @var float */
    public $speedDownload = 0.0;
    /** @var float */
    public $speedUpload = 0.0;
    /** @var float */
    public $downloadContentLength = 0.0;
    /** @var float */
    public $uploadContentLength = 0.0;
    /** @var float */
    public $startTransferTime = 0.0;
    /** @var float */
    public $redirectTime = 0.0;
    /** @var string */
    public $redirectURL = '';
    /** @var string */
    public $primaryIP = '';
    /** @var array */
    public $certInfo = array();
    /** @var int */
    public $primaryPort = 0;
    /** @var string */
    public $localIP = '';
    /** @var int */
    public $localPort = 0;
    /** @var string */
    public $requestHeader = '';
    /** @var array */
    public $responseHeaders = array();
    /** @var string */
    public $responseBody = '';
    /** @var string */
    public $curlError = '';

    /** @var array */
    public $curlOpts = array();

    /** @var resource */
    public $file = null;
    /** @var string */
    public $filename = null;

    /** @var array */
    private $_requestHeaderArray = null;

    /**
     * AbstractCurlPlusResponse constructor.
     * @param $response
     * @param array $curlInfo
     * @param $curlError
     * @param array $curlOpts
     */
    public function __construct($response, array $curlInfo, $curlError, array $curlOpts)
    {
        $this->curlOpts = $curlOpts;

        // determine response type
        if (isset($curlOpts[CURLOPT_FILE]) && 'resource' === gettype($curlOpts[CURLOPT_FILE]))
        {
            $this->file = $curlOpts[CURLOPT_FILE];
            $meta = stream_get_meta_data($curlOpts[CURLOPT_FILE]);
            $this->filename = $meta['uri'];

            if (isset($curlOpts[CURLOPT_HEADER]) && $curlOpts[CURLOPT_HEADER])
                list($this->responseHeaders) = CURLHeaderExtractor::getHeaderAndBody($this->filename);
        }
        else if (is_string($response))
        {
            if (isset($curlOpts[CURLOPT_HEADER]) && $curlOpts[CURLOPT_HEADER])
                list($this->responseHeaders, $this->responseBody) = CURLHeaderExtractor::getHeaderAndBody($response);
            else
                $this->responseBody = $response;
        }

        // parse curl info array
        $this->url = $curlInfo['url'];
        $this->contentType = $curlInfo['content_type'];
        $this->httpCode = $curlInfo['http_code'];
        $this->headerSize = $curlInfo['header_size'];
        $this->requestSize = $curlInfo['request_size'];
        $this->sslVerifyResult = (bool)$curlInfo['ssl_verify_result'];
        $this->redirectCount = $curlInfo['redirect_count'];
        $this->totalTime = $curlInfo['total_time'];
        $this->nameLookupTime = $curlInfo['namelookup_time'];
        $this->connectTime = $curlInfo['connect_time'];
        $this->preTransferTime = $curlInfo['pretransfer_time'];
        $this->sizeDownload = $curlInfo['size_download'];
        $this->sizeUpload = $curlInfo['size_upload'];
        $this->speedDownload = $curlInfo['speed_download'];
        $this->speedUpload = $curlInfo['speed_upload'];
        $this->downloadContentLength = $curlInfo['download_content_length'];
        $this->uploadContentLength = $curlInfo['upload_content_length'];
        $this->startTransferTime = $curlInfo['starttransfer_time'];
        $this->redirectTime = $curlInfo['redirect_time'];
        $this->redirectURL = $curlInfo['redirect_url'];
        $this->primaryIP = $curlInfo['primary_ip'];
        $this->certInfo = $curlInfo['certinfo'];
        $this->primaryPort = $curlInfo['primary_port'];
        $this->localIP = $curlInfo['local_ip'];
        $this->localPort = $curlInfo['local_port'];
        if (isset($curlInfo['request_header']))
            $this->requestHeader = $curlInfo['request_header'];

        // set the error, whatever the value
        $this->curlError = $curlError;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @return int
     */
    public function getHeaderSize()
    {
        return $this->headerSize;
    }

    /**
     * @return int
     */
    public function getRequestSize()
    {
        return $this->requestSize;
    }

    /**
     * @return boolean
     */
    public function isSslVerifyResult()
    {
        return $this->sslVerifyResult;
    }

    /**
     * @return int
     */
    public function getRedirectCount()
    {
        return $this->redirectCount;
    }

    /**
     * @return float
     */
    public function getTotalTime()
    {
        return $this->totalTime;
    }

    /**
     * @return float
     */
    public function getNameLookupTime()
    {
        return $this->nameLookupTime;
    }

    /**
     * @return float
     */
    public function getConnectTime()
    {
        return $this->connectTime;
    }

    /**
     * @return float
     */
    public function getPreTransferTime()
    {
        return $this->preTransferTime;
    }

    /**
     * @return float
     */
    public function getSizeUpload()
    {
        return $this->sizeUpload;
    }

    /**
     * @return float
     */
    public function getSizeDownload()
    {
        return $this->sizeDownload;
    }

    /**
     * @return float
     */
    public function getSpeedDownload()
    {
        return $this->speedDownload;
    }

    /**
     * @return float
     */
    public function getSpeedUpload()
    {
        return $this->speedUpload;
    }

    /**
     * @return float
     */
    public function getDownloadContentLength()
    {
        return $this->downloadContentLength;
    }

    /**
     * @return float
     */
    public function getUploadContentLength()
    {
        return $this->uploadContentLength;
    }

    /**
     * @return float
     */
    public function getStartTransferTime()
    {
        return $this->startTransferTime;
    }

    /**
     * @return float
     */
    public function getRedirectTime()
    {
        return $this->redirectTime;
    }

    /**
     * @return string
     */
    public function getRedirectURL()
    {
        return $this->redirectURL;
    }

    /**
     * @return string
     */
    public function getPrimaryIP()
    {
        return $this->primaryIP;
    }

    /**
     * @return array
     */
    public function getCertInfo()
    {
        return $this->certInfo;
    }

    /**
     * @return int
     */
    public function getPrimaryPort()
    {
        return $this->primaryPort;
    }

    /**
     * @return string
     */
    public function getLocalIP()
    {
        return $this->localIP;
    }

    /**
     * @return int
     */
    public function getLocalPort()
    {
        return $this->localPort;
    }

    /**
     * @return string
     */
    public function getRequestHeader()
    {
        return $this->requestHeader;
    }

    /**
     * @return array
     */
    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }

    /**
     * @return string
     */
    public function getResponseBody()
    {
        return $this->responseBody;
    }

    /**
     * @return string
     */
    public function getCurlError()
    {
        return $this->curlError;
    }

    /**
     * @return array
     */
    public function getCurlOpts()
    {
        return $this->curlOpts;
    }

    /**
     * @return resource
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return array
     */
    public function getRequestHeaderArray()
    {
        if (null === $this->_requestHeaderArray)
            list($this->_requestHeaderArray) = CURLHeaderExtractor::getHeaderAndBody($this->requestHeader);

        return $this->_requestHeaderArray;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if (isset($this->filename))
            return $this->filename;

        return $this->responseBody;
    }
}