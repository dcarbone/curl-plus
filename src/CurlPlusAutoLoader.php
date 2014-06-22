<?php

/**
 * Class CurlPlusAutoLoader
 */
class CurlPlusAutoLoader {

    /** @var boolean */
    protected static $registered = false;

    /** @var string */
    protected static $srcDir;

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

        self::$srcDir = dirname(__FILE__);

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
        array_shift($exp);
        array_shift($exp);
        $fileName = self::$srcDir . '\\' . implode(DIRECTORY_SEPARATOR, $exp).'.php';

        if(file_exists($fileName))
        {
            require $fileName;
            return true;
        }

        throw new Exception('CurlPlusAutoLoader::loadClass - file not loadable "'.$fileName.'"');
    }
}