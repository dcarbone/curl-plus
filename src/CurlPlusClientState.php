<?php namespace DCarbone\CurlPlus;

/**
 * Class CurlPlusClientState
 * @package DCarbone\CurlPlus
 */
abstract class CurlPlusClientState
{
    const STATE_NEW         = 0;
    const STATE_INITIALIZED = 1;
    const STATE_EXECUTING   = 2;
    const STATE_EXECUTED    = 3;
    const STATE_CLOSED      = 4;
}