<?php namespace DCarbone\CurlPlus;

/**
 * Interface ICurlPlusContainer
 * @package DCarbone\CurlPlus
 */
interface ICurlPlusContainer
{
    /**
     * @return ICurlPlusContainer
     */
    public function getCurlClient();
}