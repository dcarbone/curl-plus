<?php declare(strict_types=1);

namespace DCarbone\CurlPlus\Tests\Utils;

/*
    Copyright 2012-2022  Daniel Paul Carbone (daniel.p.carbone@gmail.com)

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
    public const JQUERY1_11_1 = 'http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js';
    /** @var string */
    public const JQUERY2_1_1 = 'http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js';
    /** @var string */
    public const SMALL_RESPONSE = 'https://httpbin.org/links/1/0';
    /** @var string */
    public const HTTPBIN_URL = 'https://httpbin.org';
    /** @var string */
    public const LIFE_SAVING_TAMALES = 'http://www.gstatic.com/hostedimg/6ce955e0e2197bb6_large';
    /** @var string */
    public static string $tmpDir;
    /** @var string */
    public const LOCAL_TAMALES_FILENAME = 'life_saving_tamales.jpg';
}

CPParameters::$tmpDir = realpath(__DIR__.'/../').'/tmp';