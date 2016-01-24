<?php

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
 * Class CurlPlusAutoLoader
 */
class CurlPlusAutoLoader
{
    const ROOT_NAMESPACE_OF_CONCERN = 'DCarbone\\CurlPlus';
    const ROOT_DIRECTORY_OF_CONCERN = __DIR__;

    /** @var boolean */
    private static $_registered = false;

    /**
     * Register the autoloader in the spl autoloader
     *
     * @return bool
     * @throws Exception If there was an error in registration
     */
    public static function register()
    {
        if (self::$_registered)
            return self::$_registered;

        return self::$_registered = spl_autoload_register(array(__CLASS__, 'loadClass'), true);
    }

    /**
     * Unregisters the autoloader
     *
     * @return bool
     */
    public static function unregister()
    {
        self::$_registered = !spl_autoload_unregister(array(__CLASS__, 'loadClass'));
        return !self::$_registered;
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