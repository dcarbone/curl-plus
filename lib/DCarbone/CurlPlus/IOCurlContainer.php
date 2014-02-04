<?php namespace DCarbone\CurlPlus;

/**
 * Interface IOCurlContainer
 * @package DCarbone\CurlPlus
 */
interface IOCurlContainer
{
    /**
     * @return CurlPlusClient
     */
    public function &getClient();

    /**
     * @return array
     */
    public function getRequestHeaders();

    /**
     * @param array $headers
     * @return void
     */
    public function setRequestHeaders(array $headers);

    /**
     * @param string $header
     * @return void
     */
    public function addRequestHeaderString($header);

    /**
     * @param int $opt
     * @param mixed $value
     * @return void
     */
    public function setCurlOpt($opt, $value);

    /**
     * @param int $opt
     * @return void
     */
    public function removeCurlOpt($opt);

    /**
     * @param array $opts
     * @return void
     */
    public function setCurlOpts(array $opts);

    /**
     * @return array
     */
    public function getCurlOpts();

    /**
     * @return void
     */
    public function resetCurlOpts();
}