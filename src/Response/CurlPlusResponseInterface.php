<?php namespace DCarbone\CurlPlus\Response;

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
 * Interface CurlPlusResponseInterface
 * @package DCarbone\CurlPlus\Response
 */
interface CurlPlusResponseInterface
{
    /**
     * @return array
     */
    public function getInfo();

    /**
     * @return mixed
     */
    public function getError();

    /**
     * @return int
     */
    public function getHttpCode();

    /**
     * @param bool $asArray
     * @return array|null
     */
    public function getRequestHeaders($asArray = false);

    /**
     * @return array
     */
    public function getRequestCurlOpts();

    /**
     * @return string
     */
    public function __toString();
}