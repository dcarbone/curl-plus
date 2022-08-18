<?php declare(strict_types=1);

namespace DCarbone\CurlPlus;

/*
    Copyright 2012-2022  Daniel Paul Carbone (daniel.p.carbone@gmail.com)

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
    public string $url = '';
    /** @var int */
    public int $httpCode = 0;
    /** @var string */
    public string $contentType = '';
    /** @var int */
    public int $headerSize = 0;
    /** @var int */
    public int $requestSize = 0;
    /** @var bool */
    public bool $sslVerifyResult = false;
    /** @var int */
    public int $redirectCount = 0;
    /** @var float */
    public float $totalTime = 0.0;
    /** @var float */
    public float $nameLookupTime = 0.0;
    /** @var float */
    public float $connectTime = 0.0;
    /** @var float */
    public float $preTransferTime = 0.0;
    /** @var float */
    public float $sizeUpload = 0.0;
    /** @var float */
    public float $sizeDownload = 0.0;
    /** @var float */
    public float $speedDownload = 0.0;
    /** @var float */
    public float $speedUpload = 0.0;
    /** @var float */
    public float $downloadContentLength = 0.0;
    /** @var float */
    public float $uploadContentLength = 0.0;
    /** @var float */
    public float $startTransferTime = 0.0;
    /** @var float */
    public float $redirectTime = 0.0;
    /** @var string */
    public string $redirectURL = '';
    /** @var string */
    public string $primaryIP = '';
    /** @var array */
    public array $certInfo = [];
    /** @var int */
    public int $primaryPort = 0;
    /** @var string */
    public string $localIP = '';
    /** @var int */
    public int$localPort = 0;
    /** @var string */
    public string $requestHeader = '';
    /** @var array */
    public array $responseHeaders = [];
    /** @var string */
    public string $responseBody = '';
    /** @var string */
    public string $curlError = '';

    /** @var array */
    public array $curlOpts = [];

    /** @var resource */
    public $file = null;
    /** @var string|null */
    public ?string $filename = null;

    /** @var array|null */
    private ?array $_requestHeaderArray = null;

    /**
     * AbstractCurlPlusResponse constructor.
     * @param string|bool $response
     * @param array $curlInfo
     * @param string|bool $curlError
     * @param array $curlOpts
     */
    public function __construct(string|bool $response, array $curlInfo, string|bool $curlError, array $curlOpts)
    {
        $this->curlOpts = $curlOpts;

        // determine response type
        if (isset($curlOpts[CURLOPT_FILE]) && 'resource' === gettype($curlOpts[CURLOPT_FILE])) {
            $this->file = $curlOpts[CURLOPT_FILE];
            $meta = stream_get_meta_data($curlOpts[CURLOPT_FILE]);
            $this->filename = $meta['uri'];

            if (isset($curlOpts[CURLOPT_HEADER]) && $curlOpts[CURLOPT_HEADER]) {
                [$this->responseHeaders] = CURLHeaderExtractor::getHeaderAndBody($this->filename);
            }
        } else if (is_string($response)) {
            if (isset($curlOpts[CURLOPT_HEADER]) && $curlOpts[CURLOPT_HEADER]) {
                [$this->responseHeaders, $this->responseBody] = CURLHeaderExtractor::getHeaderAndBody($response);
            } else {
                $this->responseBody = $response;
            }
        }

        // parse curl info array
        $this->url = (string)$curlInfo['url'];
        $this->contentType = (string)$curlInfo['content_type'];
        $this->httpCode = (int)$curlInfo['http_code'];
        $this->headerSize = (int)$curlInfo['header_size'];
        $this->requestSize = (int)$curlInfo['request_size'];
        $this->sslVerifyResult = (bool)$curlInfo['ssl_verify_result'];
        $this->redirectCount = (int)$curlInfo['redirect_count'];
        $this->totalTime = (float)$curlInfo['total_time'];
        $this->nameLookupTime = (float)$curlInfo['namelookup_time'];
        $this->connectTime = (float)$curlInfo['connect_time'];
        $this->preTransferTime = (float)$curlInfo['pretransfer_time'];
        $this->sizeDownload = (float)$curlInfo['size_download'];
        $this->sizeUpload = (float)$curlInfo['size_upload'];
        $this->speedDownload = (float)$curlInfo['speed_download'];
        $this->speedUpload = (float)$curlInfo['speed_upload'];
        $this->downloadContentLength = (float)$curlInfo['download_content_length'];
        $this->uploadContentLength = (float)$curlInfo['upload_content_length'];
        $this->startTransferTime = (float)$curlInfo['starttransfer_time'];
        $this->redirectTime = (float)$curlInfo['redirect_time'];
        $this->redirectURL = (string)$curlInfo['redirect_url'];
        $this->primaryIP = (string)$curlInfo['primary_ip'];
        $this->certInfo = (array)$curlInfo['certinfo'];
        $this->primaryPort = (int)$curlInfo['primary_port'];
        $this->localIP = (string)$curlInfo['local_ip'];
        $this->localPort = (int)$curlInfo['local_port'];
        if (isset($curlInfo['request_header'])) {
            $this->requestHeader = (string)$curlInfo['request_header'];
        }

        // set the error, whatever the value
        $this->curlError = $curlError;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return int
     */
    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * @return int
     */
    public function getHeaderSize(): int
    {
        return $this->headerSize;
    }

    /**
     * @return int
     */
    public function getRequestSize(): int
    {
        return $this->requestSize;
    }

    /**
     * @return boolean
     */
    public function isSslVerifyResult(): bool
    {
        return $this->sslVerifyResult;
    }

    /**
     * @return int
     */
    public function getRedirectCount(): int
    {
        return $this->redirectCount;
    }

    /**
     * @return float
     */
    public function getTotalTime(): float
    {
        return $this->totalTime;
    }

    /**
     * @return float
     */
    public function getNameLookupTime(): float
    {
        return $this->nameLookupTime;
    }

    /**
     * @return float
     */
    public function getConnectTime(): float
    {
        return $this->connectTime;
    }

    /**
     * @return float
     */
    public function getPreTransferTime(): float
    {
        return $this->preTransferTime;
    }

    /**
     * @return float
     */
    public function getSizeUpload(): float
    {
        return $this->sizeUpload;
    }

    /**
     * @return float
     */
    public function getSizeDownload(): float
    {
        return $this->sizeDownload;
    }

    /**
     * @return float
     */
    public function getSpeedDownload(): float
    {
        return $this->speedDownload;
    }

    /**
     * @return float
     */
    public function getSpeedUpload(): float
    {
        return $this->speedUpload;
    }

    /**
     * @return float
     */
    public function getDownloadContentLength(): float
    {
        return $this->downloadContentLength;
    }

    /**
     * @return float
     */
    public function getUploadContentLength(): float
    {
        return $this->uploadContentLength;
    }

    /**
     * @return float
     */
    public function getStartTransferTime(): float
    {
        return $this->startTransferTime;
    }

    /**
     * @return float
     */
    public function getRedirectTime(): float
    {
        return $this->redirectTime;
    }

    /**
     * @return string
     */
    public function getRedirectURL(): string
    {
        return $this->redirectURL;
    }

    /**
     * @return string
     */
    public function getPrimaryIP(): string
    {
        return $this->primaryIP;
    }

    /**
     * @return array
     */
    public function getCertInfo(): array
    {
        return $this->certInfo;
    }

    /**
     * @return int
     */
    public function getPrimaryPort(): int
    {
        return $this->primaryPort;
    }

    /**
     * @return string
     */
    public function getLocalIP(): string
    {
        return $this->localIP;
    }

    /**
     * @return int
     */
    public function getLocalPort(): int
    {
        return $this->localPort;
    }

    /**
     * @return string
     */
    public function getRequestHeader(): string
    {
        return $this->requestHeader;
    }

    /**
     * @return array
     */
    public function getResponseHeaders(): array
    {
        return $this->responseHeaders;
    }

    /**
     * @return string
     */
    public function getResponseBody(): string
    {
        return $this->responseBody;
    }

    /**
     * @return bool|string
     */
    public function getCurlError(): bool|string
    {
        return $this->curlError;
    }

    /**
     * @return array
     */
    public function getCurlOpts(): array
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
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @return array|null
     */
    public function getRequestHeaderArray(): ?array
    {
        if (null === $this->_requestHeaderArray) {
            [$this->_requestHeaderArray] = CURLHeaderExtractor::getHeaderAndBody($this->requestHeader);
        }

        return $this->_requestHeaderArray;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)($this->filename ?? $this->responseBody);
    }
}
