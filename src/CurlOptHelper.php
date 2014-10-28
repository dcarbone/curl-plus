<?php namespace DCarbone\CurlPlus;

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