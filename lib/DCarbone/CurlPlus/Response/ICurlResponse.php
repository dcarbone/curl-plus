<?php namespace DCarbone\CurlPlus\Response;

/**
 * Interface ICurlResponse
 * @package DCarbone\CurlPlus\Response
 */
interface ICurlResponse
{
    /**
     * @return mixed
     */
    public function getResponse();

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
     * @return array
     */
    public function getQueryHeaders();

    /**
     * @return array
     */
    public function getResponseHeaders();
}