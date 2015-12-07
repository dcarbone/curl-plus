<?php

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