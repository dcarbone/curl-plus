<?php

/**
 * CurlPlusAutoLoader
 *
 * @package CurLPlus
 * @subpackage autoload
 */
class CurlPlusAutoLoader {

    /**
     * Registered flag
     *
     * @var boolean
     */
    protected static $registered = false;

    /**
     * Library directory
     *
     * @var string
     */
    protected static $libDir;

    /**
     * Register the autoloader in the spl autoloader
     *
     * @return void
     * @throws Exception If there was an error in registration
     */
    public static function register()
    {
        if (self::$registered)
            return;

        self::$libDir = dirname(__FILE__);

        if(false === spl_autoload_register(array('CurlPlusAutoLoader', 'loadClass')))
            throw new Exception('Unable to register CurlPlusAutoLoader::loadClass as an autoloading method.');

        self::$registered = true;
    }

    /**
     * Unregisters the autoloader
     *
     * @return void
     */
    public static function unregister()
    {
        spl_autoload_unregister(array('CurlPlusAutoLoader', 'loadClass'));
        self::$registered = false;
    }

    /**
     * Loads the class
     *
     * @param string $className The class to load
     * @throws Exception
     * @return bool|null
     */
    public static function loadClass($className)
    {
        // handle only package classes
        if(strpos($className, 'DCarbone\\CurlPlus') !== 0)
            return null;

        $exp = explode('\\',$className);
        $fileName = self::$libDir . '\\' . implode(DIRECTORY_SEPARATOR, $exp).'.php';

        if(file_exists($fileName))
        {
            require $fileName;
            return true;
        }

        throw new Exception('file not loadable '.$fileName);
    }
}