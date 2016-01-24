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