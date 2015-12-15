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
 * Class CPParameters
 */
abstract class CPParameters
{
    /** @var string */
    public static $jquery1_11 = 'http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js';
    /** @var string */
    public static $jquery2_1 = 'http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js';
    /** @var string */
    public static $smallResponse = 'http://api.gvmtool.net/candidates/grails/default';
    /** @var string */
    public static $httpbinURL = 'https://httpbin.org';
    /** @var string */
    public static $lifeSavingTamales = 'http://www.gstatic.com/hostedimg/6ce955e0e2197bb6_large';
    /** @var string */
    public static $tmpDir;
    /** @var string */
    public static $localTamalesFileName = 'life_saving_tamales.jpg';
}

CPParameters::$tmpDir = realpath(__DIR__.'/../').'/tmp';