<?php

/**
 * Class CurlPlusAutoLoader
 */
class CurlPlusAutoLoader
{
    const ROOT_NAMESPACE_OF_CONCERN = 'DCarbone\\CurlPlus';
    const ROOT_DIRECTORY_OF_CONCERN = __DIR__;

    /** @var boolean */
    private static $registered = false;

    /**
     * Register the autoloader in the spl autoloader
     *
     * @return bool
     * @throws Exception If there was an error in registration
     */
    public static function register()
    {
        if (self::$registered)
            return self::$registered;

        return self::$registered = spl_autoload_register(array(__CLASS__, 'loadClass'), true);
    }

    /**
     * Unregisters the autoloader
     *
     * @return bool
     */
    public static function unregister()
    {
        self::$registered = !spl_autoload_unregister(array(__CLASS__, 'loadClass'));
        return !self::$registered;
    }

    /**
     * Loads the class
     *
     * @param string $class The class to load
     * @throws Exception
     * @return bool|null
     */
    public static function loadClass($class)
    {
        // handle only package classes
        if (0 === strpos($class, self::ROOT_NAMESPACE_OF_CONCERN))
        {
            require vsprintf('%s%s.php', array(
                self::ROOT_DIRECTORY_OF_CONCERN,
                str_replace(array(self::ROOT_NAMESPACE_OF_CONCERN, '\\'), array('', '/'), $class)
            ));

            return true;
        }
        return null;
    }
}