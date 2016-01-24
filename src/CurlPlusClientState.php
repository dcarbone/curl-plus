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
 * Class CurlPlusClientState
 * @package DCarbone\CurlPlus
 */
abstract class CurlPlusClientState
{
    const STATE_NEW         = 0;
    const STATE_INITIALIZED = 1;
    const STATE_EXECUTING   = 2;
    const STATE_EXECUTED    = 3;
    const STATE_CLOSED      = 4;
}