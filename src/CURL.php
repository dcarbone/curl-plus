<?php namespace DCarbone\CurlPlus;

/*
    CurlPlus: A simple OO implementation of CURL in PHP
    Copyright (C) 2012-2015  Daniel Paul Carbone (daniel.p.carbone@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along
    with this program; if not, write to the Free Software Foundation, Inc.,
    51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

/**
 * Class CURL
 * @package DCarbone\CurlPlus
 */
abstract class CURL
{
    /** @var \DCarbone\CurlPlus\CurlPlusClient */
    private static $_client;

    /** @var array */
    private static $_defaultGetCurlOpts = array(
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true
    );

    /** @var array */
    private static $_defaultPostCurlOpts = array(
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
    );
    /** @var array */
    private static $_defaultPostRequestHeaders = array(
        'Content-Type' => 'application/x-www-form-urlencoded'
    );

    /** @var array */
    private static $_defaultOptionsCurlOpts = array(
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'OPTIONS',
        CURLOPT_NOBODY => true,
    );

    /** @var array */
    private static $_defaultHeadCurlOpts = array(
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'HEAD',
        CURLOPT_NOBODY => true,
    );

    /** @var array */
    private static $_defaultPutCurlOpts = array(
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'PUT'
    );
    /** @var array */
    private static $_defaultPutRequestHeaders = array(
        'Content-Type' => 'application/x-www-form-urlencoded'
    );

    /** @var array */
    private static $_defaultDeleteCurlOpts = array(
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'DELETE'
    );
    /** @var array */
    private static $_defaultDeleteRequestHeaders = array(
        'Content-Type' => 'application/x-www-form-urlencoded'
    );

    /**
     * @param string $url
     * @param array $queryParams
     * @param array $curlOptions
     * @param array $requestHeaders
     * @return Response\CurlPlusResponseInterface
     */
    public static function get($url,
                               array $queryParams = array(),
                               array $curlOptions = array(),
                               array $requestHeaders = array())
    {
        if (count($queryParams) > 0)
            $url = sprintf('%s?%s', $url, http_build_query($queryParams));

        return self::_execute(
            $url,
            $curlOptions + self::$_defaultGetCurlOpts,
            $requestHeaders
        );
    }

    /**
     * @param string $url
     * @param array $queryParams
     * @param array $formFields
     * @param array $curlOptions
     * @param array $requestHeaders
     * @return Response\CurlPlusResponseInterface
     */
    public static function post($url,
                                array $queryParams = array(),
                                array $formFields = array(),
                                array $curlOptions = array(),
                                array $requestHeaders = array())
    {
        if (count($queryParams) > 0)
            $url = sprintf('%s?%s', $url, http_build_query($queryParams));

        return self::_execute(
            $url,
            $curlOptions +
            array(CURLOPT_POSTFIELDS => http_build_query($formFields)) +
            self::$_defaultPostCurlOpts,
            $requestHeaders + self::$_defaultPostRequestHeaders
        );
    }

    /**
     * @param string $url
     * @param array $curlOptions
     * @param array $requestHeaders
     * @return Response\CurlPlusResponseInterface
     */
    public static function options($url, array $curlOptions = array(), array $requestHeaders = array())
    {
        return self::_execute(
            $url,
            $curlOptions + self::$_defaultOptionsCurlOpts,
            $requestHeaders
        );
    }

    /**
     * @param string $url
     * @param array $curlOptions
     * @param array $requestHeaders
     * @return Response\CurlPlusResponseInterface
     */
    public static function head($url, array $curlOptions = array(), array $requestHeaders = array())
    {
        return self::_execute(
            $url,
            $curlOptions + self::$_defaultHeadCurlOpts,
            $requestHeaders
        );
    }

    /**
     * @param string $url
     * @param array $queryParams
     * @param array $formFields
     * @param array $curlOptions
     * @param array $requestHeaders
     * @return Response\CurlPlusResponseInterface
     */
    public static function put($url,
                               array $queryParams = array(),
                               array $formFields = array(),
                               array $curlOptions = array(),
                               array $requestHeaders = array())
    {
        if (count($queryParams) > 0)
            $url = sprintf('%s?%s', $url, http_build_query($queryParams));

        return self::_execute(
            $url,
            $curlOptions +
            array(CURLOPT_POSTFIELDS => http_build_query($formFields)) +
            self::$_defaultPutCurlOpts,
            $requestHeaders + self::$_defaultPutRequestHeaders
        );
    }

    /**
     * @param string $url
     * @param array $queryParams
     * @param array $formFields
     * @param array $curlOptions
     * @param array $requestHeaders
     * @return Response\CurlPlusResponseInterface
     */
    public static function delete($url,
                                  array $queryParams = array(),
                                  array $formFields = array(),
                                  array $curlOptions = array(),
                                  array $requestHeaders = array())
    {
        if (count($queryParams) > 0)
            $url = sprintf('%s?%s', $url, http_build_query($queryParams));

        return self::_execute(
            $url,
            $curlOptions +
            array(CURLOPT_POSTFIELDS => http_build_query($formFields)) +
            self::$_defaultDeleteCurlOpts,
            $requestHeaders + self::$_defaultDeleteRequestHeaders
        );
    }

    /**
     * @param string $url
     * @param array $curlOpts
     * @param array $requestHeaders
     * @return Response\CurlPlusResponseInterface
     */
    private static function _execute($url, array $curlOpts, array $requestHeaders)
    {
        if (!isset(self::$_client))
            self::$_client = new CurlPlusClient();

        self::$_client->initialize($url);
        self::$_client->setCurlOpts($curlOpts);
        self::$_client->setRequestHeaders($requestHeaders);

        return self::$_client->execute(true);
    }
}