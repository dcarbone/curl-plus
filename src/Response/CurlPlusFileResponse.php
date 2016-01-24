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
 * Class CurlPlusFileResponse
 * @package DCarbone\CurlPlus\Response
 */
class CurlPlusFileResponse extends AbstractCurlPlusResponse
{
    /** @var string */
    protected $outputFile = null;

    /**
     * @return string
     */
    public function getOutputFile()
    {
        return $this->outputFile;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->outputFile;
    }

    /**
     * @param mixed $response
     */
    protected function parseResponse($response)
    {
        if (isset($this->curlOpts[CURLOPT_FILE]) && is_resource($this->curlOpts[CURLOPT_FILE]))
        {
            $meta = stream_get_meta_data($this->curlOpts[CURLOPT_FILE]);
            $this->outputFile = $meta['uri'];
        }
    }
}