<?php namespace DCarbone\OCURL\Response;

/**
 * Interface ICurlResponse
 * @package DCarbone\OCURL\Response
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