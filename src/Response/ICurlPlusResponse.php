<?php namespace DCarbone\CurlPlus\Response;

/**
 * Interface ICurlPlusResponse
 * @package DCarbone\CurlPlus\Response
 */
interface ICurlPlusResponse
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
     * @return array|null
     */
    public function getRequestHeaders();

    /**
     * @return string|null
     */
    public function getResponseHeaders();
}