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

/**
 * Class CURL
 * @package DCarbone\CurlPlus
 */
abstract class CURL
{
    private const GET = 'GET';
    private const POST = 'POST';
    private const PUT = 'PUT';
    private const DELETE = 'DELETE';
    private const HEAD = 'HEAD';
    private const OPTIONS = 'OPTIONS';

    /** @var array */
    public static array $defaultGetCurlOpts = [
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true
    ];

    /** @var array */
    public static array $defaultPostCurlOpts = [
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true
    ];

    /** @var array */
    public static array $defaultPostRequestHeaders = [
        'Content-Type' => 'application/x-www-form-urlencoded'
    ];

    /** @var array */
    public static array $defaultOptionsCurlOpts = [
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_NOBODY => true
    ];

    /** @var array */
    public static array $defaultHeadCurlOpts = [
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_NOBODY => true
    ];

    /** @var array */
    public static array $defaultPutCurlOpts = [
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true
    ];

    /** @var array */
    public static array $defaultPutRequestHeaders = [
        'Content-Type' => 'application/x-www-form-urlencoded'
    ];

    /** @var array */
    public static array $defaultDeleteCurlOpts = [
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true
    ];

    /** @var array */
    public static array $defaultDeleteRequestHeaders = [
        'Content-Type' => 'application/x-www-form-urlencoded'
    ];

    /** @var \DCarbone\CurlPlus\CurlPlusClient */
    private static CurlPlusClient $_client;

    /** @var array */
    private const _METHODS_WITH_BODY = [self::POST, self::PUT, self::DELETE];

    /**
     * @param string $url
     * @param array $queryParams
     * @param array $curlOpts
     * @param array $requestHeaders
     * @return CurlPlusResponse
     */
    public static function get(string $url,
                               array  $queryParams = [],
                               array  $curlOpts = [],
                               array  $requestHeaders = []): CurlPlusResponse
    {
        return self::_execute(
            self::_buildURL($url, $queryParams),
            self::GET,
            $curlOpts,
            self::$defaultGetCurlOpts,
            $requestHeaders
        );
    }

    /**
     * @param string $url
     * @param array $queryParams
     * @param object|array|string|null $requestBody
     * @param array $curlOpts
     * @param array $requestHeaders
     * @return CurlPlusResponse
     */
    public static function post(string              $url,
                                array               $queryParams = [],
                                object|array|string $requestBody = null,
                                array               $curlOpts = [],
                                array               $requestHeaders = []): CurlPlusResponse
    {
       return self::_execute(
            self::_buildURL($url, $queryParams),
            self::POST,
            $curlOpts,
            self::$defaultPostCurlOpts,
            $requestHeaders,
            self::$defaultPostRequestHeaders,
            $requestBody
        );
    }

    /**
     * @param string $url
     * @param array $curlOpts
     * @param array $requestHeaders
     * @return CurlPlusResponse
     */
    public static function options(string $url, array $curlOpts = [], array $requestHeaders = []): CurlPlusResponse
    {
        return self::_execute(
            $url,
            self::OPTIONS,
            $curlOpts,
            self::$defaultOptionsCurlOpts,
            $requestHeaders
        );
    }

    /**
     * @param string $url
     * @param array $curlOpts
     * @param array $requestHeaders
     * @return CurlPlusResponse
     */
    public static function head(string $url, array $curlOpts = [], array $requestHeaders = []): CurlPlusResponse
    {
        return self::_execute(
            $url,
            self::HEAD,
            $curlOpts,
            self::$defaultHeadCurlOpts,
            $requestHeaders
        );
    }

    /**
     * @param string $url
     * @param array $queryParams
     * @param object|array|string|null $requestBody
     * @param array $curlOpts
     * @param array $requestHeaders
     * @return CurlPlusResponse
     */
    public static function put(string              $url,
                               array               $queryParams = [],
                               object|array|string $requestBody = null,
                               array               $curlOpts = [],
                               array               $requestHeaders = []): CurlPlusResponse
    {
        return self::_execute(
            self::_buildURL($url, $queryParams),
            self::PUT,
            $curlOpts,
            self::$defaultPutCurlOpts,
            $requestHeaders,
            self::$defaultPutRequestHeaders,
            $requestBody
        );
    }

    /**
     * @param string $url
     * @param array $queryParams
     * @param object|array|string|null $requestBody
     * @param array $curlOpts
     * @param array $requestHeaders
     * @return CurlPlusResponse
     */
    public static function delete(string              $url,
                                  array               $queryParams = [],
                                  object|array|string $requestBody = null,
                                  array               $curlOpts = [],
                                  array               $requestHeaders = []): CurlPlusResponse
    {
        return self::_execute(
            self::_buildURL($url, $queryParams),
            self::DELETE,
            $curlOpts,
            self::$defaultDeleteCurlOpts,
            $requestHeaders,
            self::$defaultDeleteRequestHeaders,
            $requestBody
        );
    }

    /**
     * On-load init method
     */
    public static function _init(): void
    {
        if (isset(self::$_client)) {
            return;
        }

        self::$_client = new CurlPlusClient();
    }

    /**
     * @param string $url
     * @param array $queryParams
     * @return string
     */
    private static function _buildURL(string $url, array $queryParams): string {
        if ([] !== $queryParams) {
            $url = sprintf('%s?%s', $url, http_build_query($queryParams));
        }
        return $url;
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $userOpts
     * @param array $defaultOpts
     * @param array $userHeaders
     * @param array $defaultHeaders
     * @param object|array|string|null $requestBody
     * @return CurlPlusResponse
     */
    private static function _execute(string              $url,
                                     string              $method,
                                     array               $userOpts,
                                     array               $defaultOpts,
                                     array               $userHeaders,
                                     array               $defaultHeaders = [],
                                     object|array|string $requestBody = null): CurlPlusResponse
    {
        self::$_client->initialize($url);

        $method = strtoupper($method);

        switch($method) {
            case self::GET:
                $defaultOpts[CURLOPT_HTTPGET] = true;
                break;
            case self::POST:
                $defaultOpts[CURLOPT_POST] = true;
                break;

            default:
                $defaultOpts[CURLOPT_CUSTOMREQUEST] = $method;
        }

        $bodyType = gettype($requestBody);

        if (null !== $requestBody
            && in_array($method, self::_METHODS_WITH_BODY, true)
            && !isset($userOpts[CURLOPT_POSTFIELDS])) {
            if ('array' === $bodyType || 'object' === $bodyType)
                $requestBody = http_build_query($requestBody);

            $defaultOpts[CURLOPT_POSTFIELDS] = $requestBody;
        }

        self::$_client->setCurlOpts($userOpts + $defaultOpts);
        self::$_client->setRequestHeaders($userHeaders + $defaultHeaders);

        return self::$_client->execute(true);
    }
}
CURL::_init();