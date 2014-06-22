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
    public function &getClient();

    /**
     * @return array
     */
    public function getRequestHeaders();

    /**
     * @param array $headers
     * @return ICurlPlusContainer
     */
    public function setRequestHeaders(array $headers);

    /**
     * @param string $header
     * @return ICurlPlusContainer
     */
    public function addRequestHeaderString($header);

    /**
     * @link http://www.php.net//manual/en/function.curl-setopt.php
     * @param int $opt
     * @param mixed $value
     * @return ICurlPlusContainer
     */
    public function setCurlOpt($opt, $value);

    /**
     * @param int $opt
     * @return ICurlPlusContainer
     */
    public function removeCurlOpt($opt);

    /**
     * @param array $opts
     * @return ICurlPlusContainer
     */
    public function setCurlOpts(array $opts);

    /**
     * @return array
     */
    public function getCurlOpts();

    /**
     * @return ICurlPlusContainer
     */
    public function resetCurlOpts();
}