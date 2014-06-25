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

    // @TODO Determine if these should be added to the interface.
//    /**
//     * Returns true if passed in curlopt has a value
//     *
//     * @param int $opt
//     * @return bool
//     */
//    public function curlOptSet($opt);
//
//    /**
//     * Returns value, if set, of curlopt
//     *
//     * @param int $opt
//     * @return mixed
//     */
//    public function getCurlOptValue($opt);

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