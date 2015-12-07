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
 * Class CurlOptHelper
 * @package DCarbone\CurlPlus
 */
abstract class CurlOptHelper
{
    /** @var bool */
    private static $init = false;

    /** @var array */
    protected static $humanReadableCurlOpts = array();
    /** @var array */
    protected static $curlOptConstantValues = array();

    /**
     * Initialization method
     */
    protected static function init()
    {
        $curl_constants = get_defined_constants(true);

        self::$humanReadableCurlOpts = array_flip($curl_constants['curl']);

        unset($curl_constants);

        self::$init = true;
    }

    /**
     * @param int $curlOpt
     * @return string|null
     */
    public static function getStringNameForCurlOpt($curlOpt)
    {
        if (!self::$init)
            self::init();

        if (isset(self::$humanReadableCurlOpts[$curlOpt]))
            return self::$humanReadableCurlOpts[$curlOpt];

        return null;
    }

    /**
     * @param array $curlOpts
     * @return array
     */
    public static function createHumanReadableCurlOptArray(array $curlOpts)
    {
        if (!self::$init)
            self::init();

        $return = array();
        foreach($curlOpts as $k=>$v)
        {
            $curlOpt = self::getStringNameForCurlOpt($k);
            if (null !== $curlOpt)
                $return[$curlOpt] = $v;
        }

        return $return;
    }
}