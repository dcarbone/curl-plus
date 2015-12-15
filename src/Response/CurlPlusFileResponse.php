<?php namespace DCarbone\CurlPlus\Response;

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