<?php namespace DCarbone\CurlPlus\Response;

/**
 * @deprecated Since 2.0: Use CurlPlusResponseInterface
 *
 * Interface ICurlPlusResponse
 * @package DCarbone\CurlPlus\Response
 */
interface ICurlPlusResponse extends CurlPlusResponseInterface
{
    /**
     * @deprecated Since 2.0: Use getResponseBody instead
     *
     * @return mixed
     */
    public function getResponse();
}