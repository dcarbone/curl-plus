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