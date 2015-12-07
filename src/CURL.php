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

    /**
     * Local initializer
     */
    private static function _init()
    {
        if (!isset(self::$_client))
            self::$_client = new CurlPlusClient();
    }

    /**
     * @param string $url
     * @param array $queryParams
     * @param array $curlOptions
     * @param array $requestHeaders
     * @return Response\CurlPlusResponse
     */
    public static function get($url, array $queryParams = array(), array $curlOptions = array(), array $requestHeaders = array())
    {
        static $defaultOpts = array(
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true
        );

        self::_init();

        if (count($queryParams) > 0)
            $url = sprintf('%s?%s', $url, http_build_query($queryParams));

        self::$_client->initialize($url, true);
        self::$_client->setCurlOpts($curlOptions + $defaultOpts);
        self::$_client->addRequestHeaders($requestHeaders);

        return self::$_client->execute(true);
    }

    /**
     * @param string $url
     * @param array $queryParams
     * @param array $postFields
     * @param array $curlOptions
     * @param array $requestHeaders
     * @return Response\CurlPlusResponse
     */
    public static function post($url,
                                array $queryParams = array(),
                                array $postFields = array(),
                                array $curlOptions = array(),
                                array $requestHeaders = array())
    {
        static $defaultOpts = array(
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
        );
        static $defaultHeaders = array(
            'Content-Type' => 'application/x-www-form-urlencoded'
        );

        self::_init();

        if (count($queryParams) > 0)
            $url = sprintf('%s?%s', $url, http_build_query($queryParams));

        self::$_client->initialize($url, true);
        self::$_client->setCurlOpts(
            $curlOptions +
            array(CURLOPT_POSTFIELDS => http_build_query($postFields)) +
            $defaultOpts);
        self::$_client->setRequestHeaders($requestHeaders + $defaultHeaders);

        return self::$_client->execute(true);
    }

    /**
     * @param string $url
     * @param array $curlOptions
     * @param array $requestHeaders
     * @return Response\CurlPlusResponse
     */
    public static function options($url, array $curlOptions = array(), array $requestHeaders = array())
    {
        static $defaultOpts = array(
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'OPTIONS',
            CURLOPT_NOBODY => true,
        );

        self::_init();

        self::$_client->initialize($url, true);
        self::$_client->setCurlOpts($curlOptions + $defaultOpts);
        self::$_client->setRequestHeaders($requestHeaders);

        return self::$_client->execute(true);
    }

    /**
     * @param string $url
     * @param array $curlOptions
     * @param array $requestHeaders
     * @return Response\CurlPlusResponse
     */
    public static function head($url, array $curlOptions = array(), array $requestHeaders = array())
    {
        static $defaultOpts = array(
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'HEAD',
            CURLOPT_NOBODY => true,
        );

        self::_init();

        self::$_client->initialize($url, true);
        self::$_client->setCurlOpts($curlOptions + $defaultOpts);
        self::$_client->setRequestHeaders($requestHeaders);

        return self::$_client->execute(true);
    }

    /**
     * @param string $url
     * @param array $curlOptions
     * @param array $requestHeaders
     * @return Response\CurlPlusResponse
     */
    public static function put($url, array $curlOptions = array(), array $requestHeaders = array())
    {
        static $defaultOpts = array(
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'PUT'
        );

        self::_init();

        self::$_client->initialize($url, true);
        self::$_client->setCurlOpts($curlOptions + $defaultOpts);
        self::$_client->setRequestHeaders($requestHeaders);

        return self::$_client->execute(true);
    }

    /**
     * @param string $url
     * @param array $curlOptions
     * @param array $requestHeaders
     * @return Response\CurlPlusResponse
     */
    public static function delete($url, array $curlOptions = array(), array $requestHeaders = array())
    {
        static $defaultOpts = array(
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'DELETE'
        );

        self::_init();

        self::$_client->initialize($url, true);
        self::$_client->setCurlOpts($curlOptions + $defaultOpts);
        self::$_client->setRequestHeaders($requestHeaders);

        return self::$_client->execute(true);
    }
}